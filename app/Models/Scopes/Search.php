<?php

namespace App\Models\Scopes;

trait Search {
    
    /**
     * Adds a scope to search the table based on the
     * $searchableFields array inside the model
     *
     * @param [type] $query
     * @param [type] $search
     * @return void
     */
    public function scopeSearch($query, $search)
    {
        if(isset($this->searchableFields) && count($this->searchableFields)) {
            
            $query->where(function($query) use ($search){
                foreach ($this->searchableFields as $field) {
                    $query->orWhere($field, 'like', "%{$search}%");
                }
            });

        }

        return $query;
    }

    /**
     * Search paginated items ordering by ID descending
     *
     * @param string $search
     * @param integer $paginationQuantity
     * @return void
     */
    public static function searchLatestPaginated(string $search, $paginationQuantity = 10)
    {
        return self::search($search)
            ->orderBy('updated_at', 'desc')
            ->paginate($paginationQuantity);
    }

}