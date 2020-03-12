<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estimate extends Model
{
    protected $fillable = [
        'name',
        'description',
        'expiration_date',
        'currency_symbol',
        'currency_decimal_separator',
        'currency_thousands_separator',
        'allows_to_select_items',
        'password',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class);
    }
}
