<?php
declare(strict_types=1);

namespace tests\Sites;

use app\ContentLoaders\LoadContentHttp;
use app\HttpClient;
use app\Storage\DevNull;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class UstavHematologieAKrevniTransfuze extends TestCase
{
    /**
     * @test
     */
    public function parse(): void
    {
        $uhkt = new \app\Sites\UstavHematologieAKrevniTransfuze();
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], file_get_contents(__DIR__ . '/../staticContent/uhkt.html') ?: ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handlerStack]);
        $content = new LoadContentHttp($client);
        $results = $uhkt->parse($content, new DevNull());

        $expected = [
                [
                    'type' => 'A-',
                    'status' => 'urgent',
                ],
                [
                    'type' => 'A+',
                    'status' => 'urgent',
                ],
                [
                    'type' => 'B-',
                    'status' => 'urgent',
                ],
                [
                    'type' => 'B+',
                    'status' => 'warning',
                ],
                [
                    'type' => '0-',
                    'status' => 'urgent',
                ],
                [
                    'type' => '0+',
                    'status' => 'urgent',
                ],
                [
                    'type' => 'AB-',
                    'status' => 'warning',
                ],
                [
                    'type' => 'AB+',
                    'status' => 'warning',
                ],
        ];

        self::assertEquals($expected, $results);
    }
}
