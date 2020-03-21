<?php
declare(strict_types=1);

use app\Commands\Klatovy;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$app = new Application();

$app->add(new Klatovy());

$app->run();
