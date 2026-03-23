<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    public const ROLE_EMPLOYEE = 'employee';
    public const ROLE_MANAGER  = 'manager';

    protected $fillable = [
        'name',
        'employee_code',
        'email',
        'password',
        'role',
        'department',
        'position',
        'annual_leave_balance',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at'    => 'datetime',
            'password'             => 'hashed',
            'annual_leave_balance' => 'integer',
        ];
    }

    // ── Relationships ──────────────────────────────────────────

    public function otRequests()
    {
        return $this->hasMany(OtRequest::class);
    }

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    // ── Helpers ────────────────────────────────────────────────

    public function isManager(): bool
    {
        return $this->role === 'manager';
    }

    public function isEmployee(): bool
    {
        return $this->role === 'employee';
    }
}
