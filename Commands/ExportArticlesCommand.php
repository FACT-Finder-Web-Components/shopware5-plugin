<?php

declare(strict_types=1);

namespace OmikronFactfinder\Commands;

use OmikronFactfinder\Components\Service\ExportService;;

use OmikronFactfinder\Components\Output\Csv;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportArticlesCommand extends ShopwareCommand
{
    protected function configure()
    {
        $this
            ->setName('factfinder:export:articles')
            ->setDescription('Export articles to csv file.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->registerErrorHandler($output);
        /** @var ExportService $exportService */
        $exportService = $this->getContainer()->get('OmikronFactfinder\Components\Service\ExportService');
        $exportService->generate(new Csv());
        return 0;
    }
}
