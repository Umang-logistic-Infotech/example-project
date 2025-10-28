<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

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
        $users = User::all();
        return view('users', compact('users', 'user'));
    }
    public function getUsers1(Request $request)
    {
        // $users = User::all();
        return DataTables::of(User::query())
            ->setRowClass(function ($user) {
                return $user->id % 2 == 0 ? 'alert-success' : 'alert-warning';
            })            // ->addIndexColumn()
            ->make(true);
        // return $users;
    }
}
