<?php

namespace App\Articles;

use Illuminate\Pagination\LengthAwarePaginator;

interface SearchRepository
{
    public function search(string $query): LengthAwarePaginator;
}
