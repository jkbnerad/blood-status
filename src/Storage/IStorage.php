<?php
declare(strict_types = 1);

namespace app\Storage;


interface IStorage
{
    public function save(array $data): bool;
}
