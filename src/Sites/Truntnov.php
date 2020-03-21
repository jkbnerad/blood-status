<?php
declare(strict_types = 1);

namespace app\Sites;

use app\Blood;
use app\ContentLoaders\LoadContentHttp;
use app\Storage\IStorage;
use Symfony\Component\DomCrawler\Crawler;

final class Truntnov extends Sites
{
    protected $name = 'Nemocnice Trutnov';
    protected $url = 'http://www.nemtru.cz/oddeleni-ambulance/darcovsky-usek';

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
        $items = $crawler->filter('.blood_state');

        if ($items->count()) {
            /** @var \DOMElement $item */
            foreach ($items->children('div') as $item) {
                $itemDom = new Crawler($item);
                $rh = $itemDom->attr('class');

                foreach ($itemDom->children('div p') as $p) {
                    $pDom = new Crawler($p);
                    $type = $pDom->text();

                    if ($type !== '' && $rh) {
                        $type = $this->convertBloodType($type, $rh);
                        $img = $pDom->children('a img');
                        $src = $img->attr('src');
                        $status = null;
                        if ($src && preg_match('@blood_([\d]{1})@', $src, $matches)) {
                            $status = $this->getStatus((int) $matches[1]);
                        }

                        if ($type && $status) {
                            $results[] = ['type' => $type, 'status' => $status];
                        }
                    }
                }
            }
        }

        return $this->sort($results);
    }

    private function getStatus(int $value): string
    {
        switch ($value) {
            case 0:
            case 1:
                return self::STATUS_URGENT;
            case 2:
                return self::STATUS_NORMAL;
            case 3:
                return self::STATUS_FULL;
            default:
                throw new \InvalidArgumentException('Unknown status ' . $value . '.');
        }
    }

    private function convertBloodType(string $value, string $rh): ?string
    {
        if ($rh === 'rh_faktor_plus') {
            $value .= '+';
        } elseif($rh === 'rh_faktor_minus') {
            $value .= '-';
        }

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
