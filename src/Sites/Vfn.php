<?php
declare(strict_types = 1);

namespace app\Sites;

use app\Blood;
use app\ContentLoaders\LoadContentHttp;
use app\Storage\IStorage;
use Symfony\Component\DomCrawler\Crawler;

final class Vfn extends Sites
{
    protected $name = 'Všeobecná fakultní nemocnice v Praze';
    protected $url = 'https://www.vfn.cz/pacienti/kliniky-ustavy/fakultni-transfuzni-oddeleni/aktualni-potreba-krve/';

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
        $items = $crawler->filter('#idobsahu')->children('table.aligncenter')->first();

        if ($items->count()) {
            /** @var \DOMElement $item */
            foreach ($items->children('tbody tr td') as $item) {
                $itemDom = new Crawler($item);
                $style = $itemDom->attr('style');
                $status = null;
                if ($style) {
                    if (preg_match('@background-color:(#[a-z0-9]+)@', $style, $matches)) {
                        $status = $this->getStatus($matches[1]);
                    }
                    $type = $this->convertBloodType(trim($itemDom->children('strong')->text()));
                    if ($status && $type) {
                        $results[] = ['type' => $this->convertBloodType($type), 'status' => $status];
                    }
                }
            }
        }

        return $this->sort($results);
    }

    private function getStatus(string $value): string
    {
        switch ($value) {
            case '#21a900':
                return self::STATUS_NORMAL;
            case '#fa0106':
                return self::STATUS_URGENT;
            case '#32ff33':
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
