<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    use HasFactory;
    // app/Models/Translation.php
protected $fillable = ['key', 'value', 'locale', 'tag'];

}
