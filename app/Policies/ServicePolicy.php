<?php

namespace App\Policies;

use App\User;
use App\Policies\CheckControllerPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class ServicePolicy
{
    use HandlesAuthorization;
    

    /**
     * Determine whether the user can view the service terms.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTerms  $serviceTerms
     * @return mixed
     */
    public function view(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('ServiceTerms', 'Read');
    }

    /**
     * Determine whether the user can create service terms.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('ServiceTerms', 'Write');
    }

    /**
     * Determine whether the user can update the service terms.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTerms  $serviceTerms
     * @return mixed
     */
    public function update(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('ServiceTerms', 'Update');
    }

    /**
     * Determine whether the user can delete the service terms.
     *
     * @param  \App\User  $user
     * @param  \App\ServiceTerms  $serviceTerms
     * @return mixed
     */
    public function delete(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('ServiceTerms', 'Delete');
    }
}
