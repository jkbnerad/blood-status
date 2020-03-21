<?php
declare(strict_types = 1);

namespace tests\Sites;

use app\ContentLoaders\LoadContentHttp;
use app\Storage\DevNull;
use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

class Klatovy extends TestCase
{

    /**
     * @test
     */
    public function parse(): void
    {
        $klatovy = new \app\Sites\Klatovy();
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], file_get_contents(__DIR__ . '/../staticContent/klatovy.html') ?: ''),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $content = new LoadContentHttp($client);
        $results = $klatovy->parse($content, new DevNull());

        $expected = [
            [
                'status' => 'urgent',
                'type' => '0-',
            ],
            [
                'status' => 'full',
                'type' => '0+',
            ],
            [
                'status' => 'urgent',
                'type' => 'A+',
            ],
            [
                'status' => 'urgent',
                'type' => 'A-',
            ],
            [
                'status' => 'urgent',
                'type' => 'B+',
            ],
            [
                'status' => 'urgent',
                'type' => 'B-',
            ],
            [
                'status' => 'urgent',
                'type' => 'AB+',
            ],
            [
                'status' => 'urgent',
                'type' => 'AB-',
            ]
        ];

        self::assertEquals($expected, $results);
    }
}
