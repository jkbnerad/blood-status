<?php
declare(strict_types = 1);

namespace app\Commands;

use app\ContentLoaders\LoadContentHttp;
use app\HttpClient;
use app\Storage\GoogleSheet;
use GuzzleHttp\Client;

use http\Exception\RuntimeException;
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
        $sheetId = $input->getOption('sheetId') ?: null;
        $secretJson = $input->getOption('secretJson') ?: null;

        if (!is_string($sheetId) && $sheetId !== null) {
            throw new \RuntimeException('Sheet ID must be string or null.');
        }

        if (!is_string($secretJson) && $secretJson !== null) {
            throw new \RuntimeException('Secret JSON ID must be string or null.');
        }

        $googleSheet = new GoogleSheet('Klatovy', $sheetId, $secretJson);
        $klatovy = new \app\Sites\Klatovy();
        $output->writeln('=== ' . $klatovy->getName() . ' ### ' . $klatovy->getUrl() . ' === ');
        $statuses = $klatovy->parse(new LoadContentHttp(new HttpClient()), $googleSheet);
        $output->writeln($statuses ? '=== Saved ===' : '=== Failed ===');
        return 0;
    }
}
