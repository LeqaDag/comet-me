<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Auth;
use DB;
use Route;
use App\Models\User;
use App\Models\UserType;
use Carbon\Carbon;
use DataTables;
use mikehaertl\wkhtmlto\Pdf;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Arr;
use Excel;
     
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
        if (Auth::guard('user')->user() != null) {

            if ($request->ajax()) {
                
                $data = DB::table('users')
                    ->join('user_types', 'users.user_type_id', '=', 'user_types.id')
                    ->where('users.is_archived', 0)
                    ->select('users.name as user_name', 'user_types.name as user_type',
                        'users.id as id', 'users.created_at as created_at', 
                        'users.updated_at as updated_at',
                        'users.email', 'users.phone', 'users.image')
                    ->latest(); 

                return Datatables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function($row) {
                        $updateButton = "<a type='button' class='updateUser' data-id='".$row->id."'><i class='fa-solid fa-pen-to-square text-success'></i></a>";
                        $deleteButton = "<a type='button' class='deleteUser' data-id='".$row->id."'><i class='fa-solid fa-trash text-danger'></i></a>";
                        
                        return $updateButton." ".$deleteButton;
                    })
                    ->addColumn('photo', function($row) {

                        return $row->image;
                    })
                
                    ->filter(function ($instance) use ($request) {
                        if (!empty($request->get('search'))) {
                                $instance->where(function($w) use($request) {
                                    $search = $request->get('search');
                                    $w->orWhere('users.name', 'LIKE', "%$search%")
                                    ->orWhere('users.email', 'LIKE', "%$search%")
                                    ->orWhere('users.phone', 'LIKE', "%$search%")
                                    ->orWhere('user_types.name', 'LIKE', "%$search%");
                            });
                        }
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }


            $userTypes = UserType::where('is_archived', 0)->get();

            return view('admin.users.index', compact('userTypes'));

        } else {

            return view('errors.not-found');
        }
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $roles = Role::pluck('name','name')->all();

        return view('users.create', compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'user_type_id' => 'required'
        ]);
    
        $input = $request->all();

        if(!empty($input['password'])) { 

            $input['password'] = Hash::make($input['password']);
        } else {

            $input = Arr::except($input,array('password'));    
        }
        
        $user = new User();
        $user->name = $input['name'];
        $user->email = $input['email'];
        $user->phone = $input['phone'];
        $user->user_type_id = $input['user_type_id'];
        $user->password = $input['password'];
        $user->save();
    
        return redirect()->back()
            ->with('success', 'User created successfully');
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = User::find($id);
        $userType = UserType::findOrFail($user->user_type_id);

        $response['user'] = $user;
        $response['userType'] = $userType;

        return response()->json([
            'response' => $response]);
    }
    
    /**
     * View Edit page.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function editPage($id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $userTypes = UserType::all();
    
        return view('admin.users.edit', compact('user', 'userTypes'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            'password' => 'same:confirm-password'
        ]);
    
        $input = $request->all();
        if(!empty($input['password'])) { 

            $input['password'] = Hash::make($input['password']);
        } else {

            $input = Arr::except($input,array('password'));    
        }
    
        $user = User::find($id);
        if($request->name) $user->name = $request->name;
        if($request->phone) $user->phone = $request->phone;
        if($request->email) $user->email = $request->email;
        if($request->user_type_id) $user->user_type_id = $request->user_type_id;
        $user->update($input);
        $extra_name = "";

        if ($request->file('image')) {
            $photo = $request->file('image');
            $original_name = $photo->getClientOriginalName();
            $extra_name  = uniqid().'_'.time().'_'.uniqid().'.'.$photo->extension();
            $encoded_base64_image = substr($photo, strpos($photo, ',') + 1);
            $resized_image = Image::make($photo->getRealPath());
            $resized_image->save('./users/profile/'.$extra_name);

			$user->image = $extra_name;
			$user->save();
        }

        return redirect()->back()
            ->with('message', 'User updated successfully');
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function deleteUser(Request $request)
    {
        $user = User::find($request->id);

        if($user) {

            $user->is_archived = 1;
            $user->save();

            $response['success'] = 1;
            $response['msg'] = 'User Deleted successfully'; 
        } else {

            $response['success'] = 0;
            $response['msg'] = 'Invalid ID.';
        }

        return response()->json($response); 
    }
}