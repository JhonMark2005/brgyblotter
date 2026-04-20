<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordResetCustom extends Model
{
    protected $table = 'password_resets_custom';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'token',
        'used',
        'expires_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a new password reset token for the given user.
     */
    public static function createForUser(int $userId): string
    {
        // Invalidate existing tokens
        static::where('user_id', $userId)->delete();

        $token     = bin2hex(random_bytes(32));
        $expiresAt = now()->addHour()->format('Y-m-d H:i:s');

        static::create([
            'user_id'    => $userId,
            'token'      => $token,
            'used'       => false,
            'expires_at' => $expiresAt,
        ]);

        return $token;
    }

    /**
     * Find a valid (unused, unexpired) token.
     */
    public static function findValid(string $token): ?self
    {
        $reset = static::with('user')
                       ->where('token', $token)
                       ->where('used', false)
                       ->first();

        if (!$reset) return null;
        if (strtotime($reset->expires_at) < time()) return null;

        return $reset;
    }

    /**
     * Mark a token as used.
     */
    public static function markUsed(string $token): void
    {
        static::where('token', $token)->update(['used' => true]);
    }
}
