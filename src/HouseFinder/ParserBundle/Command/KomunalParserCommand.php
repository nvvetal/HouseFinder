<?php
namespace HouseFinder\ParserBundle\Command;

use HouseFinder\ParserBundle\Service\KomunalService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class KomunalParserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('housefinder:parser:komunal')
            ->setDescription('Parsing Komunal!')
            ->addArgument(
                'date',
                InputArgument::OPTIONAL,
                'Date to parse'
            );

        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $date = $input->getArgument('date');
        $d = (!empty($date)) ? \DateTime::createFromFormat('Y-m-d', $date) : \DateTime::createFromFormat('Y-m-d', date('Y-m-d', strtotime('-1 day')));
        $container = $this->getApplication()->getKernel()->getContainer();
        /** @var $service KomunalService */
        $service = $container->get('housefinder.parser.service.komunal');
        $urls = array(
            'https://komunal.com.ua/ads/index.php?action=listProblems',
        );
        $output->writeln('Parsing date: '.$d->format('Y-m-d'));
        foreach ($urls as $url){
            $output->writeln('Parsing Komunal URL: '.$url);
            $res = $service->fillLastIssues($url, $d);
            $output->writeln('TOTAL: '.print_r($res, true));
        }
        $text = 'Happy END';
        $output->writeln($text);
    }
}