<?php

namespace Tests\Browser;

use App\Models\User;
use App\Models\Section;
use Tests\DuskTestCase;
use App\Models\Estimate;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class EstimateViewerTest extends DuskTestCase
{
    use DatabaseMigrations;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = factory(User::class)->create([
            'email' => 'admin@admin.com',
        ]);
    }

    public function test_it_shows_the_estimate_name_as_title()
    {
        $this->browse(function (Browser $browser) {
            
            $estimate = $this->createEstimate();

            $browser
                ->loginAs($this->user)
                ->visit(route('estimates.show', $estimate))
                ->waitFor('#estimateDocument')
                ->assertSee($estimate->name);
        });
    }

    public function test_it_dont_shows_the_estimate_name_as_title_as_set()
    {
        $estimate = $this->createEstimate([
            'use_name_as_title' => false
        ]);
        
        $this->browse(function (Browser $browser) use ($estimate) {
            $browser->loginAs($this->user)
                ->visit(route('estimates.show', $estimate));

            $browser->waitFor('#estimateDocument')
                ->assertSee('Print')
                ->assertSee('Share')
                ->assertDontSee($estimate->name);
        });
    }

    public function test_it_shows_correct_estimate_total_price()
    {
        $estimate = $this->createEstimate();
        
        $this->browse(function (Browser $browser) use ($estimate) {
            $browser->loginAs($this->user)
                ->visit(route('estimates.show', $estimate));

            $pricesSum = $estimate->items->reduce(function ($carry, $item) {
                return $carry + $item->price;
            }, 0);

            $browser->waitFor('#estimateDocument')
                ->assertSee('$ ' . number_format($pricesSum, 2));
        });
    }

    public function test_it_shows_correct_estimate_total_selected_price()
    {
        $estimate = $this->createEstimate();
        
        $this->browse(function (Browser $browser) use ($estimate) {
            $browser->loginAs($this->user)
                ->visit(route('estimates.show', $estimate));

            $pricesSum = $estimate->items->reduce(function ($carry, $item) {
                return $carry + $item->price;
            }, 0);

            $selectedPrice = $pricesSum - $estimate->items()->first()->price;

            $browser->waitFor('#estimateDocument')
                ->click('.check-item')
                ->pause('300')
                ->assertSee('$ ' . number_format($selectedPrice, 2));
        });
    }

    protected function createEstimate($data = [])
    {
        $estimate = factory(Estimate::class)->create($data);
        
        for($i = 0; $i < 3; $i++) {
            $sectionData = factory(Section::class)->make()->toArray();
            $estimate->sections()->create($sectionData);
        }

        // Creating Prices section
        $pricesSectionData = factory(Section::class)->make([
            'type' => 'prices',
            'text' => 'Total is *TOTAL_PRICE* and total selected is *TOTAL_SELECTED_PRICE*'
        ])->toArray();

        $section = $estimate->sections()->create($pricesSectionData);
        
        $section->items()->create(['description' => 'Item 01', 'price' => 13.58]);
        $section->items()->create(['description' => 'Item 02', 'price' => 15.79]);
        $section->items()->create(['description' => 'Item 03', 'price' => 18.20]);

        return $estimate;
    }
}
