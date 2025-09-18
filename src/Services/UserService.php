<?php

namespace App\Services;

use App\Repositories\UserRepository;
use App\Models\Users;

class UserService
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Create a new user with hashed password.
     *
     * @param string $username
     * @param string $password Plain text password
     * @return array Created user data
     * @throws \Exception if username already exists
     */
    public function createUser(string $username, string $password): array
    {
        if ($this->userRepository->findByUsername($username)) {
        return [
            'status' => false,
            'message' => 'Username '. $username . ' already exist.',
            'data' => ['username' => $username, 'password' => $password]
        ];

        }

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        return [
            'status' => true,
            'message' => 'SignUp Successfully.',
            'data' => $this->userRepository->insert([
            'username' => $username,
            'passwordHash' => $passwordHash])
            ];
    }

    /**
     * Verify user credentials.
     *
     * @param string $username
     * @param string $password Plain text password
     * @return array|null User data if valid, null otherwise
     */
    public function verifyUser(string $username, string $password): ?array
    {
        $user = $this->userRepository->findByUsername($username);
        if (!$user) {
            return null;
        }

        if (password_verify($password, $user['passwordHash'])) {
            return $user;
        }

        return null;
    }

}
