<?php

namespace App\Observers;

use App\Mail\DeletedUser;
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
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        //
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
