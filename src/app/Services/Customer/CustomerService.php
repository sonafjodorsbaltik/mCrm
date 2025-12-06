<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\DTOs\CreateTicketDto;
use App\Repositories\Contracts\CustomerRepositoryInterface;

/**
 * Service for managing customer records.
 * 
 * Handles customer creation and updates during ticket submission.
 */
class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}
    
    /**
     * Find existing customer by email or create a new one.
     * 
     * If a customer with the same email exists, their phone and name
     * will be updated with the new values from the DTO.
     *
     * @param CreateTicketDto $dto Data containing customer information
     * @return Customer The found or newly created customer
     */
    public function findOrCreate(CreateTicketDto $dto): Customer
    {  
        return $this->customerRepository->findOrCreateCustomer([
            'email' => $dto->customerEmail,
            'phone' => $dto->customerPhone,
            'name' => $dto->customerName,
        ]);      
    }
}