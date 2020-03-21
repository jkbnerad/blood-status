<?php
declare(strict_types = 1);

namespace app\Sites;

use app\Blood;
use app\ContentLoaders\LoadContentHttp;
use app\Storage\IStorage;
use Symfony\Component\DomCrawler\Crawler;

final class UstavHematologieAKrevniTransfuze extends Sites
{
    protected $name = 'Ústav hematologie a krevní transfuze';
    protected $url = 'https://www.uhkt.cz/darci';

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
        $items = $crawler->filter('ul.barometer');

        if ($items->count()) {

            foreach ($items->children('li') as $item) {
                $itemDom = new Crawler($item);
                $span = $itemDom->children('span')->first();
                $class = $span->attr('class');
                $status = null;
                if ($class && preg_match('@([a-z]+)-blood@', $class, $matches)) {
                    $status = $this->getStatus($matches[1]);
                }

                $type = $this->convertBloodType(trim($itemDom->text()));
                if ($status && $type) {
                    $results[] = ['type' => $this->convertBloodType($type), 'status' => $status];
                }
            }
        }

        return $this->sort($results);
    }

    private function getStatus(string $value): string
    {
        switch ($value) {
            case 'full':
                return self::STATUS_URGENT;
            case 'half':
                return self::STATUS_NORMAL;
            case 'empty':
                return self::STATUS_FULL;
            default:
                throw new \InvalidArgumentException('Unknown blood type ' . $value . '.');
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
