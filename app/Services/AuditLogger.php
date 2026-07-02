<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AuditLogger
{
    public function auth(
        Request $request,
        string $userType,
        string $action,
        string $status,
        ?object $user = null,
        ?string $identifier = null,
        ?string $failureReason = null
    ): void {
        try {
            AuditLog::create([
                'user_type'       => $userType,
                'user_id'         => $user?->id,
                'user_name'       => $user?->name,
                'user_identifier' => $identifier ?: $this->identifierFromUser($user),
                'action'          => $action,
                'status'          => $status,
                'failure_reason'  => $failureReason,
                'ip_address'      => $request->ip(),
                'user_agent'      => Str::limit((string) $request->userAgent(), 500, ''),
                'attempted_at'    => now(),
            ]);
        } catch (\Throwable $exception) {
            Log::warning('Audit log write failed', [
                'user_type' => $userType,
                'action' => $action,
                'status' => $status,
                'message' => $exception->getMessage(),
            ]);
        }
    }

    private function identifierFromUser(?object $user): ?string
    {
        if (!$user) {
            return null;
        }

        return $user->email ?? $user->employee_id ?? null;
    }
}
