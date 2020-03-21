<?php
declare(strict_types=1);

use app\Commands\Klatovy;
use app\Commands\Liberec;
use app\Commands\UstavHematologieAKrevniTransuze;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$app = new Application();

$app->add(new Klatovy());
$app->add(new Liberec());
$app->add(new UstavHematologieAKrevniTransuze());

$app->run();
