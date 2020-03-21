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

class Vfn extends TestCase
{

    /**
     * @test
     */
    public function parse(): void
    {
        $vfn = new \app\Sites\Vfn();
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], file_get_contents(__DIR__ . '/../staticContent/vfn.html') ?: ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new HttpClient(['handler' => $handlerStack]);
        $content = new LoadContentHttp($client);
        $results = $vfn->parse($content, new DevNull());

        $expected = [
            [
                'type' => 'A-',
                'status' => 'warning',
            ],
            [
                'type' => 'A+',
                'status' => 'urgent',
            ],
            [
                'type' => 'B-',
                'status' => 'warning',
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
                'status' => 'warning',
            ],
            [
                'type' => 'AB-',
                'status' => 'warning',
            ],
            [
                'type' => 'AB+',
                'status' => 'full',
            ]
        ];

        self::assertEquals($expected, $results);
    }
}
