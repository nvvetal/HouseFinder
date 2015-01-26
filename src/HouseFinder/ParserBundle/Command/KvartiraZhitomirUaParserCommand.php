<?php
namespace HouseFinder\ParserBundle\Command;

use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\ParserBundle\Service\KvartiraZhitomirUaService;
use HouseFinder\ParserBundle\Service\SlandoService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KvartiraZhitomirUaParserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('housefinder:parser:kvartira_zhitomir_ua')
            ->setDescription('Parsing KvartiraZhitomirUa!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        /** @var $service KvartiraZhitomirUaService */
        $service = $container->get('housefinder.parser.service.kvartira_zhitomir_ua');
        $urls = array(
            'http://kvartira.zhitomir.ua/prodazha-odnokomnatnyh-kvartir-v-zhitomire.html' => array('type' => Advertisement::TYPE_SELL),
            'http://kvartira.zhitomir.ua/prodazha-dvuhkomnatnyh-kvartir-v-zhitomire.html' => array('type' => Advertisement::TYPE_SELL),
            'http://kvartira.zhitomir.ua/prodazha-trehkomnatnyh-kvartir-v-zhitomire.html' => array('type' => Advertisement::TYPE_SELL),
            'http://kvartira.zhitomir.ua/prodazha-chetyrehkomnatnyh-kvartir-v-zhitomire.html' => array('type' => Advertisement::TYPE_SELL),
        );
        foreach ($urls as $url => $urlData){
            $output->writeln('Parsing KvartiraZhitomirUa Type: '.$urlData['type'].', URL: '.$url);
            $res = $service->fillLastAdvertisements($url, $urlData['type']);
            $output->writeln('TOTAL: '.print_r($res, true));
        }
        $text = 'Happy END';
        $output->writeln($text);
    }
}