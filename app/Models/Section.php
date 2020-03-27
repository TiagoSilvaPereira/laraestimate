<?php

namespace App\Models;

use App\Traits\HasUUID;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use HasUUID;
    
    protected $fillable = [
        'text',
        'type',
    ];

    protected $appends = [
        'presentable_text'
    ];

    public static function boot()
    {
        parent::boot();

        self::creating(function($section) {
            $section->position = $section->estimate->getNextSectionPosition();
        });
    }

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class)
            ->orderBy('position');
    }

    public function getPresentableTextAttribute()
    {
        $text = $this->text;

        $text = str_replace('*TOTAL_PRICE*', '<span class="total-calc-price"></span>', $text);
        $text = str_replace('*TOTAL_SELECTED_PRICE*', '<span class="total-selected-calc-price"></span>', $text);

        return $text;
    }

    public function saveItems(array $items)
    {
        $this->items()->delete();

        collect($items)->each(function($item) {
            $this->items()->create($item);
        });
    }

    public function getNextItemPosition()
    {
        return $this->items()->max('position') + 1;
    }

    public function saveItemsPositions(?array $positions)
    {
        if(empty($positions)) return;

        foreach ($positions as $itemId => $position) {
            $item = Item::find($sectionId);

            if($item) {
                $item->position = $position;
                $item->save();
            }
        }        
    }
}
