<?php

namespace App\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use App\Entities\UserEntity;

use App\Models\Users;

class UserRepository implements UserRepositoryInterface
{

    /**
     * Find a user by username.
     *
     * @param string $username
     * @return array|null Returns user data array or null if not found
     */
    public function findByUsername(string $username): ?array
    {
         $user = Users::where('username',$username)->first();
        return $user ? $user->toArray() : null;

    }

    /**
     * Insert a new user.
     *
     * @param array $data ['username' => string, 'passwordHash' => string]
     * @return array The inserted user data including userId
     */
    public function insert(array $data): array
    {
        $user = Users::create($data);
        return $user ? $user->toArray() : null;
    }

    /**
     * OAuth2 UserRepositoryInterface implementation
     * Get a user entity by username and password for OAuth2 password grant
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ): ?UserEntityInterface {
        $user = $this->findByUsername($username);
        
        if (!$user || !password_verify($password, $user['passwordHash'])) {
            return null;
        }

        $userEntity = new UserEntity();
        $userEntity->setIdentifier($user['userId']);

        return $userEntity;
    }
}
