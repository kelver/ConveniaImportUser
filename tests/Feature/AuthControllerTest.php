<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Passport\Passport;
use Tests\TestCase;

class AuthControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     */
    public function check_user_not_logged_cant_see_url()
    {
        $response = $this->json(
                        'GET',
                        '/api/user',
                        []
                    )->assertStatus(401);
    }

    /**
     * @test
     */
    public function check_user_logged_can_see_url()
    {
        $this->user = Passport::actingAs(
            User::factory()->create(),
            ['create-servers']
        );

        $response = $this->json(
            'GET',
            '/api/user',
            []
        )->assertStatus(200);
    }
}
