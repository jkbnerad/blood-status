<?php
declare(strict_types=1);

namespace app\ContentLoaders;

use GuzzleHttp\ClientInterface;

class LoadContentHttp implements IContent
{
    /**
     * @var ClientInterface
     */
    private $httpClient;

    public function __construct(ClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function load(string $source): string
    {
        $response = $this->httpClient->get($source);
        return $response->getBody()->getContents();
    }
}
