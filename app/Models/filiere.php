<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class filiere extends Model
{
    use HasFactory;
    protected $table = 'filière';
    protected $primaryKey = 'id_filiere';
    protected $guarded = [];
}
