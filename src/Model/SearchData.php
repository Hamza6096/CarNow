<?php

declare(strict_types=1);

namespace App\Model;

use App\Entity\Category;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\FormTypeInterface;


class SearchData
{

    public string $q = '';

    public array $categories = [];

    public null|Integer $max;
    public null|Integer $min;

}