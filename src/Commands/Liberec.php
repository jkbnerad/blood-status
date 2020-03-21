<?php
declare(strict_types = 1);

namespace app\Commands;

use app\ContentLoaders\LoadContentHttp;
use app\HttpClient;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Liberec extends Command
{
    protected static $defaultName = 'app:liberec';

    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Nemocnice Liberec');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $klatovy = new \app\Sites\Liberec();
        $output->writeln('=== ' . $klatovy->getName() . ' ### ' . $klatovy->getUrl() . ' === ');
        $statuses = $klatovy->parse(new LoadContentHttp(new HttpClient()), $this->getGoogleSheerStorage($input, 'Liberec'));
        $output->writeln($statuses ? '=== Saved ===' : '=== Failed ===');
        return 0;
    }
}
