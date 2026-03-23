<?php

namespace Database\Seeders;

use App\Models\LeaveRequest;
use App\Models\OtRequest;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Real employees from tblusers.sql ──────────────────
        $this->call(UserSeeder::class);

        // ── Manager ───────────────────────────────────────────
        $manager = User::firstOrCreate(
            ['employee_code' => 'QL00001'],
            [
                'name'                 => 'Nguyễn Văn Quản Lý',
                'email'                => 'manager@company.com',
                'password'             => Hash::make('password'),
                'role'                 => 'manager',
                'department'           => 'Ban Quản lý',
                'position'             => 'Quản lý hệ thống',
                'annual_leave_balance' => 15,
            ]
        );

        // ── Employees ─────────────────────────────────────────
        $rawEmployees = [
            ['name'=>'Nguyễn Văn A', 'employee_code'=>'NV12345', 'dept'=>'Xưởng A',  'position'=>'Công nhân',    'leave'=>5],
            ['name'=>'Trần Thị B',   'employee_code'=>'NV12346', 'dept'=>'Xưởng B',  'position'=>'Tổ trưởng',    'leave'=>8],
            ['name'=>'Lê Văn C',     'employee_code'=>'NV12347', 'dept'=>'Kho Vận',  'position'=>'Công nhân',    'leave'=>3],
            ['name'=>'Phạm Thị D',   'employee_code'=>'NV12348', 'dept'=>'Phòng QC', 'position'=>'Kiểm soát CL', 'leave'=>10],
        ];

        $employees = [];
        foreach ($rawEmployees as $emp) {
            $employees[] = User::firstOrCreate(
                ['employee_code' => $emp['employee_code']],
                [
                    'name'                 => $emp['name'],
                    'email'                => strtolower($emp['employee_code']) . '@company.com',
                    'password'             => Hash::make('password'),
                    'role'                 => 'employee',
                    'department'           => $emp['dept'],
                    'position'             => $emp['position'],
                    'annual_leave_balance' => $emp['leave'],
                ]
            );
        }

        // ── OT Requests ───────────────────────────────────────
        $otData = [
            ['user'=>0, 'date'=>'2026-03-15', 'hours'=>2.5, 'reason'=>'Hỗ trợ dây chuyền lắp ráp xưởng A',  'status'=>'pending'],
            ['user'=>1, 'date'=>'2026-03-16', 'hours'=>4.0, 'reason'=>'Bảo trì máy đóng gói định kỳ',         'status'=>'pending'],
            ['user'=>0, 'date'=>'2026-03-10', 'hours'=>3.0, 'reason'=>'Hoàn thành đơn hàng gấp xưởng A',      'status'=>'approved', 'approved_hours'=>3.0, 'note'=>'Đồng ý, nhớ tắt máy trước khi về.'],
            ['user'=>2, 'date'=>'2026-03-05', 'hours'=>4.0, 'reason'=>'Kiểm tra hàng xuất kho',                'status'=>'rejected', 'note'=>'Đã đủ người, không cần làm thêm.'],
        ];

        foreach ($otData as $i => $ot) {
            [$y, $m] = [date('Y', strtotime($ot['date'])), date('m', strtotime($ot['date']))];
            OtRequest::create([
                'user_id'        => $employees[$ot['user']]->id,
                'approved_by'    => $ot['status'] !== 'pending' ? $manager->id : null,
                'code'           => sprintf('OT-%s%s-%02d', $y, $m, $i + 1),
                'ot_date'        => $ot['date'],
                'hours'          => $ot['hours'],
                'approved_hours' => $ot['approved_hours'] ?? null,
                'reason'         => $ot['reason'],
                'manager_note'   => $ot['note'] ?? null,
                'status'         => $ot['status'],
                'approved_at'    => $ot['status'] === 'approved' ? now() : null,
                'rejected_at'    => $ot['status'] === 'rejected' ? now() : null,
            ]);
        }

        // ── Leave Requests ────────────────────────────────────
        $leaveData = [
            ['user'=>0, 'type'=>'annual',   'from'=>'2026-03-18', 'to'=>'2026-03-19', 'days'=>2, 'reason'=>'Việc gia đình',         'status'=>'pending'],
            ['user'=>1, 'type'=>'sick',      'from'=>'2026-03-20', 'to'=>'2026-03-20', 'days'=>1, 'reason'=>'Khám sức khỏe định kỳ','status'=>'pending'],
            ['user'=>2, 'type'=>'annual',   'from'=>'2026-03-05', 'to'=>'2026-03-05', 'days'=>1, 'reason'=>'Việc cá nhân',          'status'=>'approved'],
        ];

        foreach ($leaveData as $i => $lv) {
            [$y, $m] = [date('Y', strtotime($lv['from'])), date('m', strtotime($lv['from']))];
            LeaveRequest::create([
                'user_id'     => $employees[$lv['user']]->id,
                'approved_by' => $lv['status'] !== 'pending' ? $manager->id : null,
                'code'        => sprintf('LV-%s%s-%02d', $y, $m, $i + 1),
                'leave_type'  => $lv['type'],
                'from_date'   => $lv['from'],
                'to_date'     => $lv['to'],
                'days'        => $lv['days'],
                'reason'      => $lv['reason'],
                'status'      => $lv['status'],
                'approved_at' => $lv['status'] === 'approved' ? now() : null,
            ]);
        }
    }
}
