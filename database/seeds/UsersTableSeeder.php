<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        if(app()->environment() == 'local') {
            factory(User::class, 1)->create([
                'email' => 'admin@admin.com'
            ]);
        }
    }
}
