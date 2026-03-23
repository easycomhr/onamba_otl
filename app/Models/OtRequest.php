<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'approved_by',
        'code',
        'ot_date',
        'hours',
        'approved_hours',
        'reason',
        'manager_note',
        'status',
        'rejected_at',
        'approved_at',
    ];

    protected function casts(): array
    {
        return [
            'ot_date'     => 'date',
            'hours'       => 'decimal:1',
            'approved_hours' => 'decimal:1',
            'rejected_at' => 'datetime',
            'approved_at' => 'datetime',
        ];
    }

    // ── Status constants ───────────────────────────────────────

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // ── Boot: auto-generate code ───────────────────────────────

    protected static function booted(): void
    {
        static::creating(function (OtRequest $model) {
            if (empty($model->code)) {
                $year  = now()->format('Y');
                $month = now()->format('m');
                $seq   = static::whereYear('created_at', $year)
                               ->whereMonth('created_at', $month)
                               ->count() + 1;
                $model->code = sprintf('OT-%s%s-%02d', $year, $month, $seq);
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

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_APPROVED => 'Đã duyệt',
            self::STATUS_REJECTED => 'Từ chối',
            default               => 'Chờ duyệt',
        };
    }
}
