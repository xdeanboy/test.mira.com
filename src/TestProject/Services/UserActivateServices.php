<?php

namespace TestProject\Services;

use TestProject\Exceptions\NotFoundException;
use TestProject\Models\Users\User;

class UserActivateServices
{
    private const TABLE_NAME = 'users_activation_code';

    /**
     * @param User $user
     * @return string
     * @throws NotFoundException
     */
    public static function createActivationCode(User $user): string
    {
        if (empty($user)) {
            throw new NotFoundException('Користувача не знайдено');
        }

        $code = md5(random_bytes(50));

        $db = Db::getInstance();
        $sql = 'INSERT INTO ' . self::TABLE_NAME . ' (`user_id`, `code`) VALUES (:user_id, :code);';
        $db->query($sql,
            [':user_id' => $user->getId(),
                'code' => $code],
            static::class);

        return $code;
    }

    /**
     * @param User $user
     * @param string $code
     * @return bool
     */
    public static function checkActivationCode(User $user, string $code): bool
    {
        if (empty($user)) {
            return false;
        }

        if (empty($code)) {
            return false;
        }

        $db = Db::getInstance();
        $sql = 'SELECT * FROM ' . self::TABLE_NAME . ' WHERE `user_id` = :user_id AND `code` = :code;';
        $result = $db->query($sql,
            [':user_id' => $user->getId(),
                ':code' => $code],
            static::class);

        return !empty($result);
    }

    /**
     * @param User $user
     * @param string $code
     * @return void
     * @throws NotFoundException
     */
    public static function deleteActivationCode(User $user, string $code): void
    {
        if (empty($user)) {
            throw new NotFoundException('Користувача не знайдено');
        }

        if (empty($code)) {
            throw new NotFoundException('Код не знайдено');
        }

        $db = Db::getInstance();

        $sql = 'DELETE FROM `' . self::TABLE_NAME . '` WHERE user_id = :user_id AND code = :code;';
        $db->query($sql,
            [':user_id' => $user->getId(),
                ':code' => $code],
            static::class);
    }
}