<?php
declare(strict_types=1);

namespace app\ContentLoaders;

use app\HttpClient;

class LoadContentHttp implements IContent
{
    /**
     * @var HttpClient
     */
    private $httpClient;

    public function __construct(HttpClient $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function load(string $source): string
    {
        $response = $this->httpClient->get($source);
        return $response->getBody()->getContents();
    }
}
