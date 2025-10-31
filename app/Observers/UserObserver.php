<?php

namespace App\Observers;

use App\Mail\DeletedUser;
use App\Mail\SendVerificationOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Contracts\Events\ShouldHandleEventsAfterCommit;

// class UserObserver implements ShouldHandleEventsAfterCommit
class UserObserver
{

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $otp = rand(100000, 999999);
        session(['otp' => $otp]);
        session(['otp_expires_at' => now()->addMinutes(1)]);
        Mail::to($user->email)->send(new SendVerificationOtpMail($otp));
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
        if ($user->email_verified_at !== null) {
            Mail::to($user->email)->send(new DeletedUser());
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
        Mail::to($user->email)->send(new DeletedUser());
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
