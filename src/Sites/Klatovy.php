<?php
declare(strict_types = 1);

namespace app\Sites;

use app\Blood;
use app\ContentLoaders\LoadContentHttp;
use app\Storage\IStorage;
use Symfony\Component\DomCrawler\Crawler;

class Klatovy extends Sites
{
    protected $name = 'Nemocnice Klatovy';
    protected $url = 'https://klatovy.nemocnicepk.cz/krev/';

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
        $items = $crawler->filter('div.blood__item');
        if ($items->count()) {

            /** @var \DOMElement $item */
            foreach ($items->children('.blood__ico img') as $i => $item) {
                $img = $item->getAttribute('src');
                if (preg_match('@([a-z0-9\-]+\.svg)@', $img, $matches)) {
                    $svg = $matches[0];
                    switch ($svg) {
                        case 'blood-prijdte.svg':
                            $status = self::STATUS_URGENT;
                            break;
                        case 'blood-odlozte.svg':
                            $status = self::STATUS_FULL;
                            break;
                        default:
                            // @todo nevim jak se jmenuje ten obrazek, ale je to posleni mozna varianta
                            $status = self::STATUS_NORMAL;
                            break;
                    }
                    $results[$i] = ['status' => $status];
                }
            }

            /** @var \DOMElement $item */
            foreach ($items->children('.blood__name') as $i => $item) {
                $type = $this->convertBloodType(trim((string) $item->nodeValue));
                if ($type) {
                    if (isset($results[$i])) {
                        $results[$i]['type'] = $type;
                    } else {
                        $results[$i] = ['type' => $type, 'status' => self::STATUS_UNKNOWN];
                    }
                } elseif (isset($results[$i])) {
                    unset($results[$i]);
                }
            }
        }

        return $results;
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
