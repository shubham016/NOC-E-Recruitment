<?php

namespace Tests\Feature;

use Tests\TestCase;

class PortalEntryTest extends TestCase
{
    public function test_portal_login_pages_are_reachable(): void
    {
        $this->get(route('admin.login'))->assertOk();
        $this->get(route('candidate.login'))->assertOk();
        $this->get(route('reviewer.login'))->assertOk();
        $this->get(route('approver.login'))->assertOk();
    }

    public function test_portal_dashboards_require_authentication(): void
    {
        $this->get(route('admin.dashboard'))->assertRedirect(route('admin.login'));
        $this->get(route('candidate.dashboard'))->assertRedirect(route('candidate.login'));
        $this->get(route('reviewer.dashboard'))->assertRedirect(route('reviewer.login'));
        $this->get(route('approver.dashboard'))->assertRedirect(route('approver.login'));
    }
}
