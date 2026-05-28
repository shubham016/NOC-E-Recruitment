{{-- Admin Portal Sidebar Menu --}}
<a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i>
    <span>{{ __('admin.dashboard') }}</span>
</a>

<a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item {{ request()->routeIs('admin.jobs.create') ? 'active' : '' }}">
    <i class="bi bi-briefcase"></i>
    <span>{{ __('admin.post_vacancy') }}</span>
</a>

<a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.jobs.*') && !request()->routeIs('admin.jobs.create') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-text"></i>
    <span>{{ __('admin.vacancy_list') }}</span>
</a>

<a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-check"></i>
    <span>{{ __('admin.applications') }}</span>
</a>

<a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i>
    <span>{{ __('admin.candidates') }}</span>
</a>

<a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }}">
    <i class="bi bi-person-badge"></i>
    <span>{{ __('admin.reviewers') }}</span>
</a>

<a href="{{ route('admin.approvers.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.approvers.*') ? 'active' : '' }}">
    <i class="bi bi-person-check"></i>
    <span>{{ __('admin.approvers') }}</span>
</a>

<a href="{{ route('admin.admit-card.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.admit-card.*') ? 'active' : '' }}">
    <i class="bi bi-card-heading"></i>
    <span>{{ __('admin.admit_cards') }}</span>
</a>

<a href="{{ route('admin.reports.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart"></i>
    <span>{{ __('admin.reports') }}</span>
</a>

<a href="{{ route('admin.settings') }}" class="sidebar-menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
    <i class="bi bi-gear"></i>
    <span>{{ __('admin.settings') }}</span>
</a>
