<?php

declare(strict_types=1);

namespace OmikronFactfinder\Components\Data\Article;

use Doctrine\DBAL\Connection;
use OmikronFactfinder\Components\Service\TranslationService;
use PDO;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Components\Api\Resource\Article as ArticleResource;
use Shopware\Components\Api\Resource\Resource;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Detail;

class ArticleDetails implements \IteratorAggregate
{
    /** @var ArticleResource */
    private $articleResource;

    /** @var ContextServiceInterface */
    private $contextService;

    /** @var Connection */
    private $dbalConnection;

    /** @var TranslationService */
    private $translationService;

    /** @var int */
    private $batchSize;

    public function __construct(
        ArticleResource $articleResource,
        ContextServiceInterface $contextService,
        Connection $dbalConnection,
        TranslationService $translationService,
        int $batchSize
    ) {
        $this->articleResource    = $articleResource;
        $this->contextService     = $contextService;
        $this->dbalConnection     = $dbalConnection;
        $this->translationService = $translationService;
        $this->batchSize          = $batchSize;
    }

    public function getIterator()
    {
        $shop = $this->contextService->getContext()->getShop();
        $this->translationService->loadPropertiesTranslations((int) $shop->getId());
        $toExport = $this->loadProductIds((int) $shop->getCategory()->getId());
        yield from [];
        for ($page = 0; $list = $this->getArticles($toExport, $page, $this->batchSize); $page++) {
            foreach ($list as $article) {
                /* @var Article $article */
                yield from $article->getDetails()->filter(function (Detail $detail): bool {
                    return (bool) $detail->getActive();
                });
            }
        }
    }

    private function getArticles(array $activeShopProductIds, int $page, int $pageSize): iterable
    {
        $sortBy  = [['property' => 'article.id']];
        $shopId  = (int) $this->contextService->getShopContext()->getShop()->getId();
        $toFetch = array_slice($activeShopProductIds, $page * $pageSize, $pageSize);

        $this->translationService->loadProductTranslations($shopId, $toFetch);
        $this->translationService->loadCategoriesTranslations($shopId, $toFetch);

        $this->articleResource->setResultMode(Resource::HYDRATE_OBJECT);

        return $this->articleResource->getList(
            0,
            $pageSize,
            ['active' => true, ['property' => 'id', 'expression' => '', 'value' => $toFetch]],
            $sortBy
        )['data'];
    }

    private function loadProductIds(int $categoryId): array
    {
        $data = $this->dbalConnection
            ->createQueryBuilder()
            ->select(['article.id as articleId', 'articleDetail.ordernumber as articleNumber', 'group_concat(category.path) as paths'])
            ->from('s_articles', 'article')
            ->leftJoin('article', 's_articles_categories', 'articleCategory', 'articleCategory.articleID = article.id')
            ->leftJoin('article', 's_articles_details', 'articleDetail', 'articleDetail.articleID = article.id')
            ->leftJoin('articleCategory', 's_categories', 'category', 'category.id = articleCategory.categoryId')
            ->groupBy('article.id')
            ->execute()
            ->fetchAll(PDO::FETCH_ASSOC);

        return array_map(function (array $row): string {
            return $row['articleId'];
        }, array_filter($data, function (array $row) use ($categoryId): bool {
            $paths = array_filter(explode(',', (string) $row['paths']));
            return !empty(array_filter($paths, function (string $path) use ($categoryId): bool {
                return (bool) strstr($path, "|$categoryId|");
            }));
        }));
    }
}
