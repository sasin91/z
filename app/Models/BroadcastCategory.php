<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class BroadcastCategory extends Model
{
    use HasFactory, HasTranslations;

    protected array $translatable = ['name'];
}
