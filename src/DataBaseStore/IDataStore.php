<?php

namespace App\DataBaseStore;

interface IDataStore
{
    public function storeNewOrdersInDatabase(array $data): void;
}