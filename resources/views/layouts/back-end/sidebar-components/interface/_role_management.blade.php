@if(Route::currentRouteName() == 'add.role' || Route::currentRouteName() == 'edit.role' || Route::currentRouteName() == 'delete.role' || Route::currentRouteName() == 'role.list')
    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#roles" aria-expanded="true" aria-controls="roles">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-users-gear"></i></div>
        Roles
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse show" id="companies" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
        @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#roles" aria-expanded="false" aria-controls="roles">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-users-gear"></i></div>
                Roles
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="roles" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                @endif
                <nav class="sb-sidenav-menu-nested nav">
                    @if(Route::currentRouteName() == 'add.role')
                        <a class="nav-link" href="{{route('add.role')}}"><div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div> Add Role</a>
                    @else
                        <a class="nav-link text-chl" href="{!! route('add.role') !!}"><div class="sb-nav-link-icon"><i class="fas fa-plus"></i></div> Add Role</a>
                    @endif
                    @if(Route::currentRouteName() == 'role.list')
                        <a class="nav-link" href="{{route('role.list')}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Role List</a>
                    @else
                        <a class="nav-link text-chl" href="{!! route('role.list') !!}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Role List</a>
                    @endif
                    @if(Route::currentRouteName() == 'edit.role')
                        <a class="nav-link" href="{{route('edit.role',['roleID'=>\Illuminate\Support\Facades\Request::route('roleID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Edit Role</a>
                    @endif
                </nav>
            </div>
