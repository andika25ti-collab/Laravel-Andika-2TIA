<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Attributes\Fillable;

#[Fillable(['name', 'email', 'question'])]
class Question extends Model
{
    /** @use HasFactory<Question> */
    use HasFactory;

    protected $keyType = 'int';

    protected $casts = [
        'id' => 'integer',
    ];
}
