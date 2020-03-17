<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'description',
        'duration',
        'price',
        'obligatory',
    ];

    protected $casts = [
        'obligatory' => 'boolean'
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}
