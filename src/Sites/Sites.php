<?php
declare(strict_types=1);

namespace app\Sites;

use app\ContentLoaders\LoadContentHttp;
use app\Storage\IStorage;

abstract class Sites
{
    public const STATUS_FULL = 'full';
    public const STATUS_NORMAL = 'warning';
    public const STATUS_URGENT = 'urgent';
    public const STATUS_UNKNOWN = 'unknown';

    /**
     * @var string
     */
    protected $name = '';

    /**
     * @var string
     */
    protected $url = '';

    abstract public function parse(LoadContentHttp $content, IStorage $storage): array;

    public function getName(): string
    {
        return $this->name;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
