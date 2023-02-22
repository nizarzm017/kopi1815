<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resep extends Model
{
    use HasFactory;

    protected $table = 'resep';
    protected $guarded = [];

    public function item(){
        return $this->belongsTo(Item::class);
    }

    public function Menu(){
        return $this->belongsTo(Menu::class);
    }

    
}
