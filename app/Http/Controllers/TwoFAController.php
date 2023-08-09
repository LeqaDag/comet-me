<?php
  
namespace App\Http\Controllers;
  
use Illuminate\Http\Request;
use Session;
use App\Models\UserCode;
  
class TwoFAController extends Controller
{
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