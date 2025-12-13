<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use App\Models\DoctorSite;
use App\Models\Category;
use App\Models\Service;
use App\Models\Medicine;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. TẠO ROLES (VAI TRÒ)
        $roles = ['admin', 'doctor', 'nurse', 'pharmacist', 'receptionist', 'patient'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName], ['description' => 'Vai trò hệ thống']);
        }

        // 2. TẠO TÀI KHOẢN ADMIN (Để bạn đăng nhập quản lý)
        $adminRole = Role::where('name', 'admin')->first();
        $admin = User::create([
            'name' => 'Administrator',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('12345678'), // Mật khẩu là 12345678
            'phone' => '0909000111',
            'status' => 'active',
            'is_active' => true,
        ]);
        // Gán quyền admin (thêm vào bảng trung gian user_roles nếu bạn dùng bảng đó)
        DB::table('user_roles')->insert([
            'user_id' => $admin->id,
            'role_id' => $adminRole->id
        ]);

        // 3. TẠO CHUYÊN KHOA (DEPARTMENTS)
        $depts = [
            ['code' => 'KHOA_NOI', 'name' => 'Khoa Nội Tổng Hợp', 'desc' => 'Khám và điều trị các bệnh nội khoa', 'img' => 'khoa-noi.jpg'],
            ['code' => 'KHOA_NGOAI', 'name' => 'Khoa Ngoại', 'desc' => 'Phẫu thuật và điều trị ngoại khoa', 'img' => 'khoa-ngoai.jpg'],
            ['code' => 'KHOA_NHI', 'name' => 'Khoa Nhi', 'desc' => 'Chăm sóc sức khỏe trẻ em', 'img' => 'khoa-nhi.jpg'],
            ['code' => 'KHOA_TIM', 'name' => 'Khoa Tim Mạch', 'desc' => 'Chuyên sâu về tim mạch', 'img' => 'khoa-tim.jpg'],
            ['code' => 'KHOA_MAT', 'name' => 'Khoa Mắt', 'desc' => 'Khám chữa bệnh về mắt', 'img' => 'khoa-mat.jpg'],
        ];

        foreach ($depts as $d) {
            Department::create([
                'code' => $d['code'],
                'name' => $d['name'],
                'description' => $d['desc'],
                'image' => $d['img'],
                'status' => 'active',
                'fee' => 150000, // Phí khám mặc định
            ]);
        }

        // 4. TẠO BÁC SĨ (USERS + DOCTOR_SITES)
        $doctorsData = [
            ['name' => 'BS. Nguyễn Văn A', 'dept' => 'KHOA_NOI', 'bio' => 'Trưởng khoa Nội, 15 năm kinh nghiệm.'],
            ['name' => 'BS. Trần Thị B', 'dept' => 'KHOA_NHI', 'bio' => 'Chuyên gia tâm lý trẻ em.'],
            ['name' => 'BS. Lê Văn C', 'dept' => 'KHOA_TIM', 'bio' => 'Tiến sĩ y khoa, chuyên ngành tim mạch.'],
            ['name' => 'BS. Phạm Thị D', 'dept' => 'KHOA_NGOAI', 'bio' => 'Bác sĩ phẫu thuật chỉnh hình.'],
            ['name' => 'BS. Hoàng Văn E', 'dept' => 'KHOA_MAT', 'bio' => 'Chuyên gia khúc xạ.'],
        ];

        $doctorRole = Role::where('name', 'doctor')->first();

        foreach ($doctorsData as $index => $doc) {
            // Tạo User bác sĩ
            $userDoc = User::create([
                'name' => $doc['name'],
                'email' => 'bacsi' . ($index + 1) . '@gmail.com',
                'password' => Hash::make('12345678'),
                'phone' => '098877766' . $index,
                'status' => 'active',
                'is_active' => true,
            ]);
            
            // Gán role
            DB::table('user_roles')->insert([
                'user_id' => $userDoc->id,
                'role_id' => $doctorRole->id
            ]);

            // Tìm ID khoa
            $dept = Department::where('code', $doc['dept'])->first();

            // Tạo thông tin DoctorSite (để hiện lên web đặt lịch)
            DoctorSite::create([
                'user_id' => $userDoc->id,
                'department_id' => $dept->id,
                'specialization' => $dept->name,
                'experience_years' => rand(5, 20),
                'bio' => $doc['bio'],
                'rating' => 5.0,
                'reviews_count' => rand(10, 50),
                'status' => 1,
            ]);
        }

        // 5. TẠO DANH MỤC & DỊCH VỤ KHÁM (SERVICES)
        $cat = Category::create([
            'name' => 'Dịch vụ khám bệnh',
            'slug' => 'dich-vu-kham-benh',
            'status' => 1
        ]);

        $services = [
            ['name' => 'Khám tổng quát', 'fee' => 200000],
            ['name' => 'Khám chuyên khoa Nhi', 'fee' => 150000],
            ['name' => 'Siêu âm tim màu', 'fee' => 300000],
            ['name' => 'Chụp X-Quang', 'fee' => 100000],
            ['name' => 'Xét nghiệm máu tổng quát', 'fee' => 500000],
        ];

        foreach ($services as $sv) {
            Service::create([
                'name' => $sv['name'],
                'fee' => $sv['fee'],
                'duration' => 30, // phút
                'status' => 1,
                'category_id' => $cat->id,
                'department_id' => Department::inRandomOrder()->first()->id, // Gán ngẫu nhiên khoa
            ]);
        }
        
        // 6. TẠO THUỐC MẪU (MEDICINES)
        $medicines = [
            ['name' => 'Paracetamol 500mg', 'code' => 'MED001', 'price' => 1000, 'unit' => 'Viên'],
            ['name' => 'Amoxicillin 500mg', 'code' => 'MED002', 'price' => 2000, 'unit' => 'Viên'],
            ['name' => 'Vitamin C', 'code' => 'MED003', 'price' => 1500, 'unit' => 'Viên'],
        ];
        
        foreach($medicines as $med) {
            Medicine::create([
                'name' => $med['name'],
                'code' => $med['code'],
                'price' => $med['price'],
                'unit' => $med['unit'],
                'stock' => 1000,
                'status' => 'Sắp hết' // Hoặc giá trị enum tương ứng trong DB của bạn
            ]);
        }
    }
}