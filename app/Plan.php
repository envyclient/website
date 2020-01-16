<?php

namespace App;

use Bavix\Wallet\Interfaces\Customer;
use Bavix\Wallet\Interfaces\Product;
use Bavix\Wallet\Traits\HasWallet;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model implements Product
{
    use HasWallet;

    public function canBuy(Customer $customer, int $quantity = 1, bool $force = null): bool
    {
        return true;
    }

    public function getAmountProduct(Customer $customer): int
    {
        return $this->price;
    }

    public function getMetaProduct(): ?array
    {
        return [
            'title' => $this->title,
            'description' => 'Purchase of Product #' . $this->id,
        ];
    }

    public function getUniqueId(): string
    {
        return (string)$this->getKey();
    }
}
