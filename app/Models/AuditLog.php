<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuditLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'user_name',
        'action',
        'entity_type',
        'entity_id',
        'description',
    ];

    /**
     * Log an action using the current session user.
     */
    public static function log(string $action, string $entityType, ?int $entityId, string $description): void
    {
        $sessionUser = session('user');
        $userId   = $sessionUser['id']        ?? 0;
        $userName = $sessionUser['full_name']  ?? 'System';

        static::create([
            'user_id'     => $userId,
            'user_name'   => $userName,
            'action'      => $action,
            'entity_type' => $entityType,
            'entity_id'   => $entityId,
            'description' => $description,
        ]);
    }

    /**
     * Get paginated logs with optional filters.
     */
    public static function getFiltered(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $query = static::query();

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }
        if (!empty($filters['entity_type'])) {
            $query->where('entity_type', $filters['entity_type']);
        }
        if (!empty($filters['user_id'])) {
            $query->where('user_id', (int) $filters['user_id']);
        }

        $total = $query->count();
        $logs  = $query->orderByDesc('created_at')
                       ->offset(($page - 1) * $perPage)
                       ->limit($perPage)
                       ->get();

        return compact('logs', 'total');
    }

    /**
     * Get distinct users from audit logs.
     */
    public static function getUsers(): \Illuminate\Support\Collection
    {
        return static::select('user_id', 'user_name')
                     ->distinct()
                     ->orderBy('user_name')
                     ->get();
    }
}
