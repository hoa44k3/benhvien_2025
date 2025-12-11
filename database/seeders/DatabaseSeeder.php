<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Role;
class DatabaseSeeder extends Seeder
{
 
       public function run(): void
{
    // ✅ Gộp tất cả roles đúng theo hệ thống
    $roles = ['admin', 'doctor', 'nurse', 'pharmacist', 'receptionist', 'patient'];

    foreach ($roles as $roleName) {
        Role::firstOrCreate(['name' => $roleName]);
    }

    // ✅ Tạo tài khoản admin
    $admin = User::firstOrCreate(
        ['email' => 'admin@example.com'],
        [
            'name' => 'Administrator',
            'password' => Hash::make('123456'),
        ]
    );

    $adminRole = Role::where('name', 'admin')->first();
    if ($adminRole && !$admin->roles()->where('role_id', $adminRole->id)->exists()) {
        $admin->roles()->attach($adminRole->id);
    }

    // ✅ Doctor
    $doctor = User::firstOrCreate(
        ['email' => 'doctor@example.com'],
        ['name' => 'Doctor John', 'password' => Hash::make('123456')]
    );
    $doctor->roles()->sync([Role::where('name', 'doctor')->first()->id]);

    // ✅ Nurse
    $nurse = User::firstOrCreate(
        ['email' => 'nurse@example.com'],
        ['name' => 'Nurse Mary', 'password' => Hash::make('123456')]
    );
    $nurse->roles()->sync([Role::where('name', 'nurse')->first()->id]);

    // ✅ Pharmacist
    $pharmacist = User::firstOrCreate(
        ['email' => 'pharmacist@example.com'],
        ['name' => 'Pharmacist Linh', 'password' => Hash::make('123456')]
    );
    $pharmacist->roles()->sync([Role::where('name', 'pharmacist')->first()->id]);

    // ✅ Receptionist
    $receptionist = User::firstOrCreate(
        ['email' => 'receptionist@example.com'],
        ['name' => 'Receptionist Phúc', 'password' => Hash::make('123456')]
    );
    $receptionist->roles()->sync([Role::where('name', 'receptionist')->first()->id]);
}


}
