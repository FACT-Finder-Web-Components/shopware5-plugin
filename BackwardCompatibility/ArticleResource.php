<?php

declare(strict_types=1);

namespace OmikronFactfinder\BackwardCompatibility;

use Shopware\Components\Api\Resource\Article;
use Shopware\Components\Model\QueryBuilder;
use Shopware\Models\Shop\Shop;

class ArticleResource extends Article
{
    public function getList($offset = 0, $limit = 25, array $criteria = [], array $orderBy = [], array $options = [])
    {
        $this->checkPrivilege('read');

        /** @var QueryBuilder $builder */
        $builder = $this->getRepository()->createQueryBuilder('article')
            ->addSelect(['mainDetail', 'attribute'])
            ->addSelect('mainDetail.lastStock')
            ->leftJoin('article.mainDetail', 'mainDetail')
            ->leftJoin('mainDetail.attribute', 'attribute')
            ->addFilter($criteria)
            ->addOrderBy($orderBy)
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        $query = $builder->getQuery();

        $query->setHydrationMode($this->getResultMode());

        $paginator = $this->getManager()->createPaginator($query);

        // Returns the total count of the query
        $totalResult = $paginator->count();

        /**
         * @Deprecated Since 5.4, to be removed in 5.6
         *
         * To support Shopware <= 5.3 we make sure the lastStock-column of the main variant is being used instead of the
         * one on the product itself.
         */
        $products = array_map(function (array $val) {
            // $val[0]['lastStock'] = $val['lastStock']; // Error in SW 5.5 when hydrating on objects
            $val[0]->setLastStock($val['lastStock']);
            unset($val['lastStock']);

            return $val[0];
        }, $paginator->getIterator()->getArrayCopy());

        if ($this->getResultMode() === self::HYDRATE_ARRAY
            && isset($options['language'])
            && !empty($options['language'])) {
            /** @var Shop $shop */
            $shop = $this->findEntityByConditions(Shop::class, [
                ['id' => $options['language']],
            ]);

            foreach ($products as &$product) {
                $product = $this->translateArticle(
                    $product,
                    $shop
                );
            }
        }

        return ['data' => $products, 'total' => $totalResult];
    }
}
