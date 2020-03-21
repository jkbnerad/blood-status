<?php
declare(strict_types = 1);

namespace tests\Sites;

use app\ContentLoaders\LoadContentHttp;
use app\HttpClient;
use app\Storage\DevNull;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class Liberec extends TestCase
{

    /**
     * @test
     */
    public function parse(): void
    {
        $liberec = new \app\Sites\Liberec();
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], file_get_contents(__DIR__ . '/../staticContent/liberec.html') ?: ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handlerStack]);
        $content = new LoadContentHttp($client);
        $results = $liberec->parse($content, new DevNull());

        $expected = [
                [
                    'type' => 'A-',
                    'status' => 'warning',
                ],
                [
                    'type' => 'A+',
                    'status' => 'full',
                ],
                [
                    'type' => 'B-',
                    'status' => 'warning',
                ],
                [
                    'type' => 'B+',
                    'status' => 'full',
                ],
                [
                    'type' => '0-',
                    'status' => 'warning',
                ],
                [
                    'type' => '0+',
                    'status' => 'full',
                ],
                [
                    'type' => 'AB-',
                    'status' => 'urgent',
                ],
                [
                    'type' => 'AB+',
                    'status' => 'full',
                ],
        ];

        self::assertEquals($expected, $results);
    }
}
