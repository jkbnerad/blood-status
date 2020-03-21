<?php
declare(strict_types = 1);

namespace tests\ContentLoaders;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\TestCase;

final class LoadContentHttp extends TestCase
{
    /**
     * @test
     */
    public function load(): void
    {
        $content = 'Content';
        $mock = new MockHandler([
            new Response(200, ['X-Foo' => 'Bar'], $content),
        ]);

        $handlerStack = HandlerStack::create($mock);
        $client = new Client(['handler' => $handlerStack]);
        $loadContent = new \app\ContentLoaders\LoadContentHttp($client);
        self::assertSame($content, $loadContent->load('http://www.example.com'));
    }
}
