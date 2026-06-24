<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Gender;
use App\Models\Profile;
use App\Repositories\ICredRepository;
use App\Repositories\IProfileRepository;

/**
 * Coordinates the Profile + Cred repositories to register and authenticate users.
 * Deliberately free of superglobals so it stays unit-testable.
 */
class AuthService
{
    public function __construct(
        private IProfileRepository $profiles,
        private ICredRepository $creds,
    ) {}

    /**
     * @throws \RuntimeException if the username or email is already taken,
     *                           or the new profile cannot be reloaded.
     */
    public function register(
        string $firstName,
        string $lastName,
        string $username,
        ?Gender $gender,
        string $email,
        string $password
    ): Profile {
        if ($this->profiles->findByUsername($username) !== null) {
            throw new \RuntimeException('That username is already taken.');
        }
        if ($this->creds->findByEmail($email) !== null) {
            throw new \RuntimeException('That email is already registered.');
        }

        $userId = $this->profiles->create($firstName, $lastName, $username, $gender);
        $this->creds->create($userId, $email, password_hash($password, PASSWORD_DEFAULT), 'member');

        $profile = $this->profiles->findById($userId);
        if ($profile === null) {
            throw new \RuntimeException('Failed to load the newly created profile.');
        }

        return $profile;
    }

    /**
     * Returns the authenticated Profile, or null on bad credentials.
     */
    public function login(string $email, string $password): ?Profile
    {
        $cred = $this->creds->findByEmail($email);
        if ($cred === null) {
            return null;
        }
        if (!password_verify($password, $cred->passHash)) {
            return null;
        }

        $this->creds->updateLastLogin($cred->usrId);
        return $this->profiles->findById($cred->usrId);
    }
}
