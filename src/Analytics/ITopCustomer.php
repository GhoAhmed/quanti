<?php 

namespace App\Analytics;

interface ITopCustomer
{
    public function calculateTopCustomers(array $customerData): array;
}