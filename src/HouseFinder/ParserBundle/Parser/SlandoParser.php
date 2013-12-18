<?php
namespace HouseFinder\ParserBundle\Parser;

use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\ParserBundle\Parser\BaseParser;
use Symfony\Component\DomCrawler\Crawler;

class SlandoParser extends BaseParser
{
    /**
     * @param $content
     * @return mixed
     */
    protected function parseContent($content)
    {
        $domCrawler = new Crawler($content);
        //var_dump($content);
        $links = $domCrawler->filter('a.detailsLink')->each(function (Crawler $node, $i) {
            $text = trim($node->text());
            $text = str_replace("\n", "", $text);
            $text = str_replace("\r", "", $text);
            $text = str_replace("\t", "", $text);
            $text = str_replace("\0", "", $text);
            $text = str_replace("\x0B", "", $text);
            $text = trim($text, chr(0xC2).chr(0xA0));
            //TODO: how the HELL is exit there???
            if(empty($text)) return false;
            return array(
                'text'  => $text,
                'url'   => $node->attr('href'),
            );
        });
        $urls = array();
        foreach ($links as $link){
            //if(empty($link['text'])) continue;
            $urls[] = $link;
        }
        header('Content-Type: text/html; charset=utf-8');
        echo "<pre>";
        var_dump($urls);
        exit;
    }

    /**
     * @param string|string $raw
     * @return Advertisement;
     */
    protected function getEntityByRAW($raw)
    {
        // TODO: Implement getEntityByRAW() method.
    }
}
