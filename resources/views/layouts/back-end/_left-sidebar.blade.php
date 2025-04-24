<nav class="sb-sidenav accordion " id="sidenavAccordion" style="background: #f0ffffde;">
    <div class="sb-sidenav-menu">
        <div class="nav">
            {{-- #1.0 Core Start --}}
            <group1>
                <div class="sb-sidenav-menu-heading">Core</div>
                @include('layouts.back-end.sidebar-components._core')
            </group1>{{-- #1.0Core End --}}

            {{-- #2.0 Interface Start --}}
            <group2>
                <div class="sb-sidenav-menu-heading">Interface</div>
            </group2>{{-- #2.0Interface End --}}

            {{-- #2.1 Super Admin Components Start --}}
            <group3>
                @if (\Illuminate\Support\Facades\Auth::user()->hasRole('systemsuperadmin'))
                    @include('layouts.back-end.sidebar-components.interface._only_super_admin')
                @endif
            </group3>{{-- #2.1 Super Admin Components End --}}
            {{-- #2.2    User Management Start --}}
            <group4>
                {{-- #2.2.1  Permission Chck User Management Start --}}
                @if (auth()->user()->hasPermission('user_management'))
                    {{-- #2.2.1.1   Route/URL Chck and set navigation header User Management Start --}}
                    <subgroup1>
                        @if (Route::currentRouteName() == 'add.user' ||
                                Route::currentRouteName() == 'user.list' ||
                                Route::currentRouteName() == 'user.single.view' ||
                                Route::currentRouteName() == 'add.department' ||
                                Route::currentRouteName() == 'user.edit' ||
                                Route::currentRouteName() == 'add.designation' ||
                                Route::currentRouteName() == 'edit.designation' ||
                                Route::currentRouteName() == 'designation.list' ||
                                Route::currentRouteName() == 'add.branch' ||
                                Route::currentRouteName() == 'edit.branch' ||
                                Route::currentRouteName() == 'branch.list' ||
                                Route::currentRouteName() == 'branch.type.list' ||
                                Route::currentRouteName() == 'add.branch.type' ||
                                Route::currentRouteName() == 'edit.branch.type' ||
                                Route::currentRouteName() == 'edit.department' ||
                                Route::currentRouteName() == 'add.role' ||
                                Route::currentRouteName() == 'edit.role' ||
                                Route::currentRouteName() == 'role.list' ||
                                Route::currentRouteName() == 'delete.role' ||
                                Route::currentRouteName() == 'user.screen.permission' ||
                                Route::currentRouteName() == 'file.manager.permission')
                            <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#userLayouts"
                                aria-expanded="true" aria-controls="userLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                                User Management
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse show" id="userLayouts" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                            @else
                                <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                                    data-bs-target="#userLayouts" aria-expanded="false" aria-controls="userLayouts">
                                    <div class="sb-nav-link-icon"><i class="fas fa-user-group"></i></div>
                                    User Management
                                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                                </a>
                                <div class="collapse" id="userLayouts" aria-labelledby="headingOne"
                                    data-bs-parent="#sidenavAccordion">
                        @endif
                        <nav class="sb-sidenav-menu-nested nav ">
                            {{--                    Only User Related Menu and Submenu is here --}}
                            @include('layouts.back-end.sidebar-components.interface._user_menu_submenu')
                            {{--                    Only Department Related Menu and Submenu is here --}}
                            @include('layouts.back-end.sidebar-components.interface._department_menu_submenu')
                            {{--                    Only Designaton Related Menu and Submenu is here --}}
                            @include('layouts.back-end.sidebar-components.interface._designation_menu_submenu')
                            @include('layouts.back-end.sidebar-components.interface._branch_menu_submenu')
                            @if (auth()->user()->hasPermission('role_management'))
                                @include('layouts.back-end.sidebar-components.interface._role_management')
                            @endif
                        </nav>
        </div>
        </subgroup1>{{-- #2.2.1.1   Route/URL Chck and set navigation header User Management End --}}
        @endif{{-- #2.2.1  Permission Chck User Management End --}}
        </group4>{{-- #2.2    User Management End --}}

        {{-- #2.3    Accounts File Storage System Start --}}
        <group5>
            {{-- #2.3.1  Permission Chck Accounts File Storage System Start --}}
            @if (auth()->user()->hasPermission('salary_certificate_input') ||
                    auth()->user()->hasPermission('salary_certificate_list') ||
                    auth()->user()->hasPermission('salary_certificate_view'))
                {{-- #2.3.1.1   Route/URL Chck and set navigation header Accounts File Storage Start --}}
                <subgroup1>
                    @if (Route::currentRouteName() == 'input.salary.certificate' ||
                            Route::currentRouteName() == 'salary.certificate.list' ||
                            Route::currentRouteName() == 'salary.certificate.view')
                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#archiveLayouts"
                            aria-expanded="true" aria-controls="accLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                            Accounts
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="accLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                        @else
                            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                                data-bs-target="#accLayouts" aria-expanded="false" aria-controls="accLayouts">
                                <div class="sb-nav-link-icon"><i class="fas fa-file-invoice-dollar"></i></div>
                                Accounts
                                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                            </a>
                            <div class="collapse" id="accLayouts" aria-labelledby="headingOne"
                                data-bs-parent="#sidenavAccordion">
                    @endif
                    <nav class="sb-sidenav-menu-nested nav ">
                        {{--                    Only Accounts Document related menu and submenu --}}
                        @include('layouts.back-end.sidebar-components.interface.accounts._add_menu_submenu')
                        {{--                    Only List of Documet related menu and submenu Here --}}
                        @include('layouts.back-end.sidebar-components.interface.accounts._view_list_menu_submenu')
                    </nav>
    </div>
    </subgroup1>{{-- #2.3.1.1   Route/URL Chck and set navigation header Accounts File Storage End --}}
    @endif {{-- #2.3.1  Permission Chck Accounts File Storage System End --}}
    </group5>{{-- #2.3    Accounts File Storage System End --}}

    {{-- #2.4    Complain section Start --}}
    <group6>
        {{-- #2.4.1  Permission Chck Complain section Start --}}
        @if (auth()->user()->hasPermission('add_complain') ||
                auth()->user()->hasPermission('delete_complain') ||
                auth()->user()->hasPermission('edit_complain') ||
                auth()->user()->hasPermission('list_complain_all') ||
                auth()->user()->hasPermission('list_department_complain_all') ||
                auth()->user()->hasPermission('list_my_complain') ||
                auth()->user()->hasPermission('list_my_complain_trash') ||
                auth()->user()->hasPermission('view_complain_single'))
            {{-- #2.4.1.1   Route/URL Chck and set navigation header Complain section Start --}}
            <subgroup1>
                @if (Route::currentRouteName() == 'add.complain' ||
                        Route::currentRouteName() == 'individual.list.complain' ||
                        Route::currentRouteName() == 'my.list.complain' ||
                        Route::currentRouteName() == 'single.view.complain' ||
                        Route::currentRouteName() == 'edit.my.complain' ||
                        Route::currentRouteName() == 'my.complain.trash.list' ||
                        Route::currentRouteName() == 'departmental.list.complain')
                    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts"
                        aria-expanded="true" aria-controls="collapseLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                        Complains
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="collapseLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                            data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                            Complains
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                @endif
                <nav class="sb-sidenav-menu-nested nav ">
                    @include('layouts.back-end.sidebar-components.interface._complain_menu_submenu')
                </nav>
                </div>
            </subgroup1>{{-- #2.4.1.1   Route/URL Chck and set navigation header Complain section End --}}
        @endif{{-- #2.4.1  Permission Chck Complain section End --}}
    </group6>{{-- #2.4    Complain section End --}}

    {{-- #2.5    Mobile Sim section Start --}}
    <group7>
        {{-- #2.5.1  Permission Chck Mobile SIM section Start --}}
        @if (auth()->user()->hasPermission('add_sim_number'))
            {{-- #2.5.1.1   Route/URL Chck and set navigation header Mobile SIM section Start --}}
            <subgroup1>
                @if (Route::currentRouteName() == 'add.number')
                    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#simLayouts"
                        aria-expanded="true" aria-controls="simLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
                        Mobile SIM
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="simLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                            data-bs-target="#simLayouts" aria-expanded="false" aria-controls="simLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
                            Mobile SIM
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="simLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                @endif
                <nav class="sb-sidenav-menu-nested nav ">
                    @include('layouts.back-end.sidebar-components.interface._mobile_sim_menu_submenu')
                </nav>
                </div>
            </subgroup1>{{-- #2.5.1.1   Route/URL Chck and set navigation header Mobile SIM section End --}}
        @endif
    </group7>{{-- #2.5    Mobile Sim section End --}}

    {{-- #2.6    Sales Interface section Start --}}
    <group8>
        {{-- #2.6.1  Permission Chck Sales Interface section Start --}}
        @if (auth()->user()->hasPermission('sales_interface'))
            <subgroup1>
                @if (Route::currentRouteName() == 'sales.dashboard.interface' ||
                        Route::currentRouteName() == 'add.sales.lead' ||
                        Route::currentRouteName() == 'sales.lead.list'||Route::currentRouteName() == 'sale.settings.interface')
                    <a class="nav-link" href="#" data-bs-toggle="collapse"
                        data-bs-target="#salesInterfaceLayouts" aria-expanded="true"
                        aria-controls="salesInterfaceLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-solid fa-magnifying-glass-dollar"></i></div>
                        Sales CRM
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="salesInterfaceLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                            data-bs-target="#salesInterfaceLayouts" aria-expanded="false"
                            aria-controls="salesInterfaceLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-magnifying-glass-dollar"></i>
                            </div>
                            Sales CRM
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="salesInterfaceLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                @endif
                <nav class="sb-sidenav-menu-nested nav ">
                    {{-- Sales Interface dashboard here --}}
                    @include('layouts.back-end.sidebar-components.interface.sales._dashboard')
                    @include('layouts.back-end.sidebar-components.interface.sales._lead_menu_submenu')
                    @if (auth()->user()->hasPermission('sales_dashboard_interface'))
                        @if (Route::currentRouteName() == 'sale.settings.interface')
                            {{-- {{route('sale_settings_interface')}} --}}
                            <a class="nav-link" href="{{ route('sale.settings.interface') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                Settings
                            </a>
                        @else
                            <a class="nav-link text-chl" href="{{ route('sale.settings.interface') }}">
                                <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                                Settings
                            </a>
                        @endif
                    @endif
                    {{-- Sales Interface dashboard is here --}}
                </nav>
                </div>
            </subgroup1>
            {{-- #2.6.1.1   Route/URL Chck and set navigation header Sales Dashboard here --}}
            {{--                    @if (auth()->user()->hasPermission('sales_dashboard_interface')) --}}
            {{--                    @endif --}}
        @endif
    </group8>{{-- #2.6    Mobile Sim section End --}}

    {{-- #2.7    Fixed asset section Start --}}
    <group9>
        {{-- #2.6.1  Permission Chck Sales Interface section Start --}}
        @if (auth()->user()->hasPermission('fixed_asset_interface'))
            <subgroup1>
                @if (Request::segment(1) == 'fixed-asset' ||
                        Request::segment(1) == 'fixed-asset-distribution' ||
                        Request::segment(1) == 'fixed-asset-report')
                    <a class="nav-link" href="#" data-bs-toggle="collapse"
                        data-bs-target="#AssetInterfaceLayouts" aria-expanded="true"
                        aria-controls="AssetInterfaceLayouts">
                        <div class="sb-nav-link-icon"><i class="fas fa-solid fa-a"></i><i
                                class="fas fa-solid fa-m"></i></div>
                        Asset Management
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="AssetInterfaceLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                            data-bs-target="#AssetInterfaceLayouts" aria-expanded="false"
                            aria-controls="AssetInterfaceLayouts">
                            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-a"></i><i
                                    class="fas fa-solid fa-m"></i></div>
                            Asset Management
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="AssetInterfaceLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                @endif
                <nav class="sb-sidenav-menu-nested nav ">
                    {{-- Sales Interface dashboard here --}}
                    {{--                                    @include('layouts.back-end.sidebar-components.interface.sales._dashboard') --}}
                    @include('layouts.back-end.sidebar-components.interface._asset_menu_submenu')
                    {{-- Sales Interface dashboard is here --}}
                </nav>
                </div>
            </subgroup1>
            {{-- #2.6.1.1   Route/URL Chck and set navigation header Sales Dashboard here --}}
            {{--                    @if (auth()->user()->hasPermission('sales_dashboard_interface')) --}}
            {{--                    @endif --}}
        @endif
    </group9>{{-- #2.6    Mobile Sim section End --}}
    <group10>
        {{-- #2.6.1  Permission Chck Sales Interface section Start --}}
        @if (auth()->user()->hasPermission('requisition'))
            <subgroup1>
                @if (Request::segment(1) == 'requisition')
                    <a class="nav-link" href="#" data-bs-toggle="collapse"
                        data-bs-target="#RequisitionInterfaceLayouts" aria-expanded="true"
                        aria-controls="RequisitionInterfaceLayouts">
                        <div class="sb-nav-link-icon"><i class="fa-solid fa-hand"></i></div>
                        Requisition
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse show" id="RequisitionInterfaceLayouts" aria-labelledby="headingOne"
                        data-bs-parent="#sidenavAccordion">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                            data-bs-target="#RequisitionInterfaceLayouts" aria-expanded="false"
                            aria-controls="RequisitionInterfaceLayouts">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-hand"></i></div>
                            Requisition
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="RequisitionInterfaceLayouts" aria-labelledby="headingOne"
                            data-bs-parent="#sidenavAccordion">
                @endif
                <nav class="sb-sidenav-menu-nested nav ">
                    {{-- Sales Interface dashboard here --}}
                    {{--                                    @include('layouts.back-end.sidebar-components.interface.sales._dashboard') --}}
                    @include('layouts.back-end.sidebar-components.interface.requisition._requisition_menu_submenu')
                    {{-- Sales Interface dashboard is here --}}
                </nav>
                </div>
            </subgroup1>
            {{-- #2.6.1.1   Route/URL Chck and set navigation header Sales Dashboard here --}}
            {{--                    @if (auth()->user()->hasPermission('sales_dashboard_interface')) --}}
            {{--                    @endif --}}
        @endif
    </group10>{{-- #2.6    Mobile Sim section End --}}
    <group5>
        @if (auth()->user()->hasPermission('data_archive'))
            @if (Route::currentRouteName() == 'add.archive.type' ||
                    Route::currentRouteName() == 'archive.data.type.list' ||
                    Route::currentRouteName() == 'edit.archive.type' ||
                    Route::currentRouteName() == 'add.archive.info' ||
                    Route::currentRouteName() == 'edit.archive.info' ||
                    Route::currentRouteName() == 'uploaded.archive.list' ||
                    Route::currentRouteName() == 'view.archive.document' ||
                    Route::currentRouteName() == 'uploaded.archive.list.quick' ||
                    Route::currentRouteName() == 'data.archive.dashboard.interface' ||
                    Route::currentRouteName() == 'data.archive.setting')
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pagesUploadOption"
                    aria-expanded="true" aria-controls="pagesUploadOption">
                    <div class="sb-nav-link-icon"><i class="fas fa-receipt" aria-hidden="true"></i></div>
                    Data Archive
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse show" id="pagesUploadOption" aria-labelledby="headingOne"
                    data-bs-parent="#pagesUploadOption">
                @else
                    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse"
                        data-bs-target="#pagesUploadOption" aria-expanded="false" aria-controls="pagesUploadOption">
                        <div class="sb-nav-link-icon"><i class="fas fa-receipt" aria-hidden="true"></i></div>
                        Data Archive
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesUploadOption" aria-labelledby="headingOne"
                        data-bs-parent="#pagesUploadOption">
            @endif
            <nav class="sb-sidenav-menu-nested nav">
                @if (auth()->user()->hasPermission('archive_dashboard'))

                    @if (Route::currentRouteName() == 'data.archive.dashboard.interface')
                        <a class="nav-link" href="{{ route('data.archive.dashboard.interface') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                    @else
                        <a class="nav-link text-chl" href="{{ route('data.archive.dashboard.interface') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                            Dashboard
                        </a>
                    @endif
                @endif
                {{--            Upload Voucher Permission Check Start --}}
                @if (auth()->user()->hasPermission('add_archive_data_type'))
                    @if (Route::currentRouteName() == 'add.archive.type')
                        <a class="nav-link" href="{{ route('add.archive.type') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Data Type Add
                        </a>
                    @else
                        <a class="nav-link text-chl" href="{{ route('add.archive.type') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div>Data Type Add
                        </a>
                    @endif
                @endif
                {{--            Upload Voucher Permission Check Start --}}
                @if (auth()->user()->hasPermission('archive_data_type_list'))
                    @if (Route::currentRouteName() == 'archive.data.type.list')
                        <a class="nav-link" href="{!! route('archive.data.type.list') !!}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div> Data Type List
                        </a>
                    @else
                        <a class="nav-link text-chl" href="{!! route('archive.data.type.list') !!}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div>Data Type List
                        </a>
                    @endif
                @endif
                {{--            Upload Voucher Permission Check End --}}
                @if (auth()->user()->hasPermission('edit_archive_data_type'))
                    @if (Route::currentRouteName() == 'edit.archive.type')
                        <a class="nav-link"
                            href="{{ route('edit.archive.type', ['archiveTypeID' => \Illuminate\Support\Facades\Request::route('archiveTypeID')]) }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-edit"></i></div> Data Type Edit
                        </a>
                    @endif
                @endif
                {{--                                    Upload Voucher Permission Check Start --}}
                @if (auth()->user()->hasPermission('archive_document_upload'))
                    @if (Route::currentRouteName() == 'add.archive.info')
                        <a class="nav-link" href="{{ route('add.archive.info') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-upload"></i></div> Data Upload
                        </a>
                    @else
                        <a class="nav-link text-chl" href="{{ route('add.archive.info') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-upload"></i></div> Data Upload
                        </a>
                    @endif
                @endif
                @if (auth()->user()->hasPermission('archive_document_edit'))
                    @if (Route::currentRouteName() == 'edit.archive.info')
                        <a class="nav-link"
                            href="{{ route('edit.archive.info', ['archiveDocumentID' => \Illuminate\Support\Facades\Request::route('archiveDocumentID')]) }}">
                            <div class="sb-nav-link-icon" title="Edit Archive Info"><i class="fas fa-edit"></i></div>
                            Edit Document
                        </a>
                    @endif
                @endif
                {{--                                    Upload Voucher Permission Check End --}}
                @if (auth()->user()->hasPermission('archive_data_list'))
                    @if (Route::currentRouteName() == 'uploaded.archive.list')
                        <a class="nav-link" href="{{ route('uploaded.archive.list') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div> Uploaded List
                        </a>
                    @else
                        <a class="nav-link text-chl" href="{{ route('uploaded.archive.list') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-list"></i></div> Uploaded List
                        </a>
                    @endif
                @endif
                @if (auth()->user()->hasPermission('archive_data_list_quick'))
                    @if (Route::currentRouteName() == 'uploaded.archive.list.quick')
                        <a class="nav-link" href="{{ route('uploaded.archive.list.quick') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-truck-fast"></i></div> Quick List
                        </a>
                    @else
                        <a class="nav-link text-chl" href="{{ route('uploaded.archive.list.quick') }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-truck-fast"></i></div> Quick List
                        </a>
                    @endif
                @endif
                @if (auth()->user()->hasPermission('archive_document_view'))
                    @if (Route::currentRouteName() == 'view.archive.document')
                        <a class="nav-link"
                            href="{{ route('view.archive.document', ['vID' => \Illuminate\Support\Facades\Request::route('vID')]) }}">
                            <div class="sb-nav-link-icon"><i class="fa-solid fa-receipt"></i></div> Document View
                        </a>
                    @endif
                @endif
                @if (auth()->user()->hasPermission('archive_setting'))

                    @if (Route::currentRouteName() == 'data.archive.setting')
                        <a class="nav-link" href="{{ route('data.archive.setting') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                            Settings
                        </a>
                    @else
                        <a class="nav-link text-chl" href="{{ route('data.archive.setting') }}">
                            <div class="sb-nav-link-icon"><i class="fas fa-cog"></i></div>
                            Settings
                        </a>
                    @endif
                @endif
            </nav>
            </div>
        @endif {{-- Upload Option End Here --}}
    </group5>{{-- #2.3    Accounts File Storage System End --}}
    </div>
    </div>
    <div class="sb-sidenav-footer text-chl">
        <div class="small">
            Welcome Mr./Ms. {{ \Illuminate\Support\Facades\Auth::user()->name }}
        </div>
        {{--        <div class="small">Logged in as: {!! \Illuminate\Support\Facades\Auth::user()->roles->first()->display_name !!}</div> --}}
        <div class="small">Logged in as: <span class="text-capitalize">{!! \Illuminate\Support\Facades\Auth::user()->companyWiseRoleName() !!}</span></div>
        <a href="https://github.com/abuoubaida" class="text-decoration-none text-chl"
            title="Abu Oubaida, MIS Dept.">Oubaida
            ❤️
        </a>{{ config('app.name') }} {{ date('Y') }}
    </div>
</nav>
