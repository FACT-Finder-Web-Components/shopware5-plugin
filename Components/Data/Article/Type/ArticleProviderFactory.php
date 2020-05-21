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
        if ($detail->getKind() == self::MAIN_ARTICLE_KIND) {
            $article = clone $this->container->get('OmikronFactfinder\Components\Data\Article\Type\MainArticleProvider');
        } else {
            $article = clone $this->container->get('OmikronFactfinder\Components\Data\Article\Type\VariantProvider');
            $article->setData($data);
        }

        $article->setDetail($detail);
        $article->setArticle($detail->getArticle());

        return $article;
    }
}
