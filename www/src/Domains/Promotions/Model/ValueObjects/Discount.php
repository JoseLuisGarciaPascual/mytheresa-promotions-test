<?php
namespace PromoTest\Domains\Promotions\Model\ValueObjects;

class Discount
{
    private int $discountPercentage;

    public function __construct(int $discountPercentage)
    {
        $this->setDiscountPercentage($discountPercentage);
    }

    private function setDiscountPercentage(int $discountPercentage): void
    {
        $this->discountPercentage = $discountPercentage;
    }

    public function getDiscountPercentage(): int {
        return $this->discountPercentage;
    }
}