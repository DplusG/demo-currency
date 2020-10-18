<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Components\Currency\CurrencyRequest;

/**
 * Class ExampleTest
 * @package Tests\Unit
 *
 * vendor/bin/phpunit --filter Unit
 */
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }

    public function testGetCurrencyByDate()
    {
        $req = new CurrencyRequest();

        $req->setDate('03/10/2020');
        $req->execute();

        $xml = simplexml_load_string($req->getResponse());

        $this->assertInstanceOf('\SimpleXMLElement', $xml, 'Response is not SimpleXMLElement decoded in string!');
    }
}
