<?php
declare(strict_types = 1);

namespace app\Sites;

use app\Blood;
use app\ContentLoaders\LoadContentHttp;
use app\Storage\IStorage;
use Symfony\Component\DomCrawler\Crawler;

final class Liberec extends Sites
{
    protected $name = 'Nemocnice Liberec';
    protected $url = 'https://www.nemlib.cz/darovani-krve/';

    public function parse(LoadContentHttp $content, IStorage $storage): array
    {
        $data = $this->parseHtml($content->load($this->url));
        $storage->save($data);
        return $data;
    }

    private function parseHtml(string $html): array
    {
        $crawler = new Crawler($html);
        $results = [];
        $items = $crawler->filter('#blood-supplies-table');
        if ($items->count()) {

            /** @var \DOMElement $item */
            foreach ($items->children('.type') as $i => $item) {
                $itemDom = new Crawler($item);
                $class = $itemDom->attr('class');
                $status = null;
                if ($class && preg_match('@supply-([\d]+)@', $class, $matches)) {
                    $supply = (int) $matches[1];
                    $status = $this->getStatus($supply);
                }

                $type = $itemDom->children('div.lablel')->first()->text();

                if ($status && $type) {
                    $results[] = ['type' => $this->convertBloodType($type), 'status' => $status];
                }
            }
        }

        return $this->sort($results);
    }

    private function getStatus(int $value): string
    {
        switch ($value) {
            case 2:
                return self::STATUS_NORMAL;
            case 1:
            case 0:
                return self::STATUS_URGENT;
            case 3:
            case 4:
                return self::STATUS_FULL;
            default:
                throw new \InvalidArgumentException('Unknown status ' . $value . '.');
        }
    }

    private function convertBloodType(string $value): ?string
    {
        switch ($value) {
            case '0-':
                return Blood::BLOOD_TYPE_ZERO_NEGATIVE;
            case '0+':
                return Blood::BLOOD_TYPE_ZERO_POSITIVE;
            case 'A-':
                return Blood::BLOOD_TYPE_A_NEGATIVE;
            case 'A+':
                return Blood::BLOOD_TYPE_A_POSITIVE;
            case 'B-':
                return Blood::BLOOD_TYPE_B_NEGATIVE;
            case 'B+':
                return Blood::BLOOD_TYPE_B_POSITIVE;
            case 'AB-':
                return Blood::BLOOD_TYPE_AB_NEGATIVE;
            case 'AB+':
                return Blood::BLOOD_TYPE_AB_POSITIVE;
            case 'Krevní plazma':
                return null;
            default:
                throw new \InvalidArgumentException('Unknown blood type ' . $value . '.');
        }
    }

}
