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

class Trutnov extends TestCase
{

    /**
     * @test
     */
    public function parse(): void
    {
        $vfn = new \app\Sites\Truntnov();
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], file_get_contents(__DIR__ . '/../staticContent/trutnov.html') ?: ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handlerStack]);
        $content = new LoadContentHttp($client);
        $results = $vfn->parse($content, new DevNull());

        $expected = [
                [
                    'type' => 'A-',
                    'status' => 'full',
                ],
                [
                    'type' => 'A+',
                    'status' => 'urgent',
                ],
                [
                    'type' => 'B-',
                    'status' => 'full',
                ],
                [
                    'type' => 'B+',
                    'status' => 'warning',
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
                    'status' => 'full',
                ],
                [
                    'type' => 'AB+',
                    'status' => 'urgent',
                ],
        ];

        self::assertEquals($expected, $results);
    }
}
