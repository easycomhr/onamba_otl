<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LeaveRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'code',
        'leave_type',
        'from_date',
        'to_date',
        'days',
        'reason',
        'manager_note',
        'status',
        'rejected_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'from_date'   => 'date',
            'to_date'     => 'date',
            'days'        => 'integer',
            'rejected_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    // ── Status constants ───────────────────────────────────────

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    const LEAVE_TYPES = [
        'annual'   => 'Phép năm',
        'sick'     => 'Nghỉ bệnh',
        'personal' => 'Việc cá nhân',
        'unpaid'   => 'Không lương',
    ];

    // ── Boot: auto-generate code & days ───────────────────────

    protected static function booted(): void
    {
        static::creating(function (LeaveRequest $model) {
            // Auto code
            if (empty($model->code)) {
                $year  = now()->format('Y');
                $month = now()->format('m');
                $seq   = static::whereYear('created_at', $year)
                               ->whereMonth('created_at', $month)
                               ->count() + 1;
                $model->code = sprintf('LV-%s%s-%02d', $year, $month, $seq);
            }
            // Auto calculate days
            if (empty($model->days) && $model->from_date && $model->to_date) {
                $model->days = \Carbon\Carbon::parse($model->from_date)
                    ->diffInWeekdays(\Carbon\Carbon::parse($model->to_date)) + 1;
            }
        });
    }

    // ── Relationships ──────────────────────────────────────────

    public function employee()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // ── Scopes ─────────────────────────────────────────────────

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    // ── Accessors ──────────────────────────────────────────────

    public function getLeaveTypeLabelAttribute(): string
    {
        return self::LEAVE_TYPES[$this->leave_type] ?? $this->leave_type;
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Đã duyệt',
            self::STATUS_REJECTED => 'Từ chối',
            default               => 'Chờ duyệt',
        };
    }
}