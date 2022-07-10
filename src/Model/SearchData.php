<?php

declare(strict_types=1);

namespace App\Model;



class SearchData
{

    public null|string $q = '';
    public null|string $city = '';

    public array  $categories = [];

    public null|int $max;
    public null|int $min;

}