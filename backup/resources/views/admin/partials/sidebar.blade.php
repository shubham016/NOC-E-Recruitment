{{-- Admin Portal Sidebar Menu --}}
<a href="{{ route('admin.dashboard') }}" class="sidebar-menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
    <i class="bi bi-speedometer2"></i>
    <span>Dashboard</span>
</a>

<a href="{{ route('admin.jobs.create') }}" class="sidebar-menu-item {{ request()->routeIs('admin.jobs.create') ? 'active' : '' }}">
    <i class="bi bi-briefcase"></i>
    <span>Post Vacancy</span>
</a>

<a href="{{ route('admin.jobs.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.jobs.*') && !request()->routeIs('admin.jobs.create') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-text"></i>
    <span>Vacancy List</span>
    <!-- @if(isset($stats['active_vacancies']))
        <span class="badge bg-primary ms-auto">{{ $stats['active_vacancies'] }}</span>
    @endif -->
</a>

<a href="{{ route('admin.applications.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.applications.*') ? 'active' : '' }}">
    <i class="bi bi-file-earmark-check"></i>
    <span>Applications</span>
    <!-- @if(isset($stats['pending_applications']))
        <span class="badge bg-warning text-dark ms-auto">{{ $stats['pending_applications'] }}</span>
    @endif -->
</a>

<a href="{{ route('admin.candidates.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.candidates.*') ? 'active' : '' }}">
    <i class="bi bi-people"></i>
    <span>Candidates</span>
    <!-- @if(isset($stats['total_candidates']))
        <span class="badge bg-info ms-auto">{{ $stats['total_candidates'] }}</span>
    @endif -->
</a>

<a href="{{ route('admin.reviewers.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reviewers.*') ? 'active' : '' }}">
    <i class="bi bi-person-badge"></i>
    <span>Reviewers</span>
    <!-- @if(isset($stats['active_reviewers']))
        <span class="badge bg-success ms-auto">{{ $stats['active_reviewers'] }}</span>
    @endif -->
</a>


<a href="{{ route('admin.approvers.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.approvers.*') ? 'active' : '' }}">
    <i class="bi bi-person-check"></i>
    <span>Approvers</span>
    <!-- @if(isset($stats['active_approvers']))
        <span class="badge bg-success ms-auto">{{ $stats['active_approvers'] }}</span>
    @endif -->
</a>

<a href="{{ route('admin.reports.index') }}" class="sidebar-menu-item {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
    <i class="bi bi-bar-chart"></i>
    <span>Reports</span>
</a>

<a href="{{ route('admin.settings') }}" class="sidebar-menu-item {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
    <i class="bi bi-gear"></i>
    <span>Settings</span>
</a>
