<?php

namespace App\Repositories\Contracts;

use App\Models\Customer;

interface CustomerRepositoryInterface
{
    public function findByPhone(string $phone): ?Customer;

    public function findByEmail(string $email): ?Customer;

    public function updateOrCreate(array $attributes): Customer;
}