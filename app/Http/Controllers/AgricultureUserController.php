<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Models\User;
use App\Models\Community;
use App\Models\CommunityService;
use App\Models\Household;
use App\Models\AgricultureSystem;
use App\Models\AgricultureHolderStatus;
use App\Models\AgricultureSystemCycle;
use App\Models\AgricultureHolder;
use App\Models\AgricultureHolderSystem;
use App\Models\AzollaType;
use App\Models\AgricultureSharedHolder;
use App\Models\AgricultureHolderDonor;
use App\Models\Donor;
use App\Helpers\AzollaSystemCalculator;
use Illuminate\Support\Facades\DB;
use App\Models\AgricultureInstallationType;


class AgricultureUserController extends Controller
{ 
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        try {
            // Get agriculture holders by status with related data
            $requestedHolders = AgricultureHolder::with([
                'community', 
                'household', 
                'agricultureHolderStatus', 
                'agricultureSystems.azollaType'
            ])
            ->whereHas('agricultureHolderStatus', function($query) {
                $query->where('english_name', 'Requested');
            })
            ->orderBy('requested_date', 'desc')
            ->get();

            $confirmedHolders = AgricultureHolder::with([
                'community', 
                'household', 
                'agricultureHolderStatus', 
                'agricultureSystems.azollaType'
            ])
            ->whereHas('agricultureHolderStatus', function($query) {
                $query->where('english_name', 'Confirmed');
            })
            ->orderBy('requested_date', 'desc')
            ->get();

            $progressHolders = AgricultureHolder::with([
                'community', 
                'household', 
                'agricultureHolderStatus', 
                'agricultureSystems.azollaType'
            ])
            ->whereHas('agricultureHolderStatus', function($query) {
                $query->where('english_name', 'In Progress');
            })
            ->orderBy('requested_date', 'desc')
            ->get();

            $completedHolders = AgricultureHolder::with([
                'community', 
                'household', 
                'agricultureHolderStatus', 
                'agricultureSystems.azollaType'
            ])
            ->whereHas('agricultureHolderStatus', function($query) {
                $query->where('english_name', 'Completed');
            })
            ->orderBy('completed_date', 'desc')
            ->get();
            
            return view('agriculture.user.index', compact(
                'requestedHolders',
                'confirmedHolders', 
                'progressHolders',
                'completedHolders'
            ));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading agriculture users: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            return back()->with('error', 'You do not have permission to create agriculture users.');
        }

        try {
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
                
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $people = User::select('id', 'name')->orderBy('name', 'ASC')->get();
            $donors = Donor::where('is_archived', 0)->orderBy('donor_name', 'ASC')->get();

            // Fetch agriculture installation types using the model
            $agricultureInstallationTypes = AgricultureInstallationType::where('is_archived', 0)
                ->orderBy('id', 'asc')
                ->get();

            // Fetch agriculture system cycles
            $agricultureSystemCycles = AgricultureSystemCycle::orderBy('name', 'ASC')->get();

            return view('agriculture.user.create', compact(
                'communities', 
                'households',
                'people',
                'donors',
                'agricultureInstallationTypes',
                'agricultureSystemCycles'
            ));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading form data: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            return back()->with('error', 'You do not have permission to create agriculture users.');
        }

        // Enhanced validation with herd size requirement
        $request->validate([
            'community_id' => 'required|exists:communities,id',
            'household_id' => 'required|exists:households,id',
            'agriculture_installation_types_id' => 'nullable|exists:agriculture_installation_types,id',
            'agriculture_system_cycle_id' => 'nullable|exists:agriculture_system_cycles,id',
            'size_of_herds' => 'required|integer|min:1|max:10000', // Required field for herd size
            'azolla_unit' => 'nullable|integer|min:0', // Auto-calculated, not required input
            'size_of_goat' => 'nullable|integer|min:0',
            'size_of_cow' => 'nullable|integer|min:0',
            'size_of_camel' => 'nullable|integer|min:0',
            'size_of_chicken' => 'nullable|integer|min:0',
            'requested_date' => 'nullable|date',
            'completed_date' => 'nullable|date|after_or_equal:requested_date',
            'contribution_rate' => 'nullable|numeric|min:0|max:100',
            'area' => 'nullable|string|max:255',
            'area_of_installation' => 'nullable|string|in:A,B,C',
            'alternative_area' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000'
        ], [
            'size_of_herds.required' => 'Herd size is required to calculate systems automatically.',
            'size_of_herds.min' => 'Herd size must be at least 1 sheep.',
            'size_of_herds.max' => 'Herd size cannot exceed 10,000 sheep.',
            'completed_date.after_or_equal' => 'Completed date cannot be before requested date.'
        ]);

