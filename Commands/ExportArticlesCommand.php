<?php

declare(strict_types=1);

namespace OmikronFactfinder\Commands;

use OmikronFactfinder\Components\Output\Csv;
use OmikronFactfinder\Components\Service\ExportService;
use OmikronFactfinder\Components\Service\PushImportService;
use OmikronFactfinder\Components\Service\ShopEmulationService;
use OmikronFactfinder\Components\Service\UpdateFieldRolesService;
use OmikronFactfinder\Components\Service\UploadService;
use Shopware\Commands\ShopwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class ExportArticlesCommand extends ShopwareCommand
{
    private const SHOP_ID_ARGUMENT   = 'shop_id';
    private const UPLOAD_FEED_OPTION = 'upload';
    private const PUSH_IMPORT_OPTION = 'import';

    protected function configure()
    {
        $this->setDescription('Export articles to csv file.');
        $this->addArgument(self::SHOP_ID_ARGUMENT, InputArgument::OPTIONAL, 'Shop used for export', 1);
        $this->addOption(self::UPLOAD_FEED_OPTION, 'u', InputOption::VALUE_NONE, 'Should upload after exporting');
        $this->addOption(self::PUSH_IMPORT_OPTION, 'i', InputOption::VALUE_NONE, 'Should import after uploading');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->registerErrorHandler($output);
        $this->container->get(UpdateFieldRolesService::class)->updateFieldRoles();
        exit;
        $shopEmulation = $this->getContainer()->get(ShopEmulationService::class);
        $shopEmulation->emulateShop((int) $input->getArgument(self::SHOP_ID_ARGUMENT), function () use ($input, $output) {
            $feed = $this->getContainer()->get(Csv::class);
            $this->getContainer()->get(ExportService::class)->generate($feed);
            $output->writeln('<info>Feed has been generated</info>');

            if ($input->getOption(self::UPLOAD_FEED_OPTION)) {
                $uploadService = $this->getContainer()->get(UploadService::class);
                $uploadService->uploadFeed($feed->getFilename(), $feed->getContent());
                $output->writeln('<info>Feed has been uploaded</info>');
            }

            if ($input->getOption(self::PUSH_IMPORT_OPTION)) {
                $pushImport = $this->getContainer()->get(PushImportService::class);
                $pushImport->execute();
                $output->writeln('<info>Feed has been imported</info>');
            }
        });
    }
}
