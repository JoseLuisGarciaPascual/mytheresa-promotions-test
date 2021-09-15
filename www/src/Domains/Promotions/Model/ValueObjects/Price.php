<?php
namespace PromoTest\Domains\Promotions\Model\ValueObjects;

class Price
{
    private int $amount;
    private string $currency;

    public function __construct(int $amount, string $currency)
    {
        $this->setAmount($amount);
        $this->setCurrency($currency);
    }

    private function setAmount(int $amount): void {
        $this->amount = $amount;
    }

    private function setCurrency(string $currency): void {
        $this->currency = $currency;
    }

    public function getAmount(): int {
        return $this->amount;
    }

    public function getCurrency(): string {
        return $this->currency;
    }
}

