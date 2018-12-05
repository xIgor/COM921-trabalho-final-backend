<?php

namespace IntecPhp\Entity;

use Exception;
use IntecPhp\Handler\NotFound;
use IntecPhp\Service\DbHandler;

abstract class Entity
{
    protected $name;
    protected $id;
    protected $db;

    final public function __construct(DbHandler $db)
    {
        $this->db = $db;
    }

    public function findAll()
    {
        $stmt = $this->db->query("select * from $this->name");
        if ($stmt) {
            return $stmt->fetchAll();
        }

        throw new Exception("Não foi possível consultar $this->name");
    }

    public function findBy(array $andWhere)
    {
        $stmt = $this->findStmt($andWhere);
        if ($stmt) {
            return $stmt->fetchAll();
        }
        throw new Exception("Não foi possível consultar $this->name");
    }

    public function findOneBy(array $andWhere)
    {
        $stmt = $this->findStmt($andWhere);
        if ($stmt) {
            return $stmt->fetch();
        }
        throw new Exception("Não foi possível consultar $this->name");
    }

    protected function findStmt(array $andWhere)
    {
        $sanitizedFields = $this->sanitizeColumns(array_keys($andWhere));
        $paramNames = $this->parseNamedParams($sanitizedFields);

        $whereString = implode(' and ', array_map(function($name, $value) {
            return sprintf('%s=%s', $name, $value);
        }, $sanitizedFields, $paramNames));

        $params = array_combine($paramNames, array_values($andWhere));

        return $this->db->query("select * from $this->name where $whereString", $params);
    }

    protected function sanitize(string $value)
    {
        return filter_var($value, FILTER_SANITIZE_STRING);
    }

    protected function sanitizeColumns(array $columns)
    {
        return array_map(\Closure::bind(function($col) {
            return $this->sanitize($col);
        }, $this), $columns);
    }

    protected function parseNamedParams(array $columns, string $suffix = '')
    {
        return array_map(function($col) use($suffix) {
            return sprintf(':%s%s', $col, $suffix);
        }, $columns);
    }

    protected function buildSet(array $sanitizedColumns, array $parsedNamedParamColumns)
    {
        return implode(', ', array_map(function($name, $value) {
            return sprintf('%s=%s', $name, $value);
        }, $sanitizedColumns, $parsedNamedParamColumns));
    }

    public function save(array $columns)
    {
        $sanitizedFields = $this->sanitizeColumns(array_keys($columns));
        $paramNames = $this->parseNamedParams($sanitizedFields);

        $numericValues = array_values($columns);

        $params = array_combine($paramNames, $numericValues);

        $paramNamesString = implode(', ', $paramNames);
        $sanitizedFieldsString = implode(', ', $sanitizedFields);

        $stmt = $this->db->query("insert into $this->name($sanitizedFieldsString) values($paramNamesString)", $params);

        if($stmt) {
            return $this->db->lastInsertId();
        }

        throw new \Exception("Não foi possível salvar $this->name");
    }

    public function update(array $columns, array $andWhere)
    {
        $sanitizedColumns = $this->sanitizeColumns(array_keys($columns));
        $sanitizedWhereColumns = $this->sanitizeColumns(array_keys($andWhere));

        $parsedNamedParamColumns = $this->parseNamedParams($sanitizedColumns);
        $parsedNamedParamWhereColumns = $this->parseNamedParams($sanitizedWhereColumns, '_w');

        $columnValues = array_values($columns);
        $whereColumnValues = array_values($andWhere);

        $setString = $this->buildSet($sanitizedColumns, $parsedNamedParamColumns);

        $whereString = implode(' and ', array_map(function($name, $value) {
            return sprintf('%s=%s', $name, $value);
        }, $sanitizedWhereColumns, $parsedNamedParamWhereColumns));

        $params = array_merge(
            array_combine($parsedNamedParamColumns, $columnValues),
            array_combine($parsedNamedParamWhereColumns, $whereColumnValues)
        );

        $stmt = $this->db->query("update $this->name set $setString where $whereString", $params);

        if($stmt) {
            return $stmt->rowCount();
        }

        throw new Exception("Não foi possível atualizar $this->name");
    }
}
