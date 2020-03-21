<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'use_name_as_title',
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
        return $this->hasMany(Section::class)
            ->with('items')
            ->orderBy('order')
            ->orderBy('created_at');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    } 
    
    public function getShareUrlAttribute()
    {
        return route('estimates.view', $this);
    }

    public function saveSectionsPositions(?array $positions)
    {
        if(empty($positions)) return;

        foreach ($positions as $sectionId => $position) {
            $section = Section::find($sectionId);

            if($section) {
                $section->order = $position;
                $section->save();
            }
        }        
    }
}
