<?php

namespace TestProject\Models\Users;

use TestProject\Exceptions\InvalidArgumentException;
use TestProject\Models\ActiveRecordEntity;

class User extends ActiveRecordEntity
{
    protected $email;
    protected $position;
    protected $passwordHash;
    protected $authToken;
    protected $isConfirmed;
    protected $createdAt;

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $position
     */
    public function setPosition(string $position): void
    {
        $this->position = $position;
    }

    /**
     * @return string
     */
    public function getPosition(): string
    {
        return $this->position;
    }

    /**
     * @return bool
     */
    public function isBoss(): bool
    {
        return $this->getPosition() === 'Директор';
    }

    /**
     * @return bool
     */
    public function isManager(): bool
    {
        return $this->getPosition() === 'Менеджер';
    }

    /**
     * @return bool
     */
    public function isPerformer(): bool
    {
        return $this->getPosition() === 'Виконавець';
    }

    /**
     * @param string $passwordHash
     */
    public function setPasswordHash(string $passwordHash): void
    {
        $this->passwordHash = $passwordHash;
    }

    /**
     * @return string
     */
    public function getPasswordHash(): string
    {
        return $this->passwordHash;
    }

    /**
     * @param string $authToken
     */
    public function setAuthToken(string $authToken): void
    {
        $this->authToken = $authToken;
    }

    /**
     * @return string
     */
    public function getAuthToken(): string
    {
        return $this->authToken;
    }

    /**
     * @param bool $isConfirmed
     */
    public function setIsConfirmed(bool $isConfirmed): void
    {
        $this->isConfirmed = $isConfirmed;
    }

    /**
     * @return bool
     */
    public function getIsConfirmed(): bool
    {
        return $this->isConfirmed;
    }

    /**
     * @return bool
     */
    public function isConfirmed(): bool
    {
        return $this->getIsConfirmed() === true;
    }

    /**
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'users';
    }

    /**
     * @param string $email
     * @return static|null
     */
    public static function getByEmail(string $email): ?self
    {
        $checkUser = self::getByColumnName('email', $email);

        return !empty($checkUser) ? $checkUser : null;
    }

    /**
     * @param array $userData
     * @return static
     * @throws InvalidArgumentException
     */
    public static function create(array $userData): self
    {
        if(empty(trim($userData['email']))) {
            throw new InvalidArgumentException('Заповніть поле Email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Некоректний Email');
        }

        preg_match('~^(.*)@(.*)\.(.*)$~', $userData['email'], $matches);

        if (mb_strlen($matches[1]) < 3 || mb_strlen($matches[2]) != 4) {
            throw new InvalidArgumentException('Email повинен мати мінімум 3 символи до символу @ та тільки 4 символи після');
        }

        if (self::getByEmail($userData['email']) !== null) {
            throw new InvalidArgumentException('Користувач з таким email вже існує');
        }

        if(empty(trim($userData['position']))) {
            throw new InvalidArgumentException('Виберіть вашу посаду');
        }

        $position = false;

        if (preg_match('~^Директор$~', $userData['position'])) {
            $position = true;
        }

        if (preg_match('~^Менеджер$~', $userData['position'])) {
            $position = true;
        }

        if (preg_match('~^Виконавець$~', $userData['position'])) {
            $position = true;
        }

        if (!$position) {
            throw new InvalidArgumentException('Невідома посада');
        }

        if(empty(trim($userData['password']))) {
            throw new InvalidArgumentException('Заповніть поле Пароль');
        }

        if (!preg_match('~^(.*)!(.*)$~', $userData['password'])) {
            throw new InvalidArgumentException('Пароль повинен мати знак !');
        }

        if (mb_strlen($userData['password']) < 6) {
            throw new InvalidArgumentException('Пароль повинен мати мінімум 6 символів');
        }

        $user = new User();

        $user->setEmail($userData['email']);
        $user->setPosition($userData['position']);
        $user->setPasswordHash(password_hash($userData['password'], PASSWORD_DEFAULT));
        $user->setAuthToken(sha1(random_bytes(100)) . sha1(random_bytes(100)));
        $user->setIsConfirmed(false);
        $user->save();

        return $user;
    }

    /**
     * @return void
     * @throws \Exception
     */
    public function refreshAuthToken(): void
    {
        $this->setAuthToken(sha1(random_bytes(100)) . sha1(random_bytes(100)));
        $this->save();
    }

    /**
     * @return void
     */
    public function activate(): void
    {
        $this->setIsConfirmed(true);
        $this->save();
    }

    /**
     * @param array $userData
     * @return $this
     * @throws InvalidArgumentException
     */
    public static function signIn(array $userData): self
    {
        if (empty(trim($userData['email']))) {
            throw new InvalidArgumentException('Введіть ваш email');
        }

        if (!filter_var($userData['email'], FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Некоректний Email');
        }

        preg_match('~^(.*)@(.*)\.(.*)$~', $userData['email'], $matches);

        if (mb_strlen($matches[1]) < 3 || mb_strlen($matches[2]) != 4) {
            throw new InvalidArgumentException('Неправильний email');
        }

        if (empty($userData['password'])) {
            throw new InvalidArgumentException('Введіть ваш пароль');
        }

        if (!preg_match('~^(.*)!(.*)$~', $userData['password'])) {
            throw new InvalidArgumentException('Не правильний пароль');
        }

        if (mb_strlen($userData['password']) < 6) {
            throw new InvalidArgumentException('Не правильний пароль');
        }

        $checkUser = self::getByEmail($userData['email']);

        if ($checkUser === null) {
            throw new InvalidArgumentException('Користувача з таким email не існує');
        }

        if (!password_verify($userData['password'], $checkUser->getPasswordHash())) {
            throw new InvalidArgumentException('Не правильний email або пароль');
        }

        $checkUser->refreshAuthToken();

        return $checkUser;
    }
}