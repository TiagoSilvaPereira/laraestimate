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
            ->orderBy('position')
            ->orderBy('created_at', 'desc');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    } 
    
    public function getShareUrlAttribute()
    {
        return route('estimates.view', $this);
    }

    public function getNextSectionPosition()
    {
        return $this->sections()->max('position') + 1;
    }

    public function saveSectionsPositions(?array $positions)
    {
        if(empty($positions)) return;

        foreach ($positions as $sectionId => $position) {
            $section = Section::find($sectionId);

            if($section) {
                $section->position = $position;
                $section->save();
            }
        }        
    }

    public function duplicate()
    {
        $estimateData = $this->treatDataForDuplication(
            $this->toArray()
        );

        $estimateData['name'] = $estimateData['name'] . ' Copy';
        $duplicated = Estimate::create($estimateData);
        
        $this->copySectionsTo($duplicated);

        return $duplicated;
    }

    protected function copySectionsTo(Estimate $duplicated)
    {
        $this->sections->each(function($section) use ($duplicated) {
            $sectionData = $this->treatDataForDuplication(
                $section->toArray()
            );

            $newSection = $duplicated->sections()->create($sectionData);
            $this->copySectionItems($section, $newSection);

        });
    }

    protected function copySectionItems(Section $from, Section $to)
    {
        $from->items->each(function($item) use ($to) {
            $itemData = $this->treatDataForDuplication(
                $item->toArray()
            );

            $to->items()->create($itemData);
        });
    }

    protected function treatDataForDuplication(array $data)
    {
        $removeKeys = ['id', 'created_at', 'updated_at', 'password'];
        
        return array_diff_key($data, array_flip($removeKeys));
    }
}
