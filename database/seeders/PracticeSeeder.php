<?php

namespace Database\Seeders;

use App\Models\Practice\Practice;
use App\Models\Practice\PracticeAddress;
use App\Models\Subscription\SubscriptionPermission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class PracticeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Practice::truncate();

       $practice = Practice::create([
            'practice_registration_request_id' => 1,
            'subscription_id'=>1,
            'practice_key'=>'practice-1',
            'email'=> "practice@gmail.com",
            'password' => Hash::make('123456789'), // <---- check this
            'is_password_reset'=> 1,
            'tax_id'=> 'tax_id',
            'practice_npi'=> 'practice_npi',
            'practice_taxonomy'=> 'practice_taxonomy',
            'facility_id'=> 'facility_id',
            'oid'=> 'oid',
            'clia_number'=> 'clia_number',
            'privacy_policy'=> 'privacy_policy',
            'status'=> 'Accepted',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

       PracticeAddress::create([
            'practice_id' => 1,
            'address_line_1'=>'Johar town',
            'address_line_2'=>'Lahore town',
            'country_id'=>1,
            'state_id'=>1,
            'city_id'=>1,
            'zip_code'=>'54872',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);

        $role = Role::create(['guard_name' => 'practice-api', 'name' => 'practice-1@Admin']);

        $practice->assignRole($role);

        $permissions = SubscriptionPermission::where('subscription_id', 1)->pluck('permission_id')->toArray();

        foreach ($permissions as $permission) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission,
            ]);
        }

        $practice = Practice::create([
            'practice_registration_request_id' => 2,
            'subscription_id'=>1,
            'practice_key'=>'practice-2',
            'email'=> "admin@icarepro.com",
            'password' => Hash::make('0Bj2a2Kyeq'), // <---- check this
            'is_password_reset'=> 1,
            'tax_id'=> 'tax_id',
            'practice_npi'=> 'practice_npi',
            'practice_taxonomy'=> 'practice_taxonomy',
            'facility_id'=> 'facility_id',
            'oid'=> 'oid',
            'clia_number'=> 'clia_number',
            'privacy_policy'=> 'privacy_policy',
            'status'=> 'Accepted',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        PracticeAddress::create([
            'practice_id' => 2,
            'address_line_1'=>'Johar town',
            'address_line_2'=>'Lahore town',
            'country_id'=>1,
            'state_id'=>1,
            'city_id'=>1,
            'zip_code'=>'54872',
            'created_at' => \Carbon\Carbon::now(),
            'updated_at' => \Carbon\Carbon::now(),
        ]);
        $role = Role::create(['guard_name' => 'practice-api', 'name' => 'practice-2@Admin']);

        $practice->assignRole($role);

        $permissions = SubscriptionPermission::where('subscription_id', 1)->pluck('permission_id')->toArray();

        foreach ($permissions as $permission) {
            DB::table('role_has_permissions')->insert([
                'role_id' => $role->id,
                'permission_id' => $permission,
            ]);
        }
    }
}
