<?php

namespace App\Policies;

use App\User;
use App\Policies\CheckControllerPermission;
use Illuminate\Auth\Access\HandlesAuthorization;

class TagPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the type.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function view(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Tag', 'Read');
    }

    /**
     * Determine whether the user can create types.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Tag', 'Write');
    }

    /**
     * Determine whether the user can update the type.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function update(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Tag', 'Update');
    }

    /**
     * Determine whether the user can delete the type.
     *
     * @param  \App\User  $user
     * @param  \App\Type  $type
     * @return mixed
     */
    public function delete(User $user)
    {
        return (new CheckControllerPermission($user))->authenticate('Tag', 'Delete');
    }
}
