<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Auth\AuthenticationException;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_a_guest_cannot_see_settings()
    {
        $this->expectException(AuthenticationException::class);

        $this->get(route('settings.edit'));
    }

    public function test_a_user_can_see_settings()
    {
        $this->signIn();

        $response = $this->get(route('settings.edit'));

        $response
            ->assertStatus(200);
    }

    public function test_a_user_can_edit_settings()
    {
        $this->signIn();
        
        /**
         * First access the settings page to create the default settings
         */
        $this->get(route('settings.edit'));

        $response = $this->put(route('settings.update'), [
            'currency_symbol' => 'R$'
        ]);

        $setting = Setting::firstOrFail();

        $this->assertEquals('R$', $setting->currency_symbol);
    }

}