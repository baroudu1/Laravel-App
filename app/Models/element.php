<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class element extends Model
{
    use HasFactory;
    protected $table = 'elements';
    protected $primaryKey = 'id_element';
    protected $guarded = [];
}
