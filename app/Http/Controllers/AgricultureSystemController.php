<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Community;
use App\Models\AgricultureSystemCycle;
use App\Models\AgricultureSystem;
use App\Models\AzollaType;
use App\Helpers\SequenceHelper;
use Yajra\DataTables\DataTables;
use Auth;

class AgricultureSystemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Check if user is authenticated
        if (!Auth::guard('user')->user()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        try {
            // Get all active agriculture systems with related data
            $systems = AgricultureSystem::with(['azollaType', 'agricultureSystemCycle'])
                ->where('is_archived', 0)
                ->orderBy('created_at', 'desc')
                ->get();

            return view('system.agriculture.index', compact('systems'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Error loading agriculture systems: ' . $e->getMessage());
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
            return back()->with('error', 'You do not have permission to create agriculture systems.');
        }

        try {
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
                
            $agricultureSystemCycles = AgricultureSystemCycle::orderBy('name', 'ASC')
                ->get();

            $azollaTypes = AzollaType::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('system.agriculture.create', compact('communities', 'agricultureSystemCycles', 'azollaTypes'));
            
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
            return back()->with('error', 'You do not have permission to create agriculture systems.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:agriculture_systems,name',
            'azolla_type_id' => 'required|exists:azolla_types,id',
            'agriculture_system_cycle_id' => 'nullable|exists:agriculture_system_cycles,id',
            'installation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $cometId = SequenceHelper::generateAgricultureSystemCometId();
            
            AgricultureSystem::create([
                'comet_id' => $cometId,
                'name' => $request->name,
                'azolla_type_id' => $request->azolla_type_id,
                'agriculture_system_cycle_id' => $request->agriculture_system_cycle_id,
                'installation_year' => $request->installation_year,
                'description' => $request->description,
                'fake_meter_number' => SequenceHelper::generateAgricultureFakeMeterNumber($cometId),
                'is_archived' => 0,
            ]);

            return redirect()->route('agriculture-system.index')
                ->with('message', 'Agriculture System created successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error creating agriculture system: ' . $e->getMessage());
        }
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
            $system = AgricultureSystem::with(['azollaType', 'agricultureSystemCycle'])->findOrFail($id);
            
            if (request()->ajax()) {
                return view('system.agriculture.show-modal', compact('system'));
            }
            
            return view('system.agriculture.show', compact('system'));
            
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json(['error' => 'System not found'], 404);
            }
            return back()->with('error', 'Agriculture system not found.');
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
            return back()->with('error', 'You do not have permission to edit agriculture systems.');
        }

        try {
            $system = AgricultureSystem::findOrFail($id);
            
            $communities = Community::where('is_archived', 0)
                ->orderBy('english_name', 'ASC')
                ->get();
                
            $agricultureSystemCycles = AgricultureSystemCycle::orderBy('name', 'ASC')
                ->get();

            $azollaTypes = AzollaType::where('is_archived', 0)
                ->orderBy('name', 'ASC')
                ->get();

            return view('system.agriculture.edit', compact('system', 'communities', 'agricultureSystemCycles', 'azollaTypes'));
            
        } catch (\Exception $e) {
            return back()->with('error', 'Agriculture system not found.');
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
            return back()->with('error', 'You do not have permission to update agriculture systems.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:agriculture_systems,name,' . $id,
            'azolla_type_id' => 'required|exists:azolla_types,id',
            'agriculture_system_cycle_id' => 'nullable|exists:agriculture_system_cycles,id',
            'installation_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'description' => 'nullable|string|max:1000'
        ]);

        try {
            $system = AgricultureSystem::findOrFail($id);
            
            $system->update([
                'name' => $request->name,
                'azolla_type_id' => $request->azolla_type_id,
                'agriculture_system_cycle_id' => $request->agriculture_system_cycle_id,
                'installation_year' => $request->installation_year,
                'description' => $request->description,
            ]);

            return redirect()->route('agriculture-system.index')
                ->with('message', 'Agriculture System updated successfully!');
                
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', 'Error updating agriculture system: ' . $e->getMessage());
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
            return back()->with('error', 'You do not have permission to delete agriculture systems.');
        }

        try {
            $system = AgricultureSystem::findOrFail($id);
            
            // Soft delete by archiving
            $system->update(['is_archived' => 1]);
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => true, 
                    'message' => 'Agriculture System deleted successfully!'
                ]);
            }
            
            return redirect()->route('agriculture-system.index')
                ->with('message', 'Agriculture System deleted successfully!');
                
        } catch (\Exception $e) {
            if (request()->ajax()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error deleting system: ' . $e->getMessage()
                ], 500);
            }
            
            return back()->with('error', 'Error deleting Agriculture System!');
        }
    }
}