<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Permission::truncate();

        $operations = ['create', 'view', 'update', 'delete' ];

        $data = [];

        $now = Carbon::now()->toDateTime();

        // Permissions for Practice

        $practicePermissions = ['patient', 'doctor', 'subscription', 'e prescription', 'appointment', 'billing', 'insurance', 'consent-form', 'slots', 'staff', 'role', 'department' ];

        $practiceGuardName = 'practice-api';

        foreach ($practicePermissions as $practicePermission) {
            foreach ($operations as $operation) {
                $data[] = [ "name" => "$practicePermission-$operation", "guard_name" => "$practiceGuardName", "created_at" => $now ];
            }
        }

        // Permissions for Doctor

        $doctorPermissions = ['patient', 'doctor', 'e prescription', 'appointment', 'billing', 'vital', 'slots', 'staff', 'role', 'department' ];

        $doctorGuardName = 'doctor-api';

        foreach($doctorPermissions as $doctorPermission){
            foreach($operations as $operation){
                $data[] = [ "name" => "$doctorPermission-$operation", "guard_name" => "$doctorGuardName", "created_at" => $now ];
            }
        }

        // Permissions for practice Staff

        $staffPermissions = ['patient', 'doctor', 'e prescription', 'appointment' ,'insurance', 'billing', 'vital' ,'slots', 'staff', 'role', 'department' ];

        $staffGuardName = 'api';

        foreach($staffPermissions as $staffPermission){
            foreach($operations as $operation){
                $data[] = [ "name" => "$staffPermission-$operation", "guard_name" => "$staffGuardName", "created_at" => $now ];
            }
        }

        // Permissions for Patient

        $patientPermissions = ['patient', 'e prescription', 'appointment', 'billing', 'slots' ];

        $patientGuardName = 'patient-api';

        foreach($patientPermissions as $patientPermission){
            foreach($operations as $operation){
                $data[] = [ "name" => "$patientPermission-$operation", "guard_name" => "$patientGuardName", "created_at" => $now ];
            }
        }

        Permission::insert($data);

        // Adding below query because patient can only view doctor
        Permission::create([
            'name' => 'doctor-view',
            'guard_name' => $patientGuardName,
        ]);

        $doctor = Role::where('name', 'Doctor')->first();
        $permissions = Permission::where(['guard_name' => 'doctor-api'])->get();
        $doctor->syncPermissions($permissions);
    }
}
