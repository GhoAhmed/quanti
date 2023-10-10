<?php 

namespace App\Analytics;

interface IQuantilesData
{
    public function calculateQuantilesData(array $customerData): array;
}