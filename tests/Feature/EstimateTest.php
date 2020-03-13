<?php

namespace Tests\Feature;

use App\Models\Estimate;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
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
}
