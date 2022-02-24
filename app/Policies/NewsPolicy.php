<?php

namespace App\Policies;

use App\Models\News;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function update(User $user, News $news)
    {
        return $news->users->pluck('id')->contains($user->id);
    }

    public function destroy(User $user, News $news)
    {
        return $news->users->pluck('id')->contains($user->id);
    }
}
