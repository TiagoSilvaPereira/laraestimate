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
            ->orderBy('created_at', 'asc');
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
