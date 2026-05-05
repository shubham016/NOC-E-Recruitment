<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CandidateOtp extends Model
{
    use HasFactory;

    protected $fillable = [
        'email',
        'otp',
        'type',
        'expires_at',
        'is_used',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used' => 'boolean',
    ];

    /**
     * Generate a random 6-digit OTP
     */
    public static function generateOTP()
    {
        return str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new OTP for email
     */
    public static function createOTP($email, $type = 'registration')
    {
        // Delete old OTPs for this email
        self::where('email', $email)
            ->where('type', $type)
            ->delete();

        // Create new OTP
        return self::create([
            'email' => $email,
            'otp' => self::generateOTP(),
            'type' => $type,
            'expires_at' => Carbon::now()->addMinutes(10), // OTP valid for 10 minutes
            'is_used' => false,
        ]);
    }

    /**
     * Verify OTP
     */
    public static function verifyOTP($email, $otp, $type = 'registration')
    {
        return self::where('email', $email)
            ->where('otp', $otp)
            ->where('type', $type)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->first();
    }

    /**
     * Check if OTP is expired
     */
    public function isExpired()
    {
        return $this->expires_at < Carbon::now();
    }

    /**
     * Mark OTP as used
     */
    public function markAsUsed()
    {
        $this->is_used = true;
        $this->save();
    }
}