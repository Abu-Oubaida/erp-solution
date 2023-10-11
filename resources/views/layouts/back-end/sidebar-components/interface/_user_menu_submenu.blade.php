@if(auth()->user()->hasPermission('add_user'))
    @if(Route::currentRouteName() == 'add.user')
        <a class="nav-link" href="{{route('add.user')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add User</a>
    @else
        <a class="nav-link text-chl" href="{{route('add.user')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add User</a>
    @endif
@endif
@if(auth()->user()->hasPermission('list_user'))
    @if(Route::currentRouteName() == 'user.list')
        <a class="nav-link" href="{{route("user.list")}}"> <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> User List</a>
    @else
        <a class="nav-link text-chl" href="{{route("user.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> User List</a>
    @endif
@endif
@if(auth()->user()->hasPermission('view_user'))
    @if(Route::currentRouteName() == 'user.single.view')
        <a class="nav-link" href="{{route("user.single.view",['userID'=>\Illuminate\Support\Facades\Request::route('userID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div> User Profile</a>
    @endif
@endif
@if(auth()->user()->hasPermission('edit_user'))
    @if(Route::currentRouteName() == 'user.edit')
        <a class="nav-link" href="{{route("user.edit",['userID'=>\Illuminate\Support\Facades\Request::route('userID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div> User Profile</a>
    @endif
@endif
