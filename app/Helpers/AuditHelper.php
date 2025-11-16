<?php

namespace App\Helpers;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Request;

class AuditHelper
{
    /**
     * Ghi log hành động vào bảng audit_logs
     *
     * @param string $action  Mô tả hành động (VD: 'Tạo tài khoản mới')
     * @param string|object|null $target  Đối tượng tác động (VD: tên người dùng)
     * @param string $status  Thành công / Thất bại
     */
    public static function log($action, $target = null, $status = 'Thành công')
    {
        try {
            AuditLog::create([
                'user_id' => Auth::id(),
                'action' => $action,
                'target' => is_object($target) ? ($target->name ?? get_class($target)) : $target,
                'ip_address' => Request::ip(),
                'status' => $status,
            ]);
        } catch (\Exception $e) {
            Log::error('Không thể ghi audit log: ' . $e->getMessage());
        }
    }
}
