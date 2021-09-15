<?php
namespace PromoTest\Domains\Promotions\DTOs;

class GetProductsWithDiscountUseCaseResponseDTO
{
    /**
     * @var \stdClass[]
     */
    private array $products;

    public function addProduct(\stdClass $product): void
    {
        $this->products[] = $product;
    }

    /**
     * @return \stdClass[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}
