@php
    $isAdmin = auth()->user()->role === 'admin';

    $dashRoute  = $isAdmin ? route('admin.dashboard') : route('dashboard');
    $dashActive = $isAdmin
        ? request()->routeIs('admin.dashboard')
        : request()->routeIs('dashboard');
@endphp

<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    {{-- Brand --}}
    <a class="sidebar-brand d-flex align-items-center justify-content-center"
       href="{{ $dashRoute }}">
        <div class="sidebar-brand-icon">
            <i class="fas fa-map-marked-alt"></i>
        </div>
        <div class="sidebar-brand-text mx-3">SID Panggung</div>
    </a>

    <hr class="sidebar-divider my-0">

    {{-- Dashboard --}}
    <li class="nav-item {{ $dashActive ? 'active' : '' }}">
        <a class="nav-link" href="{{ $dashRoute }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span>
        </a>
    </li>

    <hr class="sidebar-divider">

    {{-- ===== MENU USER ===== --}}
    @if(!$isAdmin)
        <div class="sidebar-heading">Menu</div>

        <li class="nav-item {{ request()->routeIs('tenagakerja.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('tenagakerja.index') }}">
                <i class="fas fa-fw fa-file-alt"></i>
                <span>Kuesioner Tenaga Kerja</span>
            </a>
        </li>
    @endif

    {{-- ===== MENU ADMIN ===== --}}
    @if($isAdmin)
        <div class="sidebar-heading">Menu Admin</div>

        <li class="nav-item {{ request()->routeIs('admin.tkw.index') || request()->routeIs('admin.tkw.show') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.tkw.index') }}">
                <i class="fas fa-fw fa-user-check"></i>
                <span>Verifikasi Tenaga Kerja</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.tkw.listrt') || request()->routeIs('admin.tkw.showrt') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.tkw.listrt') }}">
                <i class="fas fa-fw fa-list-alt"></i>
                <span>Data per RT</span>
            </a>
        </li>

        <li class="nav-item {{ request()->routeIs('admin.user.*') ? 'active' : '' }}">
            <a class="nav-link" href="{{ route('admin.user.index') }}">
                <i class="fas fa-fw fa-users-cog"></i>
                <span>Manajemen Akun</span>
            </a>
        </li>
    @endif

    <hr class="sidebar-divider d-none d-md-block">

    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>