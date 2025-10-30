<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use SebastianBergmann\Diff\Diff;

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
        return DataTables::of(User::query())->make(true);
    }
    public function edit($id)
    {
        $row = User::find($id);
        return view('edit_form', compact('row'));
    }

    public function delete($id)
    {
        $row = User::find($id);

        if ($row) {
            $row->delete();
            return response()->json(['message' => 'Row deleted successfully!']);
        } else {
            return response()->json(['message' => 'Row not found!'], 404);
        }
    }
}
