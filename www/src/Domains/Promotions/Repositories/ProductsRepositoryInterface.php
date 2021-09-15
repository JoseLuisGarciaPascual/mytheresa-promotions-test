<?php
namespace PromoTest\Domains\Promotions\Repositories;

use PromoTest\Domains\Promotions\Model\Entities\Product;

interface ProductsRepositoryInterface
{
    /**
     * @return Product[]
     */
    public function getProductsByCategoryWithPriceLessThan(?string $category, ?int $priceLessThan, ?int $limit = null): array;
}
