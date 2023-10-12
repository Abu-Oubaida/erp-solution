@if(auth()->user()->hasPermission('add_sim_number'))
    @if(Route::currentRouteName() == 'add.number')
        <a class="nav-link" href="{{route('add.number')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
    @else
        <a class="nav-link text-chl" href="{{route('add.number')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
    @endif
@endif
