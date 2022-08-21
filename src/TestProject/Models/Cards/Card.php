<?php

namespace TestProject\Models\Cards;

use TestProject\Exceptions\InvalidArgumentException;
use TestProject\Exceptions\UnauthorizedException;
use TestProject\Models\ActiveRecordEntity;
use TestProject\Models\Users\User;

class Card extends ActiveRecordEntity
{
    protected $title;
    protected $body;
    protected $user;
    protected $button;

    /**
     * @param string $title
     */
    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $body
     */
    public function setBody(string $body): void
    {
        $this->body = $body;
    }

    /**
     * @return string
     */
    public function getBody(): string
    {
        return $this->body;
    }

    /**
     * @param string $user
     */
    public function setUser(string $user): void
    {
        $this->user = $user;
    }

    /**
     * @return string
     */
    public function getUser(): string
    {
        return $this->user;
    }

    /**
     * @param string $button
     */
    public function setButton(string $button): void
    {
        $this->button = $button;
    }

    /**
     * @return string
     */
    public function getButton(): string
    {
        return $this->button;
    }

    /**
     * @return string
     */
    protected static function getTableName(): string
    {
        return 'cards';
    }

    /**
     * @param array $dataCard
     * @param User $user
     * @return void
     * @throws InvalidArgumentException
     * @throws UnauthorizedException
     */
    public static function newCard(array $dataCard, User $user): void
    {
        if (empty($user)) {
            throw new UnauthorizedException();
        }

        if (empty($dataCard)) {
            throw new InvalidArgumentException('Невідомі дані для карт');
        }

        if (empty($dataCard['title'])) {
            throw new InvalidArgumentException('Невідомо титулка для карт');
        }

        if (empty($dataCard['body'])) {
            throw new InvalidArgumentException('Невідомо титулка для карт');
        }

        if (empty($dataCard['button'])) {
            throw new InvalidArgumentException('Невідома кнопка для карт');
        }

        $card = new Card();
        $card->setTitle($dataCard['title']);
        $card->setBody($dataCard['body']);

        if ($user->isBoss()) {
            $card->setUser('Директор');
        }

        if ($user->isManager()) {
            $card->setUser('Менеджер');
        }

        if ($user->isPerformer()) {
            $card->setUser('Виконавець');
        }

        $card->setButton($dataCard['button']);
        $card->save();
    }

    /**
     * @return array|null
     */
    public static function findAllByBoss(): ?array
    {
        $result = self::findAllByColumn('button', 'Кнопка boss');

        return $result;
    }

    /**
     * @return array|null
     */
    public static function findAllByManager(): ?array
    {
        $result = self::findAllByColumn('button', 'Кнопка manager');

        return $result;
    }

    /**
     * @return array|null
     */
    public static function findAllByPerformer(): ?array
    {
        $result = self::findAllByColumn('button', 'Кнопка performer');

        return $result;
    }
}