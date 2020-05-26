<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article\Type;

use OmikronFactfinder\Components\Data\ExportEntityInterface;
use Shopware\Models\Article\Detail;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class ArticleProviderFactory
{
    use ContainerAwareTrait;

    private const MAIN_ARTICLE_KIND = 1;

    public function create(Detail $detail, array $data = []): ExportEntityInterface
    {
        if ($detail->getKind() === self::MAIN_ARTICLE_KIND) {
            $article = clone $this->container->get(MainArticleProvider::class);
        } else {
            $article = clone $this->container->get(VariantProvider::class);
            $article->setData($data);
        }

        $article->setDetail($detail);
        $article->setArticle($detail->getArticle());

        return $article;
    }
}
