<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = 'notifications_id';

    protected $fillable = [
        'user_id', 'type', 'title', 'message',
        'icon', 'link', 'data', 'is_read', 'read_at',
    ];

    protected $casts = [
        'data'    => 'array',
        'is_read' => 'boolean',
        'read_at' => 'datetime',
    ];

    // =====================
    // RELASI
    // =====================

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // =====================
    // SCOPES
    // =====================

    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    // =====================
    // HELPERS
    // =====================

    public function markAsRead(): void
    {
        if (!$this->is_read) {
            $this->update(['is_read' => true, 'read_at' => now()]);
        }
    }

    // Warna badge sesuai tipe
    public function getColorAttribute(): string
    {
        return match($this->type) {
            'deadline_reminder' => '#ef4444',
            'habit_reminder'    => '#f59e0b',
            'achievement'       => '#10b981',
            'info'              => '#6366f1',
            default             => '#6b7280',
        };
    }
}
