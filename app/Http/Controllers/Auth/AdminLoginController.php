<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Route;

class AdminLoginController extends Controller
{
   
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }
    
    public function showLoginForm()
    {
        $pageConfigs = ['myLayout' => 'blank'];

        return view('auth.adminLogin', ['pageConfigs' => $pageConfigs]);
    }
   
    public function login(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required|email',
            'password' => 'required|min:6'
        ]);
       
        if (auth()->guard('admin')->attempt(['email' => $request->email, 
        'password' => $request->password]))
        {
            if (auth()->guard('admin')->user()->is_admin == 1 && auth()->guard('admin')->user()->type == 1) {

                return redirect()->route('admin.dashboard');
            } 
            
            // else if (auth()->user()->type == 0) {

            //     return redirect()->route('employee.dashboard');
            // }

        } 
      
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }
    
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/admin/login');
    }
}