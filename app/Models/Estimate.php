<?php

namespace App\Models;

use App\Models\Setting;
use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Estimate extends Model
{
    use SoftDeletes, HasUUID;

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
        'share_url',
        'logo_image',
        'currency_settings',
    ];

    public function sections()
    {
        return $this->hasMany(Section::class)
            ->with('items')
            ->orderBy('position')
            ->orderBy('created_at', 'desc');
    }

    public function items()
    {
        return $this->hasManyThrough(Item::class, Section::class);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'like', "%{$search}%");
    } 

    public function getLogoImageAttribute()
    {
        $setting = Setting::first();

        if($setting) {
            return $setting->getFirstMediaUrl('logo');
        }

        return null;
    }
    
    public function getShareUrlAttribute()
    {
        return route('estimates.view', $this);
    }

    public function getCurrencySettingsAttribute()
    {
        $setting = Setting::first();

        return [
            'symbol' => $this->currency_symbol ?? optional($setting)->currency_symbol,
            'decimal_separator' => $this->currency_decimal_separator ?? optional($setting)->currency_decimal_separator,
            'thousands_separator' => $this->currency_thousands_separator ?? optional($setting)->currency_thousands_separator,
        ];
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
