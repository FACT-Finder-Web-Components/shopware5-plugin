<?php

declare(strict_types=1);

namespace OmikronFactfinder\Commands;

use OmikronFactfinder\Components\Service\ExportService;
use OmikronFactfinder\Components\Service\ShopEmulationService;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportArticlesCommand extends ShopwareCommand
{
    private const SHOP_ID_ARGUMENT = 'shop_id';

    protected function configure()
    {
        $this->setDescription('Export articles to csv file.');
        $this->addArgument(self::SHOP_ID_ARGUMENT, InputArgument::OPTIONAL, 'Shop used for export', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->registerErrorHandler($output);

        $shopEmulation = $this->getContainer()->get(ShopEmulationService::class);
        $shopEmulation->emulateShop((int) $input->getArgument(self::SHOP_ID_ARGUMENT), function () {
            $exportService = $this->getContainer()->get(ExportService::class);
            $exportService->generate($this->getContainer()->get('OmikronFactfinder\Components\Output\Csv'));
        });
    }
}
