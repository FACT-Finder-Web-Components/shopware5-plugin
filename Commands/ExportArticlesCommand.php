<?php

declare(strict_types=1);

namespace OmikronFactfinder\Commands;

use OmikronFactfinder\Components\Output\Csv;
use OmikronFactfinder\Components\Service\ExportService;
use OmikronFactfinder\Components\Service\ShopEmulationService;
use OmikronFactfinder\Components\Service\UploadService;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportArticlesCommand extends ShopwareCommand
{
    private const SHOP_ID_ARGUMENT = 'shop_id';
    private const UPLOAD_FEED_OPTION = 'upload';

    protected function configure()
    {
        $this->setDescription('Export articles to csv file.');
        $this->addArgument(self::SHOP_ID_ARGUMENT, InputArgument::OPTIONAL, 'Shop used for export', 1);
        $this->addOption(self::UPLOAD_FEED_OPTION, 'u', InputOption::VALUE_NONE, 'Should upload after exporting');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->registerErrorHandler($output);

        $shopEmulation = $this->getContainer()->get(ShopEmulationService::class);
        $shopEmulation->emulateShop((int) $input->getArgument(self::SHOP_ID_ARGUMENT), function () use ($input, $output) {
            $exportService = $this->getContainer()->get(ExportService::class);
            $feed          = $exportService->generate($this->getContainer()->get(Csv::class));
            $output->writeln('<info>Feed has been generated</info>');

            if ($input->getOption(self::UPLOAD_FEED_OPTION)) {
                $uploadService = $this->getContainer()->get(UploadService::class);
                $uploadService->uploadFeed($feed, $feed->getFilename());
                $output->writeln('<info>Feed has been uploaded</info>');
            }
        });
    }
}
