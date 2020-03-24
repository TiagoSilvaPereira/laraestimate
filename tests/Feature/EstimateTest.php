<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Estimate;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EstimateTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_list_estimates()
    {
        $this->expectException(AuthenticationException::class);

        factory(Estimate::class, 5)->create();

        $this->get(route('estimates.index'));
    }

    public function test_a_user_can_list_estimates()
    {
        $this->signIn();
        $estimates = factory(Estimate::class, 5)->create();

        $response = $this->get(route('estimates.index'));

        $response
            ->assertStatus(200)
            ->assertSee($estimates[0]->name);
    }

    public function test_a_user_can_search_estimates()
    {
        $this->signIn();
        $estimates = factory(Estimate::class, 20)->create();

        $response = $this->get(route('estimates.index', ['search' => $estimates[0]->name]));

        $response
            ->assertStatus(200)
            ->assertSee($estimates[0]->name)
            ->assertDontSee($estimates[1]->name);
    }

    public function test_a_user_can_update_estimates()
    {
        $this->signIn();

        $estimate = factory(Estimate::class)->create();

        $response = $this->put(route('estimates.update', $estimate), [
            'name' => $estimate->name . ' Edited',
            'sections_positions' => []
        ]);
        
        $this->assertEquals($estimate->name . ' Edited', $estimate->fresh()->name);
    }
}
