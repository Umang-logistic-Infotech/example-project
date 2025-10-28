<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    //
    public function index()
    {
        return "User Controller";
    }

    public function getUsers()
    {
        $users = User::all();
        return view('users', compact('users'));
    }
    public function getUsers1(Request $request)
    {
        $users = User::all();
        return DataTables::of($users)
            // ->addIndexColumn()
            ->make(true);
        // return $users;
    }
}