        try {
            DB::beginTransaction();
            
            // Get herd size and validate business rules
            $herdSize = (int) $request->size_of_herds;
            $calculationSummary = AzollaSystemCalculator::generateCalculationSummary($herdSize);
            
            // Check for validation errors from business rules
            if (!$calculationSummary['is_valid']) {
                return back()->withInput()
                    ->with('error', 'Herd size validation failed: ' . implode(', ', $calculationSummary['validation_errors']));
            }

            // Find the "Requested" status (create it if it doesn't exist)
            $requestedStatus = AgricultureHolderStatus::firstOrCreate(
                ['english_name' => 'Requested'],
                [
                    'english_name' => 'Requested',
                    'arabic_name' => 'مطلوب', // Arabic translation
                    'comet_id' => 'AGR_STATUS_REQ_' . time()
                ]
            );

            // Calculate azolla units automatically
            $azollaUnits = AzollaSystemCalculator::calculateAzollaUnits($herdSize);
            
            // Get the default agriculture system cycle before creating the holder
            $defaultCycle = $this->getDefaultAgricultureSystemCycle();
            
            // Create AgricultureHolder record with "Requested" status
            $agricultureHolder = AgricultureHolder::create([
                'community_id' => $request->community_id,
                'household_id' => $request->household_id,
                'agriculture_holder_status_id' => $requestedStatus->id,
                'agriculture_system_cycle_id' => $request->input('agriculture_system_cycle_id') ?? $defaultCycle->id, // allow override from form
                'agriculture_installation_types_id' => $request->input('agriculture_installation_types_id') ?? null,
                'azolla_unit' => $azollaUnits, // Auto-calculated
                'size_of_herds' => $herdSize,
                'size_of_goat' => $request->size_of_goat ?? 0,
                'size_of_cow' => $request->size_of_cow ?? 0,
                'size_of_camel' => $request->size_of_camel ?? 0,
                'size_of_chicken' => $request->size_of_chicken ?? 0,
                'requested_date' => $request->requested_date ?? now(),
                'area_of_installation' => $request->input('area_of_installation') ?? null,
                'completed_date' => $request->completed_date,
                'contribution_rate' => $request->contribution_rate,
                'area' => $request->area,
                'alternative_area' => $request->alternative_area,
                'notes' => $request->notes
            ]);

            // Save system requirements to agriculture_holder_systems without creating actual systems
            $systemsCreated = $this->saveSystemRequirements($agricultureHolder, $calculationSummary['systems']);
            
            if ($systemsCreated === 0) {
                throw new \Exception('No system requirements were saved. Please check the calculation logic.');
            }

            // Persist shared herd information if present in the form
            if ($request->has('shared_herd') && intval($request->input('number_of_people', 0)) > 0) {
                $numberOfPeople = intval($request->input('number_of_people'));
                for ($i = 1; $i <= $numberOfPeople; $i++) {
                    $householdField = 'household_' . $i . '_name';
                    $sheepField = 'household_' . $i . '_sheep';

                    $householdId = $request->input($householdField);
                    $sheepCount = intval($request->input($sheepField, 0));

                    // Resolve household name when household id is provided
                    $householdName = null;
                    if ($householdId) {
                        $hh = Household::find($householdId);
                        if ($hh) {
                            $householdName = $hh->english_name;
                        }
                    }

                    // Create record in the existing table schema: agriculture_holder_id, household_id, size_of_herds, is_archived
                    // size_of_herds is required by the DB, so store the sheep count (0 allowed)
                    AgricultureSharedHolder::create([
                        'agriculture_holder_id' => $agricultureHolder->id,
                        'household_id' => $householdId ?: null,
                        'size_of_herds' => $sheepCount,
                        'is_archived' => 0
                    ]);
                }
            }

            // Persist donors if provided (agriculture_holder_donors)
            if ($request->has('donor_herd') || $request->has('number_of_donors')) {
                $numDonors = intval($request->input('number_of_donors', 0));
                for ($d = 1; $d <= $numDonors; $d++) {
                    $donorField = 'donor_' . $d . '_id';
                    $donorId = $request->input($donorField);
                    if ($donorId) {
                        AgricultureHolderDonor::create([
                            'agriculture_holder_id' => $agricultureHolder->id,
                            'donor_id' => $donorId
                        ]);
                    }
                }
            }

            DB::commit();
            
            $successMessage = sprintf(
                'Agriculture Holder created successfully with "Requested" status! Generated %d Azolla units and assigned %d existing systems for %d sheep.',
                $azollaUnits,
                $systemsCreated,
                $herdSize
            );
            
            return redirect()->route('argiculture-user.index')
                ->with('message', $successMessage);
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error creating agriculture user: ' . $e->getMessage());
        }
    }

    /**
     * Assign existing agriculture systems to holder based on calculated requirements
     *
     * @param AgricultureHolder $holder
     * @param array $systemsNeeded
     * @return int Number of systems assigned
     */
    private function saveSystemRequirements(AgricultureHolder $holder, array $systemsNeeded): int
    {
        $systemsAssigned = 0;
        
        foreach ($systemsNeeded as $index => $systemRequirement) {
            // Find existing agriculture system based on system type
            $existingSystem = $this->findExistingAgricultureSystem($systemRequirement['system_type']);
            
            if ($existingSystem) {
                // Assign existing system to holder
                AgricultureHolderSystem::create([
                    'agriculture_holder_id' => $holder->id,
                    'agriculture_system_id' => $existingSystem->id,
                    'is_archived' => 0
                ]);
                
                $systemsAssigned++;
            }
        }
        
        return $systemsAssigned;
    }

    /**
     * Find existing agriculture system based on system type
     *
     * @param string $systemType
     * @return AgricultureSystem|null
     */
    private function findExistingAgricultureSystem(string $systemType): ?AgricultureSystem
    {
        // Map system types to existing system names
        $systemMapping = [
            'azolla_20' => 'Azolla 20 units',   // ID 3
            'azolla_50' => 'Azolla 50 units',   // ID 4  
            'azolla_100' => 'Azolla 100 units'  // ID 5
        ];
        
        $systemName = $systemMapping[$systemType] ?? null;
        
        if ($systemName) {
            return AgricultureSystem::where('name', $systemName)
                ->where('is_archived', 0)
                ->first();
        }
        
        return null;
    }

    /**
     * Get or create Azolla Type based on system type
     */
    private function getOrCreateAzollaType(string $systemType): AzollaType
    {
        $typeMapping = [
            'azolla_20' => ['name' => 'Azolla 20 Unit', 'description' => 'Supports 1-20 sheep'],
            'azolla_50' => ['name' => 'Azolla 50 Unit', 'description' => 'Supports 21-50 sheep'],
            'azolla_100' => ['name' => 'Azolla 100 Unit', 'description' => 'Supports 51-100 sheep']
        ];

        $typeData = $typeMapping[$systemType] ?? $typeMapping['azolla_20'];
        
        return AzollaType::firstOrCreate(
            ['name' => $typeData['name']],
            [
                'name' => $typeData['name'],
                'description' => $typeData['description'],
                'is_archived' => 0
            ]
        );
    }

    /**
     * Get the default agriculture system cycle
     */
    private function getDefaultAgricultureSystemCycle(): AgricultureSystemCycle
    {
        // Try to get an existing cycle, or create a default one
        return AgricultureSystemCycle::firstOrCreate(
            ['name' => 'Standard Cycle'],
            [
                'name' => 'Standard Cycle',
                'description' => 'Default agriculture system cycle for new systems',
                'is_archived' => 0
            ]
        );
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        try {
            // Load the real agriculture holder with related data
            $agricultureUser = AgricultureHolder::with([
                'community',
                'household',
                'agricultureHolderStatus',
                'agricultureSystems.azollaType',
                'agricultureSharedHolders.household'
            ])->findOrFail($id);

            // Also fetch shared herd rows directly from DB as a fallback (in case of relation issues)
            $sharedHerds = DB::table('agriculture_shared_holders')
                ->where('agriculture_holder_id', $agricultureUser->id)
                ->orderBy('id', 'asc')
                ->get();

            // Also fetch donors for this holder
            $holderDonors = AgricultureHolderDonor::with('donor')
                ->where('agriculture_holder_id', $agricultureUser->id)
                ->orderBy('id', 'asc')
                ->get();

            return view('agriculture.user.show', compact('agricultureUser', 'sharedHerds', 'holderDonors'));

        } catch (\Exception $e) {
            return back()->with('error', 'Agriculture user not found.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            return back()->with('error', 'You do not have permission to edit agriculture users.');
        }

        try {
            // Load the real agriculture holder for editing
            $agricultureUser = AgricultureHolder::with([
                'community',
                'household',
                'agricultureSharedHolders',
                'agricultureHolderDonors.donor'
            ])->findOrFail($id);

            $donors = Donor::where('is_archived', 0)->orderBy('donor_name', 'ASC')->get();
            
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
                
            $households = Household::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();

            $agricultureSystems = AgricultureSystem::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            $agricultureHolderStatuses = AgricultureHolderStatus::orderBy('english_name', 'ASC')
                ->get();

            $agricultureSystemCycles = AgricultureSystemCycle::orderBy('name', 'ASC')
                ->get();

            // also load installation types for edit view via model
            $agricultureInstallationTypes = AgricultureInstallationType::where('is_archived', 0)
                ->orderBy('id', 'asc')
                ->get();

            return view('agriculture.user.edit', compact(
                'agricultureUser', 
                'communities', 
                'households', 
                'agricultureSystems', 
                'agricultureHolderStatuses', 
                'agricultureSystemCycles',
                'donors',
                'agricultureInstallationTypes'
            ));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Agriculture user not found.');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            return back()->with('error', 'You do not have permission to update agriculture users.');
        }

        // Validate main fields
        $request->validate([
            'community_id' => 'nullable|exists:communities,id',
            'household_id' => 'nullable|exists:households,id',
            'agriculture_installation_types_id' => 'nullable|exists:agriculture_installation_types,id',
            'area_of_installation' => 'nullable|string|in:A,B,C',
            'agriculture_system_cycle_id' => 'nullable|exists:agriculture_system_cycles,id',
            'size_of_herds' => 'nullable|integer|min:0|max:10000',
            'size_of_goat' => 'nullable|integer|min:0',
            'size_of_cow' => 'nullable|integer|min:0',
            'size_of_camel' => 'nullable|integer|min:0',
            'size_of_chicken' => 'nullable|integer|min:0',
            'requested_date' => 'nullable|date',
            'completed_date' => 'nullable|date|after_or_equal:requested_date',
            'contribution_rate' => 'nullable|numeric|min:0|max:100',
            'area' => 'nullable|string|max:255',
            'alternative_area' => 'nullable|string|max:1000',
            'notes' => 'nullable|string|max:1000',
            'shared_herd' => 'nullable',
            'number_of_people' => 'nullable|integer|min:0'
        ]);

        try {
            DB::beginTransaction();

            $holder = AgricultureHolder::findOrFail($id);

            // Update holder fields
            $holder->update([
                'community_id' => $request->community_id,
                'household_id' => $request->household_id,
                'agriculture_installation_types_id' => $request->agriculture_installation_types_id ?? $holder->agriculture_installation_types_id,
                'area_of_installation' => $request->area_of_installation ?? $holder->area_of_installation,
                'agriculture_system_cycle_id' => $request->agriculture_system_cycle_id ?? $holder->agriculture_system_cycle_id,
                'size_of_herds' => $request->size_of_herds ?? $holder->size_of_herds,
                'size_of_goat' => $request->size_of_goat ?? $holder->size_of_goat,
                'size_of_cow' => $request->size_of_cow ?? $holder->size_of_cow,
                'size_of_camel' => $request->size_of_camel ?? $holder->size_of_camel,
                'size_of_chicken' => $request->size_of_chicken ?? $holder->size_of_chicken,
                'requested_date' => $request->requested_date ?? $holder->requested_date,
                'completed_date' => $request->completed_date ?? $holder->completed_date,
                'contribution_rate' => $request->contribution_rate ?? $holder->contribution_rate,
                'area' => $request->area ?? $holder->area,
                'alternative_area' => $request->alternative_area ?? $holder->alternative_area,
                'notes' => $request->notes ?? $holder->notes
            ]);

            $herdSizeForCalc = intval($request->input('size_of_herds', $holder->size_of_herds ?? 0));
            if ($herdSizeForCalc > 0) {
                $calculationSummary = AzollaSystemCalculator::generateCalculationSummary($herdSizeForCalc);
                if (!$calculationSummary['is_valid']) {
                    // rollback will be handled in catch; throw to surface validation
                    throw new \Exception('Herd size validation failed: ' . implode(', ', $calculationSummary['validation_errors']));
                }

                // Remove any existing holder-system assignments and save new requirements
                AgricultureHolderSystem::where('agriculture_holder_id', $holder->id)->delete();
                $systemsCreated = $this->saveSystemRequirements($holder, $calculationSummary['systems']);
                
                // Update azolla units on holder
                $holder->azolla_unit = $calculationSummary['azolla_units'];
                $holder->save();
            }
            
            // Replace shared herd rows: delete existing and recreate from form
            if ($request->has('shared_herd')) {
                AgricultureSharedHolder::where('agriculture_holder_id', $holder->id)->delete();
                $numberOfPeople = intval($request->input('number_of_people', 0));
                for ($i = 1; $i <= $numberOfPeople; $i++) {
                    $householdField = 'household_' . $i . '_name';
                    $sheepField = 'household_' . $i . '_sheep';

                    $householdId = $request->input($householdField);
                    $sheepCount = intval($request->input($sheepField, 0));

                    AgricultureSharedHolder::create([
                        'agriculture_holder_id' => $holder->id,
                        'household_id' => $householdId ?: null,
                        'size_of_herds' => $sheepCount,
                        'is_archived' => 0
                    ]);
                }
            } else {
                // If shared_herd unchecked, remove existing shared rows
                AgricultureSharedHolder::where('agriculture_holder_id', $holder->id)->delete();
            }

            // Replace donors for this holder
            if ($request->has('donor_herd')) {
                AgricultureHolderDonor::where('agriculture_holder_id', $holder->id)->delete();
                $numDonors = intval($request->input('number_of_donors', 0));
                for ($d = 1; $d <= $numDonors; $d++) {
                    $donorField = 'donor_' . $d . '_id';
                    $donorId = $request->input($donorField);
                    if ($donorId) {
                        AgricultureHolderDonor::create([
                            'agriculture_holder_id' => $holder->id,
                            'donor_id' => $donorId
                        ]);
                    }
                }
            } else {
                AgricultureHolderDonor::where('agriculture_holder_id', $holder->id)->delete();
            }

            DB::commit();

            return redirect()->route('argiculture-user.index')
                ->with('message', 'Agriculture User updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Error updating agriculture user: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        if (!Auth::guard('user')->user()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return back()->with('error', 'You do not have permission to delete agriculture users.');
        }

        try {
            // In full implementation, this would soft delete the AgricultureUser record
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Agriculture User deleted successfully! (Basement version)'
                ]);
            }
            
            return redirect()->route('argiculture-user.index')
                ->with('message', 'Agriculture User deleted successfully! (Basement version)');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error deleting user: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting Agriculture User!');
        }
    }

    /**
     * Get agriculture users by community (AJAX endpoint)
     *
     * @param  int  $community_id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAgricultureUsersByCommunity($community_id)
    {
        if (!Auth::guard('user')->user()) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        try {
            // Basement implementation - return empty array
            $agricultureUsers = [];
            
            return response()->json($agricultureUsers);
            
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error fetching agriculture users'], 500);
        }
    }

    /**
     * Export agriculture users data
     */
    public function export(Request $request)
    {
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        try {
            // Basement implementation - just return a message
            return back()->with('message', 'Export functionality not implemented in basement version');
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error exporting agriculture users: ' . $e->getMessage());
        }
    }

    /**
     * Approve holder - change status to confirmed (status_id = 2)
     */
    public function approve(Request $request, $id)
    {
        if (!Auth::guard('user')->user()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to approve agriculture holders.'], 403);
            }
            return back()->with('error', 'You do not have permission to approve agriculture holders.');
        }

        try {
            DB::beginTransaction();
            
            // Find the agriculture holder
            $holder = AgricultureHolder::findOrFail($id);
            
            // Find or create the "Confirmed" status
            $confirmedStatus = AgricultureHolderStatus::firstOrCreate(
                ['english_name' => 'Confirmed'],
                [
                    'english_name' => 'Confirmed',
                    'arabic_name' => 'مؤكد',
                    'comet_id' => 'AGR_STATUS_CONF_' . time()
                ]
            );
            
            // Update holder status to confirmed
            $holder->update([
                'agriculture_holder_status_id' => $confirmedStatus->id,
                'confirmation_date' => now()
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Agriculture holder approved successfully and moved to confirmed status.'
                ]);
            }
            
            return redirect()->route('argiculture-user.index')
                ->with('message', 'Agriculture holder approved successfully and moved to confirmed status.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error approving holder: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error approving agriculture holder: ' . $e->getMessage());
        }
    }

    /**
     * Reject holder - change status to rejected
     */
    public function reject(Request $request, $id)
    {
        if (!Auth::guard('user')->user()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to reject agriculture holders.'], 403);
            }
            return back()->with('error', 'You do not have permission to reject agriculture holders.');
        }

        try {
            DB::beginTransaction();
            
            // Find the agriculture holder
            $holder = AgricultureHolder::findOrFail($id);
            
            // Find or create the "Rejected" status
            $rejectedStatus = AgricultureHolderStatus::firstOrCreate(
                ['english_name' => 'Rejected'],
                [
                    'english_name' => 'Rejected',
                    'arabic_name' => 'مرفوض',
                    'comet_id' => 'AGR_STATUS_REJ_' . time()
                ]
            );
            
            // Update holder status to rejected
            $holder->update([
                'agriculture_holder_status_id' => $rejectedStatus->id
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Agriculture holder rejected successfully.'
                ]);
            }
            
            return redirect()->route('argiculture-user.index')
                ->with('message', 'Agriculture holder rejected successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error rejecting holder: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error rejecting agriculture holder: ' . $e->getMessage());
        }
    }

    /**
     * Move holder to progress - change status to in progress
     */
    public function moveToProgress(Request $request, $id)
    {
        if (!Auth::guard('user')->user()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to move agriculture holders.'], 403);
            }
            return back()->with('error', 'You do not have permission to move agriculture holders.');
        }

        try {
            DB::beginTransaction();
            
            // Find the agriculture holder
            $holder = AgricultureHolder::findOrFail($id);
            
            // Find or create the "In Progress" status
            $progressStatus = AgricultureHolderStatus::firstOrCreate(
                ['english_name' => 'In Progress'],
                [
                    'english_name' => 'In Progress',
                    'arabic_name' => 'قيد التنفيذ',
                    'comet_id' => 'AGR_STATUS_PROG_' . time()
                ]
            );
            
            // Update holder status to in progress
            $holder->update([
                'agriculture_holder_status_id' => $progressStatus->id
            ]);
            
            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Agriculture holder moved to progress successfully.'
                ]);
            }
            
            return redirect()->route('argiculture-user.index')
                ->with('message', 'Agriculture holder moved to progress successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error moving holder to progress: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error moving agriculture holder to progress: ' . $e->getMessage());
        }
    }

    /**
     * Mark holder as complete - change status to completed
     */
    public function markComplete(Request $request, $id)
    {
        if (!Auth::guard('user')->user()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
            }
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        // Check user permissions
        $userType = Auth::guard('user')->user()->user_type_id;
        if (!in_array($userType, [1, 2, 14])) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'You do not have permission to complete agriculture holders.'], 403);
            }
            return back()->with('error', 'You do not have permission to complete agriculture holders.');
        }

        try {
            DB::beginTransaction();
            
            // Find the agriculture holder
            $holder = AgricultureHolder::findOrFail($id);
            
            // Find or create the "Completed" status
            $completedStatus = AgricultureHolderStatus::firstOrCreate(
                ['english_name' => 'Completed'],
                [
                    'english_name' => 'Completed',
                    'arabic_name' => 'مكتمل',
                    'comet_id' => 'AGR_STATUS_COMP_' . time()
                ]
            );
            
            // Update holder status to completed and set completed date
            $holder->update([
                'agriculture_holder_status_id' => $completedStatus->id,
                'completed_date' => now()
            ]);
            
            // Add a new record in community_services to link the community with agriculture service
            // Ensure that the CommunityService record exists for this community and service_id 5
            $communityService = CommunityService::firstOrCreate(
                ['community_id' => $holder->community_id, 'service_id' => 5]
            );

            $community = Community::FindOrFail($holder->community_id);
            $community->agriculture_service = "Yes";
            $community->agriculture_service_beginning_year = now()->year;
            $community->save();


            DB::commit();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Agriculture holder marked as completed successfully.'
                ]);
            }
            
            return redirect()->route('argiculture-user.index')
                ->with('message', 'Agriculture holder marked as completed successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error marking holder as complete: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error marking agriculture holder as complete: ' . $e->getMessage());
        }
    }
}