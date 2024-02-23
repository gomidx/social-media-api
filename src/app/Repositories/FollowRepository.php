<?php

namespace App\Repositories;

use App\Interfaces\FollowRepositoryInterface;
use App\Models\Follow;
use Illuminate\Pagination\Paginator;

class FollowRepository implements FollowRepositoryInterface
{
    public function createFollow(array $followDetails): Follow
    {
        return Follow::create($followDetails);
    }

    public function getFollow(int $followerId, int $followedId): ?Follow
    {
        return Follow::where('follower_user_id', $followerId)->where('followed_user_id', $followedId)->first();
    }

    public function getFollowers(int $userId): Paginator
    {
        return Follow::where('followed_user_id', $userId)->where('follower_user_id', '!=', $userId)->simplePaginate(30);
    }

    public function getFollowed(int $userId): Paginator
    {
        return Follow::where('follower_user_id', $userId)->where('followed_user_id', '!=', $userId)->simplePaginate(30);
    }

    public function removeFollower(int $userId): void
    {
        Follow::where('follower_user_id', $userId)->where('followed_user_id', auth()->user()->id)->delete();
    }

    public function stopFollowing(int $userId): void
    {
        Follow::where('follower_user_id', auth()->user()->id)->where('followed_user_id', $userId)->delete();
    }
}