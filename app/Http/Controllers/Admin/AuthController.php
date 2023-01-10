<?php

namespace App\Http\Controllers\Admin;

use App\Models\Admin;
use App\Models\SiteSetting;
use Illuminate\Support\Str;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class AuthController extends Controller
{
    use SendsPasswordResetEmails;
    public function __construct()
    {

    }

    public function showLinkRequestForm()
    {
        return view('admin.auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins',
        ]);
        //return $request->all();
        $token = Str::random(64);
        DB::table('password_resets')->insert([
            'email' => $request->email, 
            'token' => $token, 
            'created_at' => Carbon::now()
        ]);
        Mail::send('admin.auth.passwords.forgetPassword', ['token' => $token], function($message) use($request){
            $message->to($request->email);
            $message->subject('Reset Password');
        });
        return back()->with('success', (app()->getLocale() == 'en') ? 'Your password has been changed!': 'আমরা আপনার পাসওয়ার্ড রিসেট লিঙ্ক ই-মেইল কারা হয়েছে ');
    }


    public function showResetPasswordForm($token)
    {
        return view('admin.auth.passwords.forgetPasswordLink',compact('token'));
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:admins',
            'password' => 'required|string|min:6|confirmed',
            'password_confirmation' => 'required'
        ]);

        $updatePassword = DB::table('password_resets')
                            ->where([
                            'email' => $request->email, 
                            'token' => $request->token
                            ])
                            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = Admin::where('email', $request->email)
                    ->update(['password' => Hash::make($request->password)]);

        DB::table('password_resets')->where(['email'=> $request->email])->delete();

        return redirect()->route('admin.login')->with('success', (app()->getLocale() == 'en') ? 'Your password has been changed!': 'আপনার পাসওয়ার্ড পরিবর্তন করা হয়েছে!');
    }

    public function login()
    {
        return view('admin.auth.login');
    }

    public function loginStore(Request $request)
    {
        $this->validate($request, [
            'email'   => 'required',
            'password' => 'required|min:6'
        ]);

        if (Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            //$sitesetting = SiteSetting::where(['status'=>1])->first();
            //$request->session()->put('sitesetting', $sitesetting);
            return redirect()->intended('/admin/dashboard');
            //return Redirect::route('admin.dashboard');
        }elseif (Auth::guard('admin')->attempt(['username' => $request->email, 'password' => $request->password], $request->get('remember'))) {
            return redirect()->intended('/admin/dashboard');
        }
        Session::flash('error', (app()->getLocale() == 'en') ? 'email or password does not match': 'ইমেইল বা পাসওয়ার্ড মিলছে না');
        return back()->withInput($request->only('email', 'remember'));
    }

    public function changePassword(Request $request, $id)
    {
        //$this->authorize('change_password',Admin::class);
        $this->validate($request, [
            'password' => 'required|confirmed|min:6',
        ]);
        
        try {
            $data['password'] = Hash::make($request->password);
            Admin::findOrFail($id)->update($data);
        } catch (\Throwable $exception) {
            return back()->with([
                //'error' => __('admin.common.error'),
                'error' => $exception->getMessage(),
                'alert-type' => 'error'
              ]);
        }

        return back()->with([
            'message' => __('admin.common.success'),
            'alert-type' => 'success'
        ]);
    }

    protected function guard()
    {
        return Auth::guard('admin');
    }

    public function logout(Request $request)
    {
        //return "Hello";
        //Auth::guard('web')->logout();
        $this->guard('admin')->logout();
        //$request->session()->invalidate();
        //$request->session()->regenerateToken();
        return redirect('admin/login');
    }

    

}
