<?php

use App\Models\Estimate;
use App\Models\Item;
use App\Models\Section;
use Illuminate\Database\Seeder;

class EstimatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(app()->environment() == 'local') {
            factory(Estimate::class, 24)->create()->each(function($estimate) {
                
                $textSectionData = factory(Section::class)->make()->toArray();
                unset($textSectionData['presentable_text']);
                $estimate->sections()->create($textSectionData);

                $pricesSectionData = factory(Section::class)->make([
                    'type' => 'prices'
                ])->toArray();
                unset($pricesSectionData['presentable_text']);

                $pricesSection = $estimate->sections()->create($pricesSectionData);
                
                for ($i=0; $i < 3; $i++) { 
                    $itemData = factory(Item::class)->make()->toArray();
                    $pricesSection->items()->create($itemData);
                }

            });
        }
    }
}
