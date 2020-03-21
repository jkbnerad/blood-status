<?php
declare(strict_types = 1);

namespace app\Storage;

class GoogleSheet implements IStorage
{
    /**
     * @var string|null
     */
    private $sheet = '1dXkmzsDwuUC-1iM2S6JDBb647VKKEZNWUH2xnCjL3qw';

    /**
     * @var string|null
     */
    private $privacyFile = __DIR__ . '/../../privacy/google.json';

    public function __construct(?string $sheetId = null, ?string $privacyFile = null)
    {
        if ($sheetId) {
            $this->sheet = $sheetId;
        }

        if($privacyFile) {
            $this->privacyFile = $privacyFile;
        }
    }

    private function writeToSheet(array $statuses): void
    {
        $client = new \Google_Client();
        $client->setApplicationName('Google Sheets');
        $client->setScopes([\Google_Service_Sheets::SPREADSHEETS]);
        if ($this->privacyFile) {
            $client->setAuthConfig($this->privacyFile);
        }
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $rowOne = $rowTwo = [];
        foreach($statuses as $status) {
            $rowTwo[] = $status['status'];
        }

        foreach($statuses as $status) {
            $rowOne[] = $status['type'];
        }

        $service = new \Google_Service_Sheets($client);
        $spreadsheetId = $this->sheet;

        $rangeRowOne = 'Status!A2';
        $values = new \Google_Service_Sheets_ValueRange();
        $values->setValues([$rowOne]);
        $service->spreadsheets_values->update($spreadsheetId, $rangeRowOne, $values, ['valueInputOption' => 'USER_ENTERED']);

        $rangeRowTwo = 'Status!A3';
        $values = new \Google_Service_Sheets_ValueRange();
        $values->setValues([$rowTwo]);
        $service->spreadsheets_values->update($spreadsheetId, $rangeRowTwo, $values, ['valueInputOption' => 'USER_ENTERED']);
    }

    public function save(array $data): bool
    {
        $this->writeToSheet($data);
        return true;
    }

}
