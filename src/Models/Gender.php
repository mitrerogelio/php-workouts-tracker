<?php

declare(strict_types=1);

namespace App\Models;

enum Gender: string
{
    case male = 'male';
    case female = 'female';
}
