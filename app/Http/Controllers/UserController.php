<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SebastianBergmann\Diff\Diff;
use Illuminate\Support\Facades\Mail;
use App\Mail\SendVerificationOtpMail;

class UserController extends Controller
{
    //
    public function index()
    {
        return "User Controller";
    }
    public function getUsers()
    {
        $user = Auth::User();
        // $users = User::all();
        return view('users', compact('user'));
    }

    public function createUser(Request $request)
    {

        $request->validate([
            'userName' => 'required|string|max:255',
            'userEmail' => 'required|email|max:255',
            'userPassword' => 'required|string|min:8|max:255',
            'userAge' => 'required|integer|min:10|max:50',
            'userDateOfBirth' => 'required|date|before:2015-01-01',
            'userGender' => 'required|in:male,female',
            'userPercentage' => 'required|integer|min:0|max:100',
            'userType' => 'required|in:teacher,student',
        ], [
            'userName.required' => 'User name is required',
            'userEmail.required' => 'Email is required',
            'userPassword.required' => 'Password is required',
            'userAge.required' => 'Age is required',
            'userAge.max' => 'Age must be under 50',
            'userDateOfBirth.required' => 'Date of birth is required',
            'userGender.required' => 'Gender is required',
            'userPercentage.required' => 'Percentage is required',
            'userType.required' => 'User type is required',
        ]);

        $imagePath = null;
        if ($request->hasFile('userImage')) {
            $imagePath = $request->file('userImage')->store('photoes', 'public');
        }
        $user = new User();
        $user->name = $request->userName;
        $user->email = $request->userEmail;
        $user->password = $request->userPassword;
        $user->age = $request->userAge;
        $user->percentage = $request->userPercentage;
        $user->date_of_birth = $request->userDateOfBirth;
        $user->gender = $request->userGender;
        $user->userType = $request->userType;
        $user->profileImage = $imagePath;
        $user->save();
        session()->flash('success', 'user created successfully');
        session(['userMail' => $request->userEmail]);

        // $userMail = $request->userEmail;
        return redirect('/verifyotp');
    }



    public function verifyotpview()
    {
        $user = Auth::User();
        // $userMail = $request->userMail;
        $userMail = session('userMail');

        return view('verifyotp', compact('user', 'userMail'));
    }

    public function addUser(Request $request)
    {
        $user = Auth::User();
        return view('createuser', compact('user'));
    }

    public function getUsers1(Request $request)
    {
        $gender = $request->get('gender');
        $query = User::query();

        if ($gender !== 'All') {
            if ($gender == 'Male') {
                $query->where('gender', 'male');
            } else {
                $query->where('gender', 'female');
            }
        }
        return DataTables::of($query)->make(true);
    }


    public function verifyOtp(Request $request)
    {
        $request->validate([
            'otp' => 'required|numeric|digits:6',
            // 'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->userEmail)->first();


        $sessionOtp = session('otp');
        $sessionOtpExpiresAt = session('otp_expires_at');
        if ($user) {
            if (Carbon::now()->lt($sessionOtpExpiresAt)) {
                if ($sessionOtp == $request->otp) {
                    $user->email_verified_at = Carbon::now();
                    $user->save();
                    session()->flash('success', 'user created successfully with email verification');
                    return redirect('/users');

                    // return response()->json(['success' => 'Email verified successfully!'], 200);
                } else {
                    session()->flash('invalid', 'Enter valid Otp!');
                    return redirect('/users');

                    // return response()->json(['invalid' => 'Enter valid Otp!'], 400);
                }
            } else {
                session()->flash('expired', 'Otp Expired Please generate the new otp');
                return redirect('/users');
                // return response()->json(['expired' => 'Otp Expired Please  new the otp'], 400);
            }
        } else {
            session()->flash('error', 'User not found');
            return redirect('/users');
            // return response()->json(['error' => 'User not found'], 400);
        }
        session()->flash('error', 'Somthing Went wrong');
        // return response()->json(['error' => 'Somthing Went wrong'], 400);
        // $user = Auth::User();

        return redirect('/users');
    }

    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $otp = rand(100000, 999999);
            session(['otp' => $otp]);
            session(['otp_expires_at' => now()->addMinutes(5)]);
            Mail::to($user->email)->send(new SendVerificationOtpMail($otp));

            return response()->json(['message' => 'OTP resent successfully!'], 200);
        }

        return response()->json(['error' => 'User not found!'], 404);
    }

    public function edit($id)
    {
        $user = User::find($id);
        return view('edit_form', compact('user'));
    }

    public function delete($id)
    {
        $row = User::find($id);

        if ($row) {
            $userEmail = $row->email;
            $row->delete();
            return response()->json(['message' => 'User with email ' . $userEmail . ' deleted']);
        } else {
            return response()->json(['message' => 'Row not found!'], 404);
        }
    }
}
