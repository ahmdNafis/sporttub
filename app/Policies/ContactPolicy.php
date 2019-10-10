<?php

namespace App\Policies;

use App\User;
use App\Policies\CheckControllerPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function view(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Contact', 'Read');
    }

    /**
     * Determine whether the user can create privacies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Contact', 'Write');
    }

    /**
     * Determine whether the user can update the privacy.
     *
     * @param  \App\User  $user
     * @param  \App\Privacy  $privacy
     * @return mixed
     */
    public function update(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Contact', 'Update');
    }

    /**
     * Determine whether the user can delete the privacy.
     *
     * @param  \App\User  $user
     * @param  \App\Privacy  $privacy
     * @return mixed
     */
    public function delete(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Contact', 'Delete');
    }

}
