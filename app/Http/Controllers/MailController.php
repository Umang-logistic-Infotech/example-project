<?php

namespace App\Http\Controllers;

use App\Mail\DeletedUser;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    //
    public function deletedUserMail()
    {
        // $row = User::find($id);

        // if ($row) {
        //     $row->delete();
        //     return response()->json(['message' => 'Row deleted successfully!']);
        // } else {
        //     return response()->json(['message' => 'Row not found!'], 404);
        // }
        Mail::to('jadavumang160@gmail.com')->send(new DeletedUser());
        return "email send success";
    }
}
