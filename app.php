<?php
declare(strict_types=1);
date_default_timezone_set('Europe/Prague');

use app\Commands\Klatovy;
use app\Commands\Liberec;
use app\Commands\Trutnov;
use app\Commands\UstavHematologieAKrevniTransuze;
use app\Commands\Vfn;
use Symfony\Component\Console\Application;

require __DIR__ . '/vendor/autoload.php';

$app = new Application();

$app->add(new Klatovy());
$app->add(new Liberec());
$app->add(new UstavHematologieAKrevniTransuze());
$app->add(new Vfn());
$app->add(new Trutnov());

$app->run();
