<?php

namespace App\Repositories\Contracts;

use App\Models\Customer;

/**
 * Contract for Customer repository operations.
 * 
 * Defines methods for finding and managing customer records.
 */
interface CustomerRepositoryInterface
{
    /**
     * Find a customer by phone number.
     */
    public function findByPhone(string $phone): ?Customer;

    /**
     * Find a customer by email address.
     */
    public function findByEmail(string $email): ?Customer;

    /**
     * Find existing customer by email or create a new one.
     * 
     * @param array{email: string, name: string, phone: string} $attributes
     */
    public function findOrCreateCustomer(array $attributes): Customer;
}