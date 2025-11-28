<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerRepository implements CustomerRepositoryInterface
{
    public function findByPhone(string $phone): ?Customer
    {
        return Customer::where('phone', $phone)->first();
    }

    public function findByEmail(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }

    public function firstOrCreate(array $attributes): Customer
    {
        return Customer::updateOrCreate(
            ['email' => $attributes['email']],
            [
                'name' => $attributes['name'],
                'phone' => $attributes['phone'],
            ]
        );
    }
}
