<?php

namespace App\Policies;

use App\Models\Follow;
use App\Models\User;
use App\Models\UserPost;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPostPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserPost  $userPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $authUser, UserPost $userPost)
    {
        $owner = $userPost->user;

        // If the owner's profile is public, anyone can view the post
        if (!$owner->is_private) {
            return true;
        }

        // If the authenticated user is the owner of the post
        if ($authUser->id === $owner->id) {
            return true;
        }

        // Check if the authenticated user follows the owner with accepted status
        return Follow::where('follower_id', $authUser->id)
            ->where('following_id', $owner->id)
            ->where('status', 'accepted')
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    // public function create(User $user)
    // {
    //     //
    // }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserPost  $userPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, UserPost $userPost)
    {
        return $user->id === $userPost->user_id;    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserPost  $userPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, UserPost $userPost)
    {
        return $user->id === $userPost->user_id;
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserPost  $userPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, UserPost $userPost)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UserPost  $userPost
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, UserPost $userPost)
    {
        //
    }
}
