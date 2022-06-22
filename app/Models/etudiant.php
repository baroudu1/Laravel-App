<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class etudiant extends Model
{
    use HasFactory;
    protected $table = 'etudiant';
    protected $primaryKey = 'CNE';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $guarded = [];
}
