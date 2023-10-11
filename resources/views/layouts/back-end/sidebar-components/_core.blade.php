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
@if(auth()->user()->hasPermission('add_department'))
    <a class="nav-link text-chl" href="{{route('file-manager')}}">
        <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
        File Manager
    </a>
@endif
