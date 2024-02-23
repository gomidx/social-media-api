<?php

namespace App\Services;

use App\Helpers\Http;
use App\Repositories\FollowRepository;
use App\Repositories\UserRepository;

class FollowService
{
    use Http;

    private FollowRepository $repository;

    public function __construct()
    {
        $this->repository = new FollowRepository;
    }

    public function createFollow(array $followDetails): array
    {
        if (! $this->userExists($followDetails['user_id'])) {
            return $this->notFound("User doesn't exists.");
        }

        $followDetails = [
            'followed_user_id' => $followDetails['user_id'],
            'follower_user_id' => auth()->user()->id
        ];

        if ($this->followExists($followDetails)) {
            return $this->forbidden('You already follow this user.');
        }

        $this->repository->createFollow($followDetails);

        return $this->created('User successfuly followed.');
    }

    private function userExists(int $userId): bool
    {
        $userRepository = new UserRepository;

        $user = $userRepository->getUserById($userId);

        if (! empty($user->id)) {
            return true;
        }

        return false;
    }

    private function followExists(array $followDetails): bool
    {
        $user = $this->repository->getFollow($followDetails['follower_user_id'], $followDetails['followed_user_id']);

        if (! empty($user->id)) {
            return true;
        }

        return false;
    }

    public function getFollowers(int $userId): array
    {
        if (! $this->userExists($userId)) {
            return $this->notFound("User doesn't exists.");
        }

        $followers = [];

        foreach ($this->repository->getFollowers($userId)->items() as $followModel) {
            $followers[] = $followModel->followerUser;
        }

        return $this->ok($followers);
    }

    public function getFollowed(int $userId): array
    {
        if (! $this->userExists($userId)) {
            return $this->notFound("User doesn't exists.");
        }

        $following = [];

        foreach ($this->repository->getFollowed($userId)->items() as $followModel) {
            $following[] = $followModel->followedUser;
        }

        return $this->ok($following);
    }

    public function removeFollower(int $userId): array
    {
        if (! $this->userExists($userId)) {
            return $this->notFound("User doesn't exists.");
        }

        if (! $this->userIsFollower($userId)) {
            return $this->badRequest("This user doesn't follow you.");
        }

        $this->repository->removeFollower($userId);

        return $this->ok('The informed user is not your follower anymore.');
    }

    private function userIsFollower(int $userId): bool
    {
        $follow = $this->repository->getFollow($userId, auth()->user()->id);

        if (! empty($follow->id)) {
            return true;
        }

        return false;
    }

    public function stopFollowing(int $userId): array
    {
        if (! $this->userExists($userId)) {
            return $this->notFound("User doesn't exists.");
        }

        if (! $this->userIsFollowed($userId)) {
            return $this->badRequest("You don't follow this user."); // criar caso de teste
        }

        $this->repository->stopFollowing($userId);

        return $this->ok('The informed user is not followed by you anymore.');
    }

    private function userIsFollowed(int $userId): bool
    {
        $follow = $this->repository->getFollow(auth()->user()->id, $userId);

        if (! empty($follow->id)) {
            return true;
        }

        return false;
    }
}