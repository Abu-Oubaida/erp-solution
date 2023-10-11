<nav class="sb-sidenav accordion " id="sidenavAccordion" style="background: #f0ffffde;">
    <div class="sb-sidenav-menu">
        <div class="nav">
{{--#1.0 Core Start--}}
        <group1>
            <div class="sb-sidenav-menu-heading">Core</div>
            @include('layouts.back-end.sidebar-components._core')
        </group1>{{--#1.0Core End--}}

{{--#2.0 Interface Start--}}
        <group2>
            <div class="sb-sidenav-menu-heading">Interface</div>
        </group2>{{--#2.0Interface End--}}

{{--#2.1 Super Admin Components Start--}}
        <group3>
        @if(\Illuminate\Support\Facades\Auth::user()->hasRole('superadmin'))
            @include('layouts.back-end.sidebar-components.interface._only_super_admin')
        @endif
        </group3>{{--#2.1 Super Admin Components End--}}
{{--#2.2    User Management Start--}}
        <group4>
{{--#2.2.1  Permission Chck User Management Start--}}
        @if(auth()->user()->hasPermission('add_department') || auth()->user()->hasPermission('add_user') || auth()->user()->hasPermission('list_user') || auth()->user()->hasPermission('view_user') || auth()->user()->hasPermission('edit_user') || auth()->user()->hasPermission('delete_user') || auth()->user()->hasPermission('add_department'))
{{--#2.2.1.1   Route/URL Chck and set navigation header User Management Start--}}
            <subgroup1>
            @if(Route::currentRouteName() == 'add.user'|| Route::currentRouteName() == 'user.list' || Route::currentRouteName() == 'user.single.view' || Route::currentRouteName() == 'add.department'|| Route::currentRouteName() == 'user.edit')
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#userLayouts" aria-expanded="true" aria-controls="userLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                    User Management
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse show" id="userLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @else
                <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#userLayouts" aria-expanded="false" aria-controls="userLayouts">
                    <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                    User Management
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="userLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @endif
                    <nav class="sb-sidenav-menu-nested nav ">
{{--                    Only User Related Menu and Submenu is here--}}
                        @include('layouts.back-end.sidebar-components.interface._user_menu_submenu')
{{--                    Only Department Related Menu and Submenu is here--}}
                        @include('layouts.back-end.sidebar-components.interface._department_menu_submenu')
                    </nav>
                </div>
            </subgroup1>{{--#2.2.1.1   Route/URL Chck and set navigation header User Management End--}}
        @endif{{--#2.2.1  Permission Chck User Management End--}}
        </group4>{{--#2.2    User Management End--}}

{{--#2.3    Accounts File Storage System Start--}}
        <group5>
{{--#2.3.1  Permission Chck Accounts File Storage System Start--}}
            @if(auth()->user()->hasPermission('add_voucher_type') || auth()->user()->hasPermission('edit_voucher_type') || auth()->user()->hasPermission('delete_voucher_type') || auth()->user()->hasPermission('add_voucher_document') || auth()->user()->hasPermission('edit_voucher_document') || auth()->user()->hasPermission('add_fr_document'))
{{--#2.3.1.1   Route/URL Chck and set navigation header Accounts File Storage Start--}}
                <subgroup1>
                @if(Route::currentRouteName() == 'add.voucher.info' || Route::currentRouteName() == 'add.voucher.type' || Route::currentRouteName() == 'edit.voucher.type' || Route::currentRouteName() == 'uploaded.voucher.list' || Route::currentRouteName() == 'add.bill.info' || Route::currentRouteName() == 'add.fr.info')
                    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#accLayouts" aria-expanded="true" aria-controls="accLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        Accounts File
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="accLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                @else
                    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#accLayouts" aria-expanded="false" aria-controls="accLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        Accounts File
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="accLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                @endif
                        <nav class="sb-sidenav-menu-nested nav ">
{{--                    Only Accounts Document related menu and submenu--}}
                        @include('layouts.back-end.sidebar-components.interface.accounts._type_menu_submenu')
{{--                    Only Document Upload related menu and submenu Here--}}
                        @include('layouts.back-end.sidebar-components.interface.accounts._add_document_menu_submenu')
{{--                    Only List of Documet related menu and submenu Here--}}
                        @include('layouts.back-end.sidebar-components.interface.accounts._uploded_document_list_menu_submenu')
                        </nav>
                    </div>
                </subgroup1>{{--#2.3.1.1   Route/URL Chck and set navigation header Accounts File Storage End--}}
            @endif {{--#2.3.1  Permission Chck Accounts File Storage System End--}}
        </group5>{{--#2.3    Accounts File Storage System End--}}


{{--Complain section--}}
            @if(Route::currentRouteName() == 'add.complain' || Route::currentRouteName() == 'individual.list.complain'|| Route::currentRouteName() == 'my.list.complain'|| Route::currentRouteName() == 'single.view.complain' || Route::currentRouteName() == 'edit.me.complain' || Route::currentRouteName() == 'my.complain.trash.list' || Route::currentRouteName() == 'departmental.list.complain')
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="true" aria-controls="collapseLayouts">
            @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
            @endif
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                Complains
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            @if(Route::currentRouteName() == 'add.complain' || Route::currentRouteName() == 'individual.list.complain'|| Route::currentRouteName() == 'my.list.complain'|| Route::currentRouteName() == 'single.view.complain' || Route::currentRouteName() == 'edit.me.complain' || Route::currentRouteName() == 'my.complain.trash.list' || Route::currentRouteName() == 'departmental.list.complain')
                <div class="collapse show" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @else
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @endif
                <nav class="sb-sidenav-menu-nested nav ">
                @if(Route::currentRouteName() == 'add.complain')
                        <a class="nav-link" href="{{route('add.complain')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
                @else
                    <a class="nav-link text-chl" href="{{route('add.complain')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
                @endif

                @if(Route::currentRouteName() == 'my.list.complain')
                    <a class="nav-link" href="{{route("my.list.complain")}}"> <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> My List</a>
                @else
                    <a class="nav-link text-chl" href="{{route("my.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> My List</a>
                @endif
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
                            @if(Route::currentRouteName() == 'individual.list.complain')
                                <a class="nav-link" href="{{route("individual.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Individual List</a>
                            @else
                                <a class="nav-link text-chl" href="{{route("individual.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Individual List</a>
                            @endif
                            @if(Route::currentRouteName() == 'departmental.list.complain')
                                <a class="nav-link" href="{{route("departmental.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div> Dept. List</a>
                            @else
                                <a class="nav-link text-chl" href="{{route("departmental.list.complain")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-ol"></i></div> Dept. List</a>
                            @endif
                        </nav>
                    </div>

                @if(Route::currentRouteName() == 'single.view.complain')
                    <a class="nav-link" href="{{route("single.view.complain",['complainID'=>\Illuminate\Support\Facades\Request::route('complainID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-eye"></i></div> Complain View</a>
                @endif
                @if(Route::currentRouteName() == 'edit.me.complain')
                    <a class="nav-link" href="{{route("edit.me.complain",['complainID'=>\Illuminate\Support\Facades\Request::route('complainID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Complain Edit</a>
                @endif
{{--Received List End--}}
                @if(Route::currentRouteName() == 'my.complain.trash.list')
                    <a class="nav-link" href="{{route("my.complain.trash.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-trash"></i></div> My Trash List</a>
                @else
                    <a class="nav-link text-chl" href="{{route("my.complain.trash.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-trash"></i></div> My Trash List</a>
                @endif
                </nav>
            </div>
            @if(Route::currentRouteName() == 'add.number' )
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#simLayouts" aria-expanded="true" aria-controls="simLayouts">
            @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#simLayouts" aria-expanded="false" aria-controls="simLayouts">
            @endif
                <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
                Mobile SIM
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
        @if(Route::currentRouteName() == 'add.number')
            <div class="collapse show" id="simLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
        @else
            <div class="collapse" id="simLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
        @endif
                <nav class="sb-sidenav-menu-nested nav ">
                @if(Route::currentRouteName() == 'add.number')
                        <a class="nav-link" href="{{route('add.number')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
                @else
                    <a class="nav-link text-chl" href="{{route('add.number')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add New</a>
                @endif
                </nav>
            </div>
{{--            <div class="sb-sidenav-menu-heading">Addons</div>--}}
{{--            <a class="nav-link text-chl" href="charts.html">--}}
{{--                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>--}}
{{--                Charts--}}
{{--            </a>--}}
{{--            <a class="nav-link text-chl" href="tables.html">--}}
{{--                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>--}}
{{--                Tables--}}
{{--            </a>--}}
        </div>
    </div>
    <div class="sb-sidenav-footer text-chl">
        <div class="small">
            Welcome Mr./Ms. {{\Illuminate\Support\Facades\Auth::user()->name}}
        </div>
        <div class="small">Logged in as: {!! \Illuminate\Support\Facades\Auth::user()->roles->first()->display_name !!}</div>
        {{config('app.name')}}
    </div>
</nav>
