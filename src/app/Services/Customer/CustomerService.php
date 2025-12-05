<?php

namespace App\Services\Customer;

use App\Models\Customer;
use App\DTOs\CreateTicketDto;
use App\Repositories\Contracts\CustomerRepositoryInterface;

class CustomerService
{
    public function __construct(
        private CustomerRepositoryInterface $customerRepository
    ) {}
    
    public function findOrCreate(CreateTicketDto $dto): Customer
    {  
        return $this->customerRepository->firstOrCreate([
            'email' => $dto->customerEmail,
            'phone' => $dto->customerPhone,
            'name' => $dto->customerName,
        ]);      
    }
}