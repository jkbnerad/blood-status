<?php
declare(strict_types = 1);

namespace app\Storage;

class File implements IStorage
{
    /**
     * @var array
     */
    private $config = [
        'path' => __DIR__ . '/../../data'
    ];

    public function __construct(array $config = [])
    {
        $this->config = array_merge($this->config, $config);
    }

    public function save(array $data): bool
    {
        return file_put_contents($this->config['path'] . '/' . $this->config['name'], json_encode($data)) ? true : false;
    }
}
