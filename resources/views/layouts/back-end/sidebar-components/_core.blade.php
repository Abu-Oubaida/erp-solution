@if(Route::currentRouteName() == 'dashboard')
    <a class="nav-link" href="{{route('dashboard')}}">
        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
        Dashboard
    </a>
@else
    <a class="nav-link text-chl" href="{{route('dashboard')}}">
        <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
        Dashboard
    </a>
@endif
@if(auth()->user()->hasPermission('file_manager'))
    <a class="nav-link text-chl" href="{{route('file-manager')}}">
        <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
        File Manager
    </a>
@endif
@if(auth()->user()->hasPermission('control_panel'))
    @if(Request::segment(1) == 'control-panel')
    <a class="nav-link" href="{{route('control.panel')}}">
        <div class="sb-nav-link-icon"><i class="fas fa-solid fa-gears"></i></div>
        Control Panel
    </a>
    @else
    <a class="nav-link text-chl" href="{{route('control.panel')}}">
        <div class="sb-nav-link-icon"><i class="fas fa-solid fa-gears"></i></div>
        Control Panel
    </a>
    @endif
@endif
