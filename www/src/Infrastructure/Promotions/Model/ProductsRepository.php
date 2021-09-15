<?php
namespace PromoTest\Infrastructure\Promotions\Model;

use Doctrine\DBAL\Connection;
use PromoTest\Domains\Promotions\Model\Entities\Product;
use PromoTest\Domains\Promotions\Model\ValueObjects\Category;
use PromoTest\Domains\Promotions\Model\ValueObjects\Discount;
use PromoTest\Domains\Promotions\Model\ValueObjects\Price;
use PromoTest\Domains\Promotions\Repositories\ProductsRepositoryInterface;
use function Doctrine\DBAL\Query\QueryBuilder;

class ProductsRepository implements ProductsRepositoryInterface
{
    private Connection $connection;

    public function __construct(
        Connection $connection
    )
    {
        $this->connection = $connection;
    }

    public function getProductsByCategoryWithPriceLessThan(
        ?string $category,
        ?int $priceLessThan,
        ?int $limit = null
    ): array {
        $queryBuilder = $this->connection->createQueryBuilder()
            ->select('p.id as price_id,
                p.sku as product_sku,
                p.name as product_name,
                c.id as category_id,
                c.name as category_name,
                pr.amount as price_amount,
                pr.currency as price_currency')
            ->from('products', 'p')
            ->join('p', 'prices', 'pr', 'p.id=pr.product_id')
            ->join('p', 'categories', 'c', 'p.category_id=c.id')
            ->where('NOW() BETWEEN pr.start_at and pr.end_at');

        if (!is_null($category)) {
            $queryBuilder->andWhere('c.name=:category')
                ->setParameter('category', $category);
        }

        if (!is_null($priceLessThan)) {
            $queryBuilder->andWhere('pr.amount <=:price')
                ->setParameter('price', $priceLessThan);
        }

        if (!is_null($limit)) {
            $queryBuilder->setMaxResults($limit);
        }

        $productsQueryResults = $queryBuilder->executeQuery()->fetchAllAssociative();

        $products = [];
        foreach ($productsQueryResults as $productsQueryResult) {
            $discountQueryResults = $this->connection->createQueryBuilder()
                ->select('d.percentage as discount_percentage')
                ->from('discounts', 'd')
                ->where('NOW() BETWEEN d.start_at and d.end_at')
                ->andWhere(
                    $queryBuilder->expr()->or(
                        $queryBuilder->expr()->eq('d.product_id', ':product_id'),
                        $queryBuilder->expr()->eq('d.category_id', ':category_id'),
                    )
                )
                ->setParameters(
                    [
                        'product_id' => $productsQueryResult['id'],
                        'category_id' => $productsQueryResult['category_id']
                    ]
                )
                ->executeQuery()
                ->fetchAllAssociative();

            $discounts = [];
            foreach ($discountQueryResults as $discountQueryResult) {
                $discounts[] = new Discount(
                    $discountQueryResult['discount_percentage']
                );
            }

            $products[] = new Product(
                $productsQueryResult['product_sku'],
                $productsQueryResult['product_name'],
                new Category(
                    $productsQueryResult['category_id'],
                    $productsQueryResult['category_name']
                ),
                new Price(
                    $productsQueryResult['price_amount'],
                    $productsQueryResult['price_currency']
                ),
                $discounts
            );
        }

        return $products;
    }
}
