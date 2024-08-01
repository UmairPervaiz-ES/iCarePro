<?php

namespace Tests;

use App\Models\Practice\Practice;
use App\Models\Practice\PracticeAddress;
use Faker\Factory;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\CountrySeeder;
use App\Models\Subscription\SubscriptionPermission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseMigrations;

    public function setUp(): void
    {
        parent::setUp();
        // Artisan::call('migrate:refresh');
        // $this->artisan('db:seed');
        Artisan::call('passport:install');
        $this->faker = Factory::create();
        $this->headers = $this->setHeader();
    }

    public function createPractice()
    {
        $this->seed(CountrySeeder::class);

        $practice =  Practice::factory()->create(
            ['email' => 'sample@test.com',
            'password' => bcrypt('sample123'),
            'practice_registration_request_id' => 1,
            'subscription_id' => 1,
            'practice_key' => 'practice-3',
            'tax_id' => '1',
            'practice_npi' => '1',
            'practice_taxonomy' => '1',
            'facility_id' => '1',
            'oid' => '1',
            'clia_number' => '1',
            'privacy_policy' => '1',
            ]
        );
        PracticeAddress::create([
            'practice_id'=>1,
            'country_id'=>231,
            'address_line_1'=> 'Shanghai',
            'address_line_2'=> 'Chandni chowk',
        ]);
        return $practice;
    }

    public function assignPermission($practice)
    {
        $permissions = SubscriptionPermission::where('subscription_id', 1)->pluck('permission_id')->toArray();
        $role = Role::create(['guard_name' => 'practice-api', 'name' => 'practice-' . $practice->id . '@Admin']);
        $practice->assignRole($role);
        foreach ($permissions as $permission) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission,
            ]);
        }
    }

    public function setHeader()
    {
        // Create Practice
        $practice = $this->createPractice();
        // Set Token
        $token = $practice->createToken('practice')->accessToken;
        $header = [ 'Authorization' => 'Bearer '. $token];
        return ['practice'=>$practice,'header'=>$header];

    }
}
