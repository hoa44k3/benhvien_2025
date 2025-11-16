<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         $admin = User::firstOrCreate(
            ['email' => 'admin@hospital.com'],
            [
                'name' => 'Super Admin',
                'phone' => '0123456789',
                'password' => Hash::make('password')
            ]
        );
        // gán role admin
        $adminRole = Role::where('name', 'admin')->first();
        if ($adminRole && !$admin->roles->contains($adminRole->id)) {
            $admin->roles()->attach($adminRole->id);
        }

        // Thêm 2 bác sĩ mẫu
        $doctor = User::firstOrCreate(
            ['email' => 'doctor@hospital.com'],
            ['name' => 'Dr. Smith', 'password' => Hash::make('password')]
        );
        $doctor->roles()->attach(Role::where('name','doctor')->first()->id);

        // Thêm 2 y tá mẫu
        $nurse = User::firstOrCreate(
            ['email' => 'nurse@hospital.com'],
            ['name' => 'Nurse Lily', 'password' => Hash::make('password')]
        );
        $nurse->roles()->attach(Role::where('name','nurse')->first()->id);

        // Thêm bệnh nhân mẫu
        $patient = User::firstOrCreate(
            ['email' => 'patient@hospital.com'],
            ['name' => 'John Doe', 'password' => Hash::make('password')]
        );
        $patient->roles()->attach(Role::where('name','patient')->first()->id);
    
    }
}
