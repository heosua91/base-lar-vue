<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;

class GatePolicy
{
    use HandlesAuthorization;
    /**
     * Create a new policy instance.
     *
     * @return void
     */
    protected $action;

    public function __construct()
    {
        //
    }

    public function before($user, $ability)
    {
        $this->action = $ability;
    }

    /**
     * @param $instructor
     * @return bool
     */
    public function checkRole($user)
    {
        $roles = collect(config('role'));
        $action = $roles->where('id', $this->action)->first();
        if ($action && (in_array($user->role, $action['read']) || in_array($user->role, $action['write']))) {
            return true;
        }

        return false;
    }
}
