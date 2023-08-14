<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Session;
use App\Models\UserCode;
use Auth;

class TwoFAController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Write code on Method
     *
     * @return response() 
     */
    public function index() 
    {

        return view('auth.2fa');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        if (Auth::guard('user')->user() != null) {

            $request->validate([
                'code'=>'required',
            ]);
      
            $find = UserCode::where('user_id', auth()->guard('user')->user()->id)
                ->where('code', $request->code)
                ->where('updated_at', '>=', now()->subMinutes(2))
                ->first();
      
            if (!is_null($find)) {
    
                Session::put('user_2fa', auth()->guard('user')->user()->id);

                return redirect()->route('home');
            }
      
            return back()->with('error', 'You Entered Wrong Code.');
        } else {

            return view('errors.not-found');
        }
    }

    /**
     * Write code on Method
     *
     * @return response()
     */
    public function resend()
    {
        auth()->guard('user')->user()->generateCode();
  
        return back()->with('success', 'We sent you code on your email.');
    }
}