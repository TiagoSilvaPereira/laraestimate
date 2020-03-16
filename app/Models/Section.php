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

    public function estimate()
    {
        return $this->belongsTo(Estimate::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function saveItems(array $items)
    {
        $this->items()->delete();

        collect($items)->each(function($item) {
            $this->items()->create($item);
        });
    }
}
