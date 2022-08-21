<?php

namespace TestProject\Services;

use TestProject\Exceptions\DbException;

class Db
{
    private static $instance;
    private $pdo;

    private function __construct()
    {
        $settings = (require __DIR__ . '/../../settings.php')['db'];
        try {
            $this->pdo = new \PDO('mysql:host=' . $settings['host'] . ';dbname=' . $settings['dbname'],
                $settings['users'],
                $settings['password']);

            $this->pdo->exec('SET NAMES UTF8');
        } catch (\PDOException $e) {
            throw new DbException('Помилка при підключенні до БД: ' . $e->getMessage());
        }
    }

    /**
     * Method for require to DB
     * @param string $sql
     * @param $params
     * @param string $className
     * @return array|null
     */
    public function query(string $sql, $params = [], string $className = 'stdClass'): ?array
    {
        $sth = $this->pdo->prepare($sql);
        $result = $sth->execute($params);

        if ($result === false) {
            return null;
        }

        return $sth->fetchAll(\PDO::FETCH_CLASS, $className);
    }

    /**
     * @return static
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    /**
     * @return int
     */
    public function getLastInsertId(): int
    {
        return $this->pdo->lastInsertId();
    }
}