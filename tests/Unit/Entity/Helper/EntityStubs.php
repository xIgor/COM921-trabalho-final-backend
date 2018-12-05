<?php

namespace IntecPhp\Test\Unit\Entity\Helper;

use IntecPhp\Service\DbHandler;

trait EntityStubs
{
    private function getPDOStatementStub(string $method, $return)
    {
        $stmtStub = $this->createMock(\PDOStatement::class);
        $stmtStub
            ->expects($this->once())
            ->method($method)
            ->willReturn($return);

        return $stmtStub;
    }

    private function getDbHandlerStub($return)
    {
        $dbStub = $this->createMock(DbHandler::class);
        $dbStub
            ->expects($this->once())
            ->method('query')
            ->willReturn($return);

        return $dbStub;
    }
}
