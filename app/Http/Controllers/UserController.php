<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SebastianBergmann\Diff\Diff;
use Illuminate\Support\Facades\Mail;
use App\Mail\DeletedUser;

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
            Mail::to($userEmail)->send(new DeletedUser());
            $row->delete();
            return response()->json(['message' => 'User with email ' . $userEmail . ' deleted']);
            // return response()->json(['message' => $userEmail]);
        } else {
            return response()->json(['message' => 'Row not found!'], 404);
        }
    }
}
