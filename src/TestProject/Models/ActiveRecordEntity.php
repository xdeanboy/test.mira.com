<?php

namespace TestProject\Models;

use TestProject\Services\Db;

abstract class ActiveRecordEntity
{
    protected $id;

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    abstract protected static function getTableName(): string;

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function __set($name, $value)
    {
        $nameToCamelCase = $this->underscoreToCamelCase($name);
        $this->$nameToCamelCase = $value;
    }

    /**
     * method for work with DB columns camelCase => camel_case
     * @param string $source
     * @return string
     */
    private function camelCaseToUnderscore(string $source): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $source));
    }

    /**
     * method for work with DB columns camel_case => camelCase
     * @param string $source
     * @return string
     */
    private function underscoreToCamelCase(string $source): string
    {
        return lcfirst(str_replace('_', '', ucwords($source, '_')));
    }

    /**
     * @return array
     */
    private function mapPropertiesToDb(): array
    {
        $reflector = new \ReflectionObject($this);
        $properties = $reflector->getProperties();

        $mappedProperties = [];
        foreach ($properties as $property) {
            $propertyName = $property->getName();
            $propertyToUnderscore = $this->camelCaseToUnderscore($propertyName);
            $mappedProperties[$propertyToUnderscore] = $this->$propertyName;
        }

        return $mappedProperties;
    }

    /**
     * save result to DB
     * @return void
     */
    public function save(): void
    {
        $mappedProperties = $this->mapPropertiesToDb();

        if ($this->id !== null) {
            $this->update($mappedProperties);
        } else {
            $this->insert($mappedProperties);
        }
    }

    /**
     * @param array $mappedProperties
     * @return void
     */
    private function update(array $mappedProperties): void
    {
        $columns2params = [];
        $params2value = [];
        $index = 1;

        foreach ($mappedProperties as $propertyName => $value) {
            $params = ':param' . $index++;
            $params2value[$params] = $value;
            $columns2params[] = $propertyName . ' = ' . $params;
        }

        $valueForSet = implode(', ', $columns2params);
        $sql = 'UPDATE ' . static::getTableName() . ' SET ' . $valueForSet . ' WHERE id=' . $this->id;

        $db = Db::getInstance();
        $db->query($sql, $params2value, static::class);

    }

    /**
     * @param array $mappedProperties
     * @return void
     */
    private function insert(array $mappedProperties): void
    {
        $filerProperties = array_filter($mappedProperties);
        $columns = [];
        $params = [];
        $params2values = [];
        $index = 1;


        foreach ($filerProperties as $columnName => $value) {
            $param = ':param' . $index++;
            $columns[] = $columnName;
            $params2values[$param] = $value;
            $params[] = $param;
        }

        $columnsViaSemicolon = implode(', ', $columns);
        $paramsViaSemicolon = implode(', ', $params);

        $db = Db::getInstance();
        $sql = 'INSERT INTO ' . static::getTableName() . ' ( ' . $columnsViaSemicolon . ' ) VALUES ( ' . $paramsViaSemicolon . ' );';

        $db->query($sql, $params2values, static::class);
        $this->id = $db->getLastInsertId();
    }

    /**
     * @return array|null
     */
    public static function findAll(): ?array
    {
        $db = Db::getInstance();

        $result = $db->query('SELECT * FROM `' . static::getTableName() . '`;',
            [],
            static::class);

        if ($result === []) {
            return null;
        }

        return $result;
    }

    /**
     * @param int $id
     * @return static|null
     */
    public static function getById(int $id): ?self
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` WHERE id = :id;';
        $result = $db->query($sql, [':id' => $id], static::class);

        return !empty($result) ? $result[0] : null;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return static|null
     */
    public static function getByColumnName(string $columnName, $value): ?self
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` WHERE ' . $columnName . ' = :column_name LIMIT 1;';
        $result = $db->query($sql, [':column_name' => $value], static::class);

        return !empty($result) ? $result[0] : null;
    }

    /**
     * @param string $columnName
     * @param $value
     * @return array|null
     */
    public static function findAllByColumn(string $columnName, $value): ?array
    {
        $db = Db::getInstance();
        $sql = 'SELECT * FROM `' . static::getTableName() . '` WHERE ' . $columnName . ' = :column_name ;';
        $result = $db->query($sql, [':column_name' => $value], static::class);

        return !empty($result) ? $result : null;
    }
}