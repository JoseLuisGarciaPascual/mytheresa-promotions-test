<?php
namespace PromoTest\Domains\Promotions\DTOs;

class GetProductsWithDiscountUseCaseDTO
{
    private ?string $category = null;
    private ?int $priceIsLessThan = null;

    public function __construct(?string $category, ?int $priceIsLessThan)
    {
        $this->setCategory($category);
        $this->setPriceIsLessThan($priceIsLessThan);
    }

    private function setCategory(?string $category):void
    {
        $this->category = $category;
    }

    private function setPriceIsLessThan(?int $priceIsLessThan)
    {
        $this->priceIsLessThan = $priceIsLessThan;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function getPriceIsLessThan(): ?int
    {
        return $this->priceIsLessThan;
    }
}
