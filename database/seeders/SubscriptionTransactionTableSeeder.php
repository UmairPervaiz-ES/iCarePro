<?php

namespace Database\Seeders;

use App\Models\Subscription\SubscriptionPermission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class SubscriptionTransactionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        SubscriptionPermission::truncate();

        $permissions = Permission::all();
        foreach($permissions as $permission){
            SubscriptionPermission::create(['subscription_id' => 1 , 'permission_id' => $permission->id]);
        }
    }
}
