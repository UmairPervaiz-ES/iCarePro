<?php

namespace Tests\Feature;

use App\Models\Practice\Practice;
use App\Models\Practice\PracticeAddress;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Laravel\Passport\Passport;
use Tests\TestCase;


class NoAuthDataTest extends TestCase
{
    /**
     * Get Countries
     *
     * @return void
     */
    public function test_get_countries()
    {
        $response = $this->getJson('api/countries');

        $response->assertStatus(200);
    }
    /**
     * Get States
     *
     * @return void
     */
    public function test_get_states()
    {
        $response = $this->getJson('api/states/231');

        $response->assertStatus(200);
    }
    /**
     * Get cities
     *
     * @return void
     */
    public function test_get_cities()
    {
        $response = $this->getJson('api/cities/3391');

        $response->assertStatus(200);
    }
    /**
     * Get specializations
     *
     * @return void
     */
    public function test_get_doctor_specializations()
    {
        $response = $this->getJson('api/doctor-specializations');

        $response->assertStatus(200);
    }

    /**
     * Test Successful Registration
     *
     * @return void
     */
    public function testSuccessfulRegistration()
    {
        $userData = [
            'practice_name' => $this->faker->name,
            'country_code' => $this->faker->countryCode,
            'phone_number' => $this->faker->numerify('##############'),
            'first_name' => $this->faker->firstName,
            'middle_name' => 'middle',
            'last_name'  => $this->faker->lastName,
            'email'      => $this->faker->email,
            'designation' => $this->faker->jobTitle,
        ];

        $response = $this->postJson('api/initial-practice', $userData);
        $response->assertStatus(201)
            ->assertJsonStructure(
                [
                    'success',
                    'message',
                    'data' =>  [
                        "practice_name",
                        "country_code",
                        "phone_number",
                        "first_name",
                        "middle_name",
                        "last_name",
                        "email",
                        "designation",
                        "updated_at",
                        "created_at",
                    ],
                ]
            );
    }

    /**
     * Test Practice Login
     *
     * @return void
     */
    public function test_practice_login()
    {
        // Assign Permission
        $this->assignPermission($this->headers['practice']);
      
        $userData = [
            'email'    => 'sample@test.com',
            'password' => 'sample123',
        ];

        $response = $this->postJson('api/practice/login', $userData);
        $response->assertStatus(200);
        $this->assertAuthenticated('practice');
    }
}
