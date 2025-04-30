@if (auth()->user()->hasPermission('add_user') ||
        auth()->user()->hasPermission('list_user') ||
        auth()->user()->hasPermission('view_user') ||
        auth()->user()->hasPermission('edit_user') ||
        auth()->user()->hasPermission('user_screen_permission') ||
        auth()->user()->hasPermission('file_manager_permission')||
        auth()->user()->hasPermission('get_sale_employee_entry')
        )
    @if (Route::currentRouteName() == 'add.user' ||
            Route::currentRouteName() == 'user.list' ||
            Route::currentRouteName() == 'user.single.view' ||
            Route::currentRouteName() == 'user.edit' ||
            Route::currentRouteName() == 'user.screen.permission' ||
            Route::currentRouteName() == 'file.manager.permission'||
            Route::currentRouteName() == 'get.sale.employee.entry'
            )
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#users" aria-expanded="true"
            aria-controls="users">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
            Users
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="users" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
        @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#users"
                aria-expanded="false" aria-controls="users">
                <div class="sb-nav-link-icon"><i class="fa-solid fa-users"></i></div>
                Users
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="users" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
    @endif
    <nav class="sb-sidenav-menu-nested nav">
        @if (auth()->user()->hasPermission('add_user'))
            @if (Route::currentRouteName() == 'add.user')
                <a class="nav-link" href="{{ route('add.user') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add User
                </a>
            @else
                <a class="nav-link text-chl" href="{{ route('add.user') }}">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add User
                </a>
            @endif
        @endif
        @if (auth()->user()->hasPermission('list_user'))
            @if (Route::currentRouteName() == 'user.list')
                <a class="nav-link" href="{{ route('user.list') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> User List
                </a>
            @else
                <a class="nav-link text-chl" href="{{ route('user.list') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> User List
                </a>
            @endif
        @endif
        @if (auth()->user()->hasPermission('sale_employee_entry'))
            @if (Route::currentRouteName() == 'get.sale.employee.entry')
                <a class="nav-link" href="{{ route('get.sale.employee.entry') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> Sales Employee Entry
                </a>
            @else
                <a class="nav-link text-chl" href="{{ route('get.sale.employee.entry') }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> Sales Employee Entry
                </a>
            @endif
        @endif
        @if (auth()->user()->hasPermission('user_screen_permission'))
            @if (Route::currentRouteName() == 'user.screen.permission')
                <a class="nav-link"
                    href="{{ route('user.screen.permission', ['userID' => \Illuminate\Support\Facades\Request::route('userID')]) }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>Screen Permission
                </a>
            @endif
        @endif


        @if (auth()->user()->hasPermission('file_manager_permission'))
            @if (Route::currentRouteName() == 'file.manager.permission')
                <a class="nav-link"
                    href="{{ route('file.manager.permission', ['userID' => \Illuminate\Support\Facades\Request::route('userID')]) }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div>File Permission
                </a>
            @endif
        @endif

        @if (auth()->user()->hasPermission('view_user'))
            @if (Route::currentRouteName() == 'user.single.view')
                <a class="nav-link"
                    href="{{ route('user.single.view', ['userID' => \Illuminate\Support\Facades\Request::route('userID')]) }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-user"></i></div> User Profile
                </a>
            @endif
        @endif
        @if (auth()->user()->hasPermission('edit_user'))
            @if (Route::currentRouteName() == 'user.edit')
                <a class="nav-link"
                    href="{{ route('user.edit', ['userID' => \Illuminate\Support\Facades\Request::route('userID')]) }}">
                    <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> User Profile
                </a>
            @endif
        @endif
    </nav>
    </div>
@endif
