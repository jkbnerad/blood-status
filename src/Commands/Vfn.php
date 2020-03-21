<?php
declare(strict_types = 1);

namespace app\Commands;

use app\ContentLoaders\LoadContentHttp;
use app\HttpClient;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Vfn extends Command
{
    protected static $defaultName = 'app:vfn';

    protected function configure(): void
    {
        parent::configure();
        $this->setDescription('Všeobecná fakultní nemocnice v Praze');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $Vfn = new \app\Sites\Vfn();
        $output->writeln('=== ' . $Vfn->getName() . ' ### ' . $Vfn->getUrl() . ' === ');
        $statuses = $Vfn->parse(new LoadContentHttp(new HttpClient()), $this->getGoogleSheerStorage($input, 'Vfn'));
        $output->writeln($statuses ? '=== Saved ===' : '=== Failed ===');
        return 0;
    }
}
