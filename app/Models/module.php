<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class module extends Model
{
    use HasFactory;
    protected $table = 'etudiant';
    protected $primaryKey = 'id_module';
    protected $guarded = [];
}
