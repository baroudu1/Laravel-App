<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class enseignant extends Model
{
    use HasFactory;
    protected $table = 'enseignant';
    protected $primaryKey = 'CIN';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
