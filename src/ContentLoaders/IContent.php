<?php
declare(strict_types=1);

namespace app\ContentLoaders;


interface IContent
{
    public function load(string $source): string;
}
