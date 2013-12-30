<?php
namespace HouseFinder\ParserBundle\Command;

use HouseFinder\CoreBundle\Entity\Advertisement;
use HouseFinder\ParserBundle\Service\SlandoService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SlandoParserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('housefinder:parser:slando')
            ->setDescription('Parsing Slando!')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $container = $this->getApplication()->getKernel()->getContainer();
        /** @var $service SlandoService */
        $service = $container->get('housefinder.parser.service.slando');
        $urls = array(
            //'http://zhitomir.zht.slando.ua/nedvizhimost/arenda-kvartir/?page=3' => array('type' => Advertisement::TYPE_RENT),
            //'http://zhitomir.zht.slando.ua/nedvizhimost/arenda-kvartir/?page=2' => array('type' => Advertisement::TYPE_RENT),
            //'http://zhitomir.zht.slando.ua/nedvizhimost/arenda-kvartir/' => array('type' => Advertisement::TYPE_RENT),
            'http://zhitomir.zht.slando.ua/nedvizhimost/prodazha-kvartir/?currency=USD' => array('type' => Advertisement::TYPE_SELL),

        );
        foreach ($urls as $url => $urlData){
            $output->writeln('Parsing Slando Type: '.$urlData['type'].', URL: '.$url);
            $res = $service->fillLastAdvertisements($url, $urlData['type']);
            $output->writeln('TOTAL: '.print_r($res, true));
        }
        $text = 'Happy END';
        $output->writeln($text);
    }
}