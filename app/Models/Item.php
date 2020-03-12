<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'description',
        'duration',
        'price'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
