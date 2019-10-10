<?php

namespace App\Policies;

use App\Privacy;
use App\User;
use App\Policies\CheckControllerPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class PrivacyPolicy
{
    use HandlesAuthorization;
    
    /**
     * Determine whether the user can view any privacies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    /**
     * Determine whether the user can view the privacy.
     *
     * @param  \App\User  $user
     * @param  \App\Privacy  $privacy
     * @return mixed
     */
    public function view(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('PrivacyPolicy', 'Read');
    }

    /**
     * Determine whether the user can create privacies.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('PrivacyPolicy', 'Write');
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
        return (new CheckControllerPermission($user))->authenticate('PrivacyPolicy', 'Update');
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
        return (new CheckControllerPermission($user))->authenticate('PrivacyPolicy', 'Delete');
    }

    /**
     * Determine whether the user can restore the privacy.
     *
     * @param  \App\User  $user
     * @param  \App\Privacy  $privacy
     * @return mixed
     */
    public function restore(User $user, Privacy $privacy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the privacy.
     *
     * @param  \App\User  $user
     * @param  \App\Privacy  $privacy
     * @return mixed
     */
    public function forceDelete(User $user, Privacy $privacy)
    {
        //
    }
}
