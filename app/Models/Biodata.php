<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable([
    'name',
    'nim',
    'email',
    'phone',
    'address',
    'photo_path',
    'professional_title',
    'summary',
    'experience',
    'education',
    'skills',
    'interests',
    'awards',
])]
class Biodata extends Model
{
    protected $table = 'Biodata_1021';
}
