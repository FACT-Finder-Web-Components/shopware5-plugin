<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\ExportEntityInterface;
use Shopware\Models\Article\Detail;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ArticleProviderFactory implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    private const MAIN_DETAIL_KIND = 1;

    public function create(Detail $detail): ExportEntityInterface
    {
        if ($detail->getKind() === self::MAIN_DETAIL_KIND) {
            $article = clone $this->container->get(MainDetailProvider::class);
        } else {
            $article = clone $this->container->get(DetailProvider::class);
        }

        $article->setDetail($detail);
        $article->setArticle($detail->getArticle());

        return $article;
    }
}
