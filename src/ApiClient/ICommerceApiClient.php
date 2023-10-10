<?php

namespace App\ApiClient;

interface ICommerceApiClient
{
    public function getNewOrders(): array;
}