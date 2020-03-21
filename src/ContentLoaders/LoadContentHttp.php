<?php
declare(strict_types=1);

namespace app\ContentLoaders;

use GuzzleHttp\Client;

class LoadContentHttp implements IContent
{
    /**
     * @var Client
     */
    private $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function load(string $source): string
    {
        $response = $this->httpClient->get($source);
        return $response->getBody()->getContents();
    }
}
