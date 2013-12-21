<?php
namespace HouseFinder\ParserBundle\Parser;

use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\ParserBundle\Parser\BaseParser;
use HouseFinder\ParserBundle\Service\SlandoService;
use phpOCR\cOCR;
use Symfony\Component\DomCrawler\Crawler;


class SlandoParser extends BaseParser
{
    /**
     * @param Crawler $crawler
     * @return mixed|void
     */
    protected function parseListDomCrawler(Crawler $crawler)
    {
        $links = $crawler->filter('a.detailsLink')->each(function (Crawler $node, $i) {
            $text = $node->text();
            $text = trim($text, " \n\t\r\0\x0B\xC2\xA0");
            if (empty($text)) return false;
            return array(
                'text' => $text,
                'url' => $node->attr('href'),
                //TODO: parse (ID7XG8F) from zhitomir.zht.slando.ua/obyavlenie/sdam-svoyu-2-h-komnatnuyu-kvartiru-v-zhitomire-ID7XG8F.html
                'sourceHash' => '',
            );
        });
        $pages = array();
        foreach ($links as $key => $link) {
            if ($link === false) continue;
            $service = $this->container->get('housefinder.parser.service.slando');
            $crawlerPage = $service->getPageCrawler($link['url']);
            $pageData = $this->parsePageDomCrawler($crawlerPage);
            $pages[$key] = $link;
            $pages[$key]['data'] = $pageData;
            //TODO: temporary
            break;
        }

        header('Content-Type: text/html; charset=utf-8');
        echo "<pre>";
        var_dump($pages);
        exit;

        return $pages;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    protected function parsePageDomCrawler(Crawler $crawler)
    {
        header('Content-Type: text/html; charset=utf-8');
        $data = array();
        $data['params'] = $crawler->filter('div.pding5_10')->each(function (Crawler $node, $i) {
            $text = $node->text();
            $text = trim($text, " \n\t\r\0\x0B\xC2\xA0");
            $data = explode(":", $text, 2);
            $data[1] = trim($data[1], " \n\t\r\0\x0B\xC2\xA0");
            return $data;
        });
        $data['text'] = $crawler->filter('#textContent p.large')->text();
        $data['price'] = $crawler->filter("div.pricelabel.tcenter strong")->text();
        $data['photo'] = $crawler->filter("div.photo-glow img")->extract(array('src'));
        $data['ownerName'] = $crawler->filter("p.userdetails span")->eq(0)->text();
        $data['ownerUrl'] = $crawler->filter("#linkUserAds")->extract(array('href'))[0];
        if(preg_match("/\/user\/(.*)\/$/i", $data['ownerUrl'], $matches)){
            $data['ownerHash'] = $matches[1];
            $data['ownerId'] = $matches[1];
        }
        $data['createdText'] = $crawler->filter("div.offerheadinner p small span")->text();
        $data['createdText'] = str_replace('Добавлено: в', '', $data['createdText']);
        $data['createdText'] = trim($data['createdText'], " \n\t\r\0\x0B\xC2\xA0");
        //13:07, 21 декабря 2013, номер: 121717607
        if(preg_match("/(\d+)\:(\d+)\, (\d+) (\w+) (\d+)\, номер\: (\d+)/iu", $data['createdText'], $matches)){
            $data['sourceID'] = $matches[6];
            $data['createdDateTime'] = $matches[5].'-'.$this->getMonthByRussianName($matches[4]).'-'.$matches[3].' '.$matches[1].':'.$matches[2].':00';
        }
        $phone = $crawler->filter("span[data-rel=phone]")->eq(0)->extract(array('class'))[0];
        if (preg_match("/\{.*\}/", $phone, $matches)) {
            $phoneJSON = json_decode(strtr($matches[0], "'", '"'), true);
            $phoneHash = $phoneJSON['id'];
            /** @var $service SlandoService */
            $service = $this->container->get('housefinder.parser.service.slando');
            $phoneURL = 'http://slando.ua/ajax/misc/contact/phone/' . $phoneHash . '/';
            sleep(3);
            $phoneContent = json_decode($service->getPageContent($phoneURL), true);
            list(, $phoneContent,) = explode('"', $phoneContent);
            $phoneFile = $service->getFile($phoneContent);
            $template = cOCR::loadTemplate('slando');
            $img = cOCR::openImg($phoneFile);
            cOCR::setInfelicity(5);
            $data['phone'] = explode(",", preg_replace("/[^\d,]/", "", cOCR::defineImg(cOCR::$img, $template)));
            unlink($phoneFile);
        }

        echo "<pre>";
        var_dump($data);
        exit;

        return $data;
    }

    /**
     * @param string|string $raw
     * @return Advertisement;
     */
    protected function getEntityByRAW($raw)
    {
        $entity = new Advertisement();

        return $entity;
    }

    private function getMonthByRussianName($russianName)
    {
        $month = array(
            'января',
            'февраля',
            'марта',
            'апреля',
            'мая',
            'июня',
            'июля',
            'августа',
            'сентября',
            'октября',
            'ноября',
            'декабря',
        );
        $keys = array_keys($month, $russianName);
        return sprintf("%02d", $keys[0]);
    }

}
