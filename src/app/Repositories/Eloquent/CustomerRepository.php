<?php

namespace App\Repositories\Eloquent;

use App\Models\Customer;
use App\Repositories\Contracts\CustomerRepositoryInterface;

/**
 * Eloquent implementation of CustomerRepository.
 * 
 * Handles all database operations for Customer model.
 */
class CustomerRepository implements CustomerRepositoryInterface
{
    /**
     * Find a customer by phone number.
     *
     * @param string $phone Phone number in E.164 format
     * @return Customer|null The customer or null if not found
     */
    public function findByPhone(string $phone): ?Customer
    {
        return Customer::where('phone', $phone)->first();
    }

    /**
     * Find a customer by email address.
     *
     * @param string $email Email address to search
     * @return Customer|null The customer or null if not found
     */
    public function findByEmail(string $email): ?Customer
    {
        return Customer::where('email', $email)->first();
    }

    /**
     * Find existing customer by email or create a new one.
     * 
     * Uses email as the unique identifier. If customer exists,
     * updates their name and phone with new values.
     *
     * @param array{email: string, name: string, phone: string} $attributes Customer data
     * @return Customer The found/updated or newly created customer
     */
    public function findOrCreateCustomer(array $attributes): Customer
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
