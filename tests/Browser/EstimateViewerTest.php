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
            
            $browser->visit('/login')
                ->type('email', $this->user->email)
                ->type('password', 'password')
                ->press('Login');
            

            $browser
                ->visit(route('estimates.show', $estimate))
                ->waitFor('#estimateDocument')
                ->assertSee($estimate->name);
        });
    }

    // public function test_it_dont_shows_the_estimate_name_as_title_as_set()
    // {
    //     $estimate = $this->createEstimate(false);
        
    //     $this->browse(function (Browser $browser) use ($estimate) {
    //         $browser->loginAs($this->user)
    //             ->visit(route('estimates.show', $estimate));

    //         $browser->waitFor('#estimateDocument')
    //             ->assertSee('Print')
    //             ->assertSee('Share')
    //             ->assertDontSee($estimate->name);
    //     });
    // }

    protected function createEstimate($nameAsTitle = true)
    {
        $estimate = factory(Estimate::class)->create([
            'use_name_as_title' => $nameAsTitle
        ]);
        
        for($i = 0; $i < 3; $i++) {
            $sectionData = factory(Section::class)->make()->toArray();
            $estimate->sections()->create($sectionData);
        }

        return $estimate;
    }
}
