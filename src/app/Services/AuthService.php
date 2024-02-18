<?php

namespace App\Services;

use App\Helpers\Http;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class AuthService
{
	use Http;

	private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

	public function generateToken(array $userDetails): array
	{
		$user = $this->userRepository->getUserByEmail($userDetails['email']);

		if (! empty($user->id)) {
			$checkPass = Hash::check($userDetails['password'], $user->password);

			if (! $checkPass) {
				return $this->unprocessableEntity('Invalid password.');
			}
		} else {
			return $this->notFound("User doesn't exists.");
		}

		$user->tokens()->delete();

		$token = $user->createToken($userDetails['email'])->plainTextToken;

		return $this->ok($token);
	}

	public function logout(): array
	{
		$user = $this->userRepository->getUserById(auth()->user()->id);

		$user->tokens()->delete();

		return $this->ok('Successfully disconnected.');
	}
}