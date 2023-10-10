<?php

namespace App\CSV;
use Symfony\Component\HttpFoundation\Response;

interface ICsvData
{
    public function generateCSVFile(array $data): string;
    public function createCSVResponse(string $csvData): Response;
}