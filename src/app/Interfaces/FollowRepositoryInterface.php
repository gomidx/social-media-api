<?php

namespace App\Interfaces;

interface FollowRepositoryInterface
{
    public function createFollow(array $followDetails);
    public function getFollow(int $followerId, int $followedId);
    public function getFollowers(int $userId);
    public function getFollowed(int $userId);
    public function removeFollower(int $userId);
    public function stopFollowing(int $userId);
}