<?php

namespace Tests\Unit;

use App\Models\Approver;
use App\Models\Reviewer;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class RoleAuthenticationIdentifierTest extends TestCase
{
    public static function roleModels(): array
    {
        return [
            'reviewer' => [Reviewer::class],
            'approver' => [Approver::class],
        ];
    }

    #[DataProvider('roleModels')]
    public function test_authentication_uses_the_primary_key_for_notification_identity(string $modelClass): void
    {
        $model = new $modelClass();
        $model->forceFill([
            'id' => 7,
            'employee_id' => 'EMP-1042',
        ]);

        $this->assertSame('id', $model->getAuthIdentifierName());
        $this->assertSame(7, $model->getAuthIdentifier());
    }
}
