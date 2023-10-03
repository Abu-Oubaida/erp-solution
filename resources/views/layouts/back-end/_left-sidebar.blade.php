<nav class="sb-sidenav accordion " id="sidenavAccordion" style="background: #f0ffffde;">
    <div class="sb-sidenav-menu">
        <div class="nav">
            <div class="sb-sidenav-menu-heading">Core</div>
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
            <a class="nav-link text-chl" href="{{route('file-manager')}}">
                <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
                File Manager
            </a>
            <div class="sb-sidenav-menu-heading">Interface</div>
{{--User Management--}}
    @if(\Illuminate\Support\Facades\Auth::user()->hasRole('superadmin'))
        @if(Route::currentRouteName() == 'add.user'|| Route::currentRouteName() == 'user.list' || Route::currentRouteName() == 'user.single.view' || Route::currentRouteName() == 'add.department'|| Route::currentRouteName() == 'user.edit')
            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#userLayouts" aria-expanded="true" aria-controls="userLayouts">
        @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#userLayouts" aria-expanded="false" aria-controls="userLayouts">
        @endif
                <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                User Management
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            @if(Route::currentRouteName() == 'add.user' || Route::currentRouteName() == 'user.list' || Route::currentRouteName() == 'user.single.view' || Route::currentRouteName() == 'add.department'|| Route::currentRouteName() == 'user.edit')
                <div class="collapse show" id="userLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @else
                <div class="collapse" id="userLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @endif
                    <nav class="sb-sidenav-menu-nested nav ">
                        @if(Route::currentRouteName() == 'add.user')
                            <a class="nav-link" href="{{route('add.user')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add User</a>
                        @else
                            <a class="nav-link text-chl" href="{{route('add.user')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Add User</a>
                        @endif

                        @if(Route::currentRouteName() == 'user.list')
                            <a class="nav-link" href="{{route("user.list")}}"> <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> User List</a>
                        @else
                            <a class="nav-link text-chl" href="{{route("user.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> User List</a>
                        @endif
                        @if(Route::currentRouteName() == 'user.single.view')
                            <a class="nav-link" href="{{route("user.single.view",['userID'=>\Illuminate\Support\Facades\Request::route('userID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div> User Profile</a>
                        @endif
                        @if(Route::currentRouteName() == 'user.edit')
                            <a class="nav-link" href="{{route("user.edit",['userID'=>\Illuminate\Support\Facades\Request::route('userID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div> User Profile</a>
                        @endif
{{--                        Department--}}
                        @if(Route::currentRouteName() == 'add.department')
                            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#department" aria-expanded="true" aria-controls="department">
                                <div class="sb-nav-link-icon"><i class="fas fa-table-list"></i></div>
                                Department
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse show" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        @else
                            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#department" aria-expanded="false" aria-controls="department">
                                <div class="sb-nav-link-icon"><i class="fas fa-table-list"></i></div>
                                Department
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="department" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        @endif
                                <nav class="sb-sidenav-menu-nested nav">
                                    @if(Route::currentRouteName() == 'add.department')
                                        <a class="nav-link" href="{{route("add.department")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Add New</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route("add.department")}}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div> Add New</a>
                                    @endif
                                </nav>
                            </div>
                    </nav>
                </div>
    @endif
{{--Accounts File Storage System--}}
                @if(Route::currentRouteName() == 'add.voucher.info' || Route::currentRouteName() == 'add.voucher.type' || Route::currentRouteName() == 'edit.voucher.type' || Route::currentRouteName() == 'uploaded.voucher.list' || Route::currentRouteName() == 'add.bill.info' || Route::currentRouteName() == 'add.fr.info')
                    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#accLayouts" aria-expanded="true" aria-controls="accLayouts">
                @else
                    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#accLayouts" aria-expanded="false" aria-controls="accLayouts">
                @endif
                        <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                        Accounts File
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    @if(Route::currentRouteName() == 'add.voucher.info' || Route::currentRouteName() == 'add.voucher.type' || Route::currentRouteName() == 'edit.voucher.type' || Route::currentRouteName() == 'uploaded.voucher.list' || Route::currentRouteName() == 'add.bill.info' || Route::currentRouteName() == 'add.fr.info')
                        <div class="collapse show" id="accLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    @else
                        <div class="collapse" id="accLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    @endif
                            <nav class="sb-sidenav-menu-nested nav ">
                                @if(Route::currentRouteName() == 'add.voucher.type')
                                    <a class="nav-link" href="{{route('add.voucher.type')}}">
                                        <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Voucher Type
                                    </a>
                                @else
                                    <a class="nav-link text-chl" href="{{route('add.voucher.type')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Voucher Type</a>
                                @endif

                                @if(Route::currentRouteName() == 'edit.voucher.type')
                                    <a class="nav-link" href="{{route("edit.voucher.type",['voucherTypeID'=>\Illuminate\Support\Facades\Request::route('voucherTypeID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Voucher Type Edit</a>
                                @endif

                                @if(Route::currentRouteName() == 'add.voucher.info' || Route::currentRouteName() == 'add.bill.info' || Route::currentRouteName() == 'add.fr.info')
                                    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pagesUploadOption" aria-expanded="true" aria-controls="pagesUploadOption">
                                        <div class="sb-nav-link-icon"><i class="fas fa-upload" aria-hidden="true"></i></div>
                                        Upload Option
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse show" id="pagesUploadOption" aria-labelledby="headingOne" data-bs-parent="#pagesUploadOption">
                                @else
                                    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#pagesUploadOption" aria-expanded="false" aria-controls="pagesUploadOption">
                                        <div class="sb-nav-link-icon"><i class="fas fa-upload" aria-hidden="true"></i></div>
                                        Upload Option
                                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                    </a>
                                    <div class="collapse" id="pagesUploadOption" aria-labelledby="headingOne" data-bs-parent="#pagesUploadOption">
                                @endif
                                        <nav class="sb-sidenav-menu-nested nav">
                                            @if(Route::currentRouteName() == 'add.voucher.info')
                                                <a class="nav-link" href="{{route('add.voucher.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Voucher</a>
                                            @else
                                                <a class="nav-link text-chl" href="{{route('add.voucher.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Voucher</a>
                                            @endif

                                            @if(Route::currentRouteName() == 'add.fr.info')
                                                <a class="nav-link" href="{{route('add.fr.info')}}"><div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div> FR</a>
                                            @else
                                                <a class="nav-link text-chl" href="{{route('add.fr.info')}}"><div class="sb-nav-link-icon"><i class="fas fa-dollar-sign"></i></div> FR</a>
                                            @endif

                                            @if(Route::currentRouteName() == 'add.bill.info')
                                                <a class="nav-link" href="{{route('add.bill.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div> Bill</a>
                                            @else
                                                <a class="nav-link text-chl" href="{{route('add.bill.info')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-money-bill"></i></div> Bill</a>
                                            @endif
                                        </nav>
                                    </div>

                                @if(Route::currentRouteName() == 'uploaded.voucher.list')
                                    <a class="nav-link" href="{{route("uploaded.voucher.list")}}"> <div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> Voucher List</a>
                                @else
                                    <a class="nav-link text-chl" href="{{route("uploaded.voucher.list")}}"><div class="sb-nav-link-icon"><i class="fas fa-list-check"></i></div> Voucher List</a>
                                @endif
                                @if(Route::currentRouteName() == 'user.single.view')
                                    <a class="nav-link" href="{{route("user.single.view",['userID'=>\Illuminate\Support\Facades\Request::route('userID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div> User Profile</a>
                                @endif
                                @if(Route::currentRouteName() == 'user.edit')
                                    <a class="nav-link" href="{{route("user.edit",['userID'=>\Illuminate\Support\Facades\Request::route('userID')])}}"><div class="sb-nav-link-icon"><i class="fas fa-user"></i></div> User Profile</a>
                                @endif
                            </nav>
                        </div>
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
