<?php

declare(strict_types=1);

namespace OmikronFactfinder\Commands;

use OmikronFactfinder\Components\Output\Csv;
use OmikronFactfinder\Components\Service\ExportService;
use OmikronFactfinder\Components\Service\ShopEmulationService;
use Shopware\Commands\ShopwareCommand;
use Shopware\Models\Shop\Shop;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExportArticlesCommand extends ShopwareCommand
{
    private const SHOP_ID_ARGUMENT = 'shop_id';

    protected function configure()
    {
        $this
            ->setName('factfinder:export:articles')
            ->setDescription('Export articles to csv file.')
            ->addArgument(self::SHOP_ID_ARGUMENT, InputArgument::OPTIONAL, 'Shop used for export', 1);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var ShopEmulationService $shopEmulation */
        $shopEmulation = $this->getContainer()->get('OmikronFactfinder\Components\Service\ShopEmulationService');
        $shopId = (int) $input->getArgument(self::SHOP_ID_ARGUMENT);
        $shopEmulation->emulateShop($shopId);

        $this->registerErrorHandler($output);

        /** @var ExportService $exportService */
        $exportService = $this->getContainer()->get('OmikronFactfinder\Components\Service\ExportService');
        $exportService->generate(new Csv());
        return 0;
    }
}
