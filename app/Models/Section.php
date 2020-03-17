<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    protected $fillable = [
        'title',
        'text',
        'type',
        'total'
    ];

    protected $appends = [
        'presentable_text'
    ];

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function getPresentableTextAttribute()
    {
        $text = $this->text;

        $text = str_replace('*TOTAL_PRICE*', '<span class="total-price"></span>', $text);
        $text = str_replace('*TOTAL_SELECTED_PRICE*', '<span class="total-selected-price"><span/>', $text);

        return $text;
    }

    public function saveItems(array $items)
    {
        $this->items()->delete();

        collect($items)->each(function($item) {
            $this->items()->create($item);
        });
    }
}
