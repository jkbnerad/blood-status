<?php
declare(strict_types = 1);

namespace app\Commands;

use app\ContentLoaders\LoadContentHttp;
use app\Storage\GoogleSheet;
use GuzzleHttp\Client;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Klatovy extends Command
{
    protected static $defaultName = 'app:klatovy';

    protected function configure(): void
    {
        $this
            ->setDescription('Scrape semaphore from Klatovy Hospital.');
        $this->addOption('secretJson', 'j', InputArgument::OPTIONAL, 'Google Service Accounts Json');
        $this->addOption('sheetId', 's', InputArgument::OPTIONAL, 'Google Sheet ID', );
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $googleSheet = new GoogleSheet($input->getOption('sheetId') ?: null, $input->getOption('secretJson') ?: null);
        $klatovy = new \app\Sites\Klatovy();
        $output->writeln('=== ' . $klatovy->getName() . ' ### ' . $klatovy->getUrl() . ' === ');
        $statuses = $klatovy->parse(new LoadContentHttp(new Client(['timeout' => 10])), $googleSheet);
        $output->writeln($statuses ? '=== Saved ===' : '=== Failed ===');
        return 0;
    }
}
