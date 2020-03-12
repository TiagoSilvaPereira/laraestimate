<?php

use App\Models\Estimate;
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
            factory(Estimate::class, 24)->create();
        }
    }
}
