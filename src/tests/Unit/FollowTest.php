<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Follow;
use App\Services\FollowService;
use App\Repositories\FollowRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Response;
use Laravel\Sanctum\Sanctum;

class FollowTest extends TestCase
{
    use RefreshDatabase;

    protected FollowService $followService;
    protected FollowRepository $followRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->followService = new FollowService;
    }

    public function test_creates_follow(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $userToFollow = User::factory()->create();

        $followDetails = [
            'user_id' => $userToFollow->id
        ];

        $result = $this->followService->createFollow($followDetails);

        $this->assertTrue($result['code'] === Response::HTTP_CREATED);
        $this->assertEquals('User successfuly followed.', $result['response']['data']);
    }

    public function test_gets_followers(): void
    {
        $follower = User::factory()->create();
        $user = User::factory()->create();

        Follow::factory()->create([
            'follower_user_id' => $follower->id,
            'followed_user_id' => $user->id
        ]);

        $result = $this->followService->getFollowers($user->id);

        $this->assertTrue($result['code'] === Response::HTTP_OK);
        $this->assertCount(1, $result['response']['data']);
    }

    public function test_gets_followed(): void
    {
        $follower = User::factory()->create();
        $user = User::factory()->create();

        Follow::factory()->create([
            'follower_user_id' => $follower->id,
            'followed_user_id' => $user->id
        ]);

        $result = $this->followService->getFollowed($follower->id);

        $this->assertTrue($result['code'] === Response::HTTP_OK);
        $this->assertCount(1, $result['response']['data']);
    }

    public function test_removes_follower(): void
    {
        $follower = User::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        Follow::factory()->create([
            'follower_user_id' => $follower->id,
            'followed_user_id' => $user->id
        ]);

        $result = $this->followService->removeFollower($follower->id);

        $this->assertTrue($result['code'] === Response::HTTP_OK);
        $this->assertEquals('The informed user is not your follower anymore.', $result['response']['data']);
    }

    public function test_stops_following(): void
    {
        $follower = User::factory()->create();
        $user = User::factory()->create();

        Sanctum::actingAs($follower);

        Follow::factory()->create([
            'follower_user_id' => $follower->id,
            'followed_user_id' => $user->id
        ]);

        $result = $this->followService->stopFollowing($user->id);

        $this->assertTrue($result['code'] === Response::HTTP_OK);
        $this->assertEquals('The informed user is not followed by you anymore.', $result['response']['data']);
    }

    public function test_returns_error_if_user_not_found_when_creating_follow(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $followDetails = [
            'user_id' => 999
        ];

        $result = $this->followService->createFollow($followDetails);

        $this->assertTrue($result['code'] === Response::HTTP_NOT_FOUND);
        $this->assertEquals("User doesn't exists.", $result['response']['data']);
    }

    public function test_returns_error_if_user_already_follow_when_creating_follow(): void
    {
        $user = User::factory()->create();
        $followed = User::factory()->create();

        Sanctum::actingAs($user);

        Follow::factory()->create([
            'follower_user_id' => $user->id,
            'followed_user_id' => $followed->id
        ]);

        $followDetails = [
            'user_id' => $followed->id
        ];

        $result = $this->followService->createFollow($followDetails);

        $this->assertTrue($result['code'] === Response::HTTP_FORBIDDEN);
        $this->assertEquals('You already follow this user.', $result['response']['data']);
    }

    public function test_returns_error_if_user_not_found_when_getting_followers(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $result = $this->followService->getFollowers(999);

        $this->assertTrue($result['code'] === Response::HTTP_NOT_FOUND);
        $this->assertEquals("User doesn't exists.", $result['response']['data']);
    }

    public function test_returns_error_if_user_not_found_when_getting_followed(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $result = $this->followService->getFollowed(999);

        $this->assertTrue($result['code'] === Response::HTTP_NOT_FOUND);
        $this->assertEquals("User doesn't exists.", $result['response']['data']);
    }

    public function test_returns_error_if_user_not_found_when_removing_follower(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $result = $this->followService->removeFollower(999);

        $this->assertTrue($result['code'] === Response::HTTP_NOT_FOUND);
        $this->assertEquals("User doesn't exists.", $result['response']['data']);
    }

    public function test_returns_error_if_user_is_not_follower_when_removing_follower(): void
    {
        $user = User::factory()->create();
        $secondUser = User::factory()->create();

        Sanctum::actingAs($user);

        $result = $this->followService->removeFollower($secondUser->id);

        $this->assertTrue($result['code'] === Response::HTTP_BAD_REQUEST);
        $this->assertEquals("This user doesn't follow you.", $result['response']['data']);
    }

    public function test_returns_error_if_user_not_found_when_stopping_following(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $result = $this->followService->stopFollowing(999);

        $this->assertTrue($result['code'] === Response::HTTP_NOT_FOUND);
        $this->assertEquals("User doesn't exists.", $result['response']['data']);
    }

    public function test_returns_error_if_user_is_not_following_when_stopping_following(): void
    {
        $user = User::factory()->create();
        $secondUser = User::factory()->create();

        Sanctum::actingAs($user);

        $result = $this->followService->stopFollowing($secondUser->id);

        $this->assertTrue($result['code'] === Response::HTTP_BAD_REQUEST);
        $this->assertEquals("You don't follow this user.", $result['response']['data']);
    }
}
