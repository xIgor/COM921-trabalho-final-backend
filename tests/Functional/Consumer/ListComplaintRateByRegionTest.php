<?php

namespace IntecPhp\Test\Functional\Consumer;

use IntecPhp\Test\Functional\TestCase;

class ListComplaintRateByRegionTest extends TestCase
{

    public function setUp()
    {
        parent::setUp();
    }

    public function testListInfo()
    {
        $resp = $this->runApp('GET', '/region/complaint-rate', null, null);

        $jsonBody = $this->decodeResponse($resp);
        $this->assertEquals(200, $resp->getStatusCode());
        $this->assertEquals(200, $jsonBody['code']);
    }
}
