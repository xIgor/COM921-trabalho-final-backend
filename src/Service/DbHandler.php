<?php

namespace IntecPhp\Service;

use PDO;
use PDOException;
use Psr\Log\LoggerInterface;

class DbHandler
{
    private $conn;
    private $logger;

    public function __construct(PDO $conn, LoggerInterface $logger)
    {
        $this->conn = $conn;
        $this->logger = $logger;
    }

    public function beginTransaction()
    {
        return $this->conn->beginTransaction();
    }

    public function commit()
    {
        return $this->conn->commit();
    }

    public function query($sql, array $params = [])
    {
        try {
            $sth = $this->conn->prepare($sql);
            $sth->execute($params);
            return $sth;
        } catch (PDOException $e) {
            if ($this->conn->inTransaction()) {
                $this->conn->rollBack();
            }
            $this->logger->debug(sprintf(
                'Sql: %s. Params: %s\nMessage: %s',
                $sql,
                json_encode($params),
                $e->getMessage(),
                $e->getCode()
            ));
        }
    }

    public function lastInsertId()
    {
        return $this->conn->lastInsertId();
    }
}
