<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Estimate;
use App\Models\Section;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SectionTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_user_can_change_sections_order_position()
    {
        $this->signIn();

        $estimate = factory(Estimate::class)->create();
        
        $sections = [];

        for($i = 0; $i < 3; $i++) {
            $sectionData = factory(Section::class)->make()->toArray();
            $sections[] = $estimate->sections()->create($sectionData);
        }

        $this->assertEquals(1, $sections[0]->position);
        $this->assertEquals(2, $sections[1]->position);
        $this->assertEquals(3, $sections[2]->position);

        $response = $this->put(route('estimates.update', $estimate), [
            'name' => $estimate->name,
            'sections_positions' => [
                $sections[0]->id => 2,
                $sections[2]->id => 1,
                $sections[1]->id => 3,
            ]
        ]);

        $this->assertEquals(2, $sections[0]->fresh()->position);
        $this->assertEquals(3, $sections[1]->fresh()->position);
        $this->assertEquals(1, $sections[2]->fresh()->position);
    }
}
