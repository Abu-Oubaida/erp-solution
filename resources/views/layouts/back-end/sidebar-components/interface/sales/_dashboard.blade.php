@if(auth()->user()->hasPermission('sales_dashboard_interface') )
    @if(Route::currentRouteName() == 'sales.dashboard.interface')
        <a class="nav-link" href="{{route('sales.dashboard.interface')}}">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>
    @else
        <a class="nav-link text-chl" href="{{route('sales.dashboard.interface')}}">
            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
            Dashboard
        </a>
    @endif
@endif {{--Upload Option End Here--}}
