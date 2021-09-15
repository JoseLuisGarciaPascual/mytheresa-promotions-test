<?php
namespace PromoTest\Domains\Promotions\Model\Entities;

use PromoTest\Domains\Promotions\Model\ValueObjects\Category;
use PromoTest\Domains\Promotions\Model\ValueObjects\Discount;
use PromoTest\Domains\Promotions\Model\ValueObjects\Price;

class Product
{
    private string $sku;
    private string $name;
    private Category $category;
    private Price $price;
    /**
     * @var Discount[]
     */
    private array $discounts;

    public function __construct(string $sku, string $name, Category $category, Price $price, array $discounts)
    {
        $this->setSku($sku);
        $this->setName($name);
        $this->setCategory($category);
        $this->setPrice($price);
        $this->setDiscounts($discounts);
    }

    public function setSku(string $sku): void
    {
        $this->sku = $sku;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setCategory(Category $category): void
    {
        $this->category = $category;
    }

    public function setPrice(Price $price): void
    {
        $this->price = $price;
    }

    /**
     * @param Discount[] $discounts
     */
    public function setDiscounts(array $discounts): void
    {
        $this->discounts = $discounts;
    }

    public function getSku(): string
    {
        return $this->sku;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCategory(): Category
    {
        return $this->category;
    }

    public function getPrice(): Price
    {
        return $this->price;
    }

    function getDiscounts(): array
    {
        return $this->discounts;
    }

    public function getBestDiscountPercentage(): ?int {
        $bestDiscount = null;
        foreach ($this->discounts as $discount) {
            $bestDiscount = ($discount->getDiscountPercentage() > $bestDiscount) ?
                $discount->getDiscountPercentage() : $bestDiscount;
        }

        return $bestDiscount;
    }

    public function getBestDiscountedPriceAmount(): ?int
    {
        if (is_null($this->getBestDiscountPercentage())) {
            return $this->price->getAmount();
        } else {
            $amountToDiscount = (int)floor($this->price->getAmount() * ($this->getBestDiscountPercentage() / 100));
            return $this->price->getAmount() - $amountToDiscount;
        }
    }
}
