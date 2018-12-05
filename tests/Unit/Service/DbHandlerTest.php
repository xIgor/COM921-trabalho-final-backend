<?php

namespace IntecPhp\Test\Unit\Service;

use PHPUnit\Framework\TestCase;
use IntecPhp\Service\DbHandler;
use Psr\Log\LoggerInterface;


class DbHandlerTest extends TestCase
{
    public function testBeginTransaction()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $pdoStub = $this->createMock(\PDO::class);
        $pdoStub
            ->expects($this->once())
            ->method('beginTransaction')
            ->willReturn(true);

        $db = new DbHandler($pdoStub, $logger);
        $this->assertTrue($db->beginTransaction());
    }

    public function testCommit()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $pdoStub = $this->createMock(\PDO::class);
        $pdoStub
            ->expects($this->once())
            ->method('commit')
            ->willReturn(true);

        $db = new DbHandler($pdoStub, $logger);

        $this->assertTrue($db->commit());
    }

    public function testQuery()
    {
        $params = [10, 'somestring'];
        $sql = 'some sql';
        $logger = $this->createMock(LoggerInterface::class);

        $stmt = $this->createMock(\PDOStatement::class);
        $stmt
            ->expects($this->once())
            ->method('execute')
            ->with($params);

        $pdoStub = $this->createMock(\PDO::class);
        $pdoStub
            ->expects($this->once())
            ->method('prepare')
            ->with($sql)
            ->willReturn($stmt);

        $db = new DbHandler($pdoStub, $logger);

        $this->assertSame($stmt, $db->query($sql, $params));
    }

    public function testQueryError()
    {
        $sql = 'some sql';
        $logger = $this->createMock(LoggerInterface::class);
        $logger
            ->expects($this->once())
            ->method('debug');

        $pdoStub = $this->createMock(\PDO::class);
        $pdoStub
            ->expects($this->once())
            ->method('prepare')
            ->with($sql)
            ->will($this->throwException(new \PDOException('some runtime exception')));

        $pdoStub
            ->expects($this->once())
            ->method('inTransaction')
            ->willReturn(true);

        $pdoStub
            ->expects($this->once())
            ->method('rollBack');

        $db = new DbHandler($pdoStub, $logger);
        $this->assertNull($db->query($sql));
    }

    public function testLastInsertId()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $pdoStub = $this->createMock(\PDO::class);
        $pdoStub
            ->expects($this->once())
            ->method('lastInsertId')
            ->willReturn(10);

        $db = new DbHandler($pdoStub, $logger);

        $this->assertSame(10, $db->lastInsertId());
    }
}
