<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
    use SoftDeletes;

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

    protected $appends = [
        'share_url'
    ];

    public function sections()
    {
        return $this->hasMany(Section::class)->with('items');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function($query) use ($search) {
            return $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    } 
    
    public function getShareUrlAttribute()
    {
        return route('estimates.view', $this);
    }
}
