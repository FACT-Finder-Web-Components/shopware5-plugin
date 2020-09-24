<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\ExportEntityInterface;
use Shopware\Models\Article\Detail;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ProviderFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private const MAIN_DETAIL_KIND = 1;

    public function create(Detail $detail, array $data = []): ExportEntityInterface
    {
        if ($detail->getKind() === self::MAIN_DETAIL_KIND) {
            $entity = clone $this->container->get(MainDetailProvider::class);
        } else {
            $entity = clone $this->container->get(DetailProvider::class);
            $entity->setData($data);
        }

        $entity->setDetail($detail);
        $entity->setArticle($detail->getArticle());

        return $entity;
    }
}
