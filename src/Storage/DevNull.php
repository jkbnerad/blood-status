<?php
declare(strict_types=1);

namespace app\Storage;


class DevNull implements IStorage
{

    public function save(array $data): bool
    {
        return true;
    }
}
