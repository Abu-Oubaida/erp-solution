@if(auth()->user()->hasPermission('add_complain'))
    @if(Route::currentRouteName() == 'add.complain')
        <a class="nav-link" href="{{route('add.complain')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
    @else
        <a class="nav-link text-chl" href="{{route('add.complain')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
    @endif
@endif
@if(auth()->user()->hasPermission('list_my_complain'))
@if(Route::currentRouteName() == 'my.list.complain')
    <a class="nav-link" href="{{route("my.list.complain")}}"> <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> My List</a>
@else
    <a class="nav-link text-chl" href="{{route("my.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> My List</a>
@endif
@endif
@if(auth()->user()->hasPermission('list_complain_all') || auth()->user()->hasPermission('list_department_complain_all'))
{{--Received List Start--}}
@if(Route::currentRouteName() == 'individual.list.complain' || Route::currentRouteName() == 'departmental.list.complain')
    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="true" aria-controls="pagesCollapseAuth">
        <div class="sb-nav-link-icon"><i class="fas fa-table-list"></i></div>
        Received List
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse show" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
@else
    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
        <div class="sb-nav-link-icon"><i class="fas fa-table-list"></i></div>
        Received List
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
@endif
        <nav class="sb-sidenav-menu-nested nav">
        @if(auth()->user()->hasPermission('list_complain_all'))
            @if(Route::currentRouteName() == 'individual.list.complain')
                <a class="nav-link" href="{{route("individual.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Individual List</a>
            @else
                <a class="nav-link text-chl" href="{{route("individual.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Individual List</a>
            @endif
        @endif
        @if( auth()->user()->hasPermission('list_department_complain_all'))
            @if(Route::currentRouteName() == 'departmental.list.complain')
                <a class="nav-link" href="{{route("departmental.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div> Dept. List</a>
            @else
                <a class="nav-link text-chl" href="{{route("departmental.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div> Dept. List</a>
            @endif
        @endif
        </nav>
    </div>
@endif

@if(auth()->user()->hasPermission('view_complain_single'))
    @if(Route::currentRouteName() == 'single.view.complain')
        <a class="nav-link" href="{{route("single.view.complain",['complainID'=>\Illuminate\Support\Facades\Request::route('complainID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-eye"></i></div> Complain View</a>
    @endif
@endif

@if(auth()->user()->hasPermission('edit_complain'))
    @if(Route::currentRouteName() == 'edit.my.complain')
        <a class="nav-link" href="{{route("edit.my.complain",['complainID'=>\Illuminate\Support\Facades\Request::route('complainID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Complain Edit</a>
    @endif
@endif

@if(auth()->user()->hasPermission('list_my_complain_trash'))
    {{--Received List End--}}
    @if(Route::currentRouteName() == 'my.complain.trash.list')
        <a class="nav-link" href="{{route("my.complain.trash.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-trash"></i></div> My Trash List</a>
    @else
        <a class="nav-link text-chl" href="{{route("my.complain.trash.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-trash"></i></div> My Trash List</a>
    @endif
@endif
