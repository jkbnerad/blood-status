<?php
declare(strict_types = 1);

namespace app\Commands;

use app\ContentLoaders\LoadContentHttp;
use app\HttpClient;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class UstavHematologieAKrevniTransuze extends Command
{
    protected static $defaultName = 'app:uhkt';

    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Ústav hematologie a krevní transfuze');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $uhkt = new \app\Sites\UstavHematologieAKrevniTransfuze();
        $output->writeln('=== ' . $uhkt->getName() . ' ### ' . $uhkt->getUrl() . ' === ');
        $statuses = $uhkt->parse(new LoadContentHttp(new HttpClient()), $this->getGoogleSheerStorage($input, 'UHKT'));
        $output->writeln($statuses ? '=== Saved ===' : '=== Failed ===');
        return 0;
    }
}
