<?php
declare(strict_types = 1);

namespace app\Commands;

use app\Storage\GoogleSheet;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;

abstract class Command extends \Symfony\Component\Console\Command\Command
{
    protected function configure(): void
    {
        $this->addOption('secretJson', 'j', InputArgument::OPTIONAL, 'Google Service Accounts Json');
        $this->addOption('sheetId', 's', InputArgument::OPTIONAL, 'Google Sheet ID',);
    }

    protected function getGoogleSheerStorage(InputInterface $input, string $list): GoogleSheet
    {
        $sheetId = $input->getOption('sheetId') ?: null;
        $secretJson = $input->getOption('secretJson') ?: null;

        if (!is_string($sheetId) && $sheetId !== null) {
            throw new \RuntimeException('Sheet ID must be string or null.');
        }

        if (!is_string($secretJson) && $secretJson !== null) {
            throw new \RuntimeException('Secret JSON ID must be string or null.');
        }

        return new GoogleSheet($list, $sheetId, $secretJson);
    }
}
