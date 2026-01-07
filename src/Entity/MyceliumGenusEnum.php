<?php

declare(strict_types=1);

namespace App\Entity;

enum MyceliumGenusEnum: string
{
    case GENUS_AMANITA ='amanita';
    case GENUS_BOLETUS ='boletus';
    case GENUS_CANTHARELLUS ='cantharellus';
    case GENUS_MORCHELLA ='morchella';
    case GENUS_PLEUROTUS ='pleurotus';
    case GENUS_XEROCOMUS ='xerocomus';
}
