<?php

namespace App\DataBaseOrder;

interface IDataOrder
{
    public function getProcessedOrders(): array;
}