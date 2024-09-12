@if(Request::segment(1) == "system-super-admin" )
    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#programmerLayouts" aria-expanded="true" aria-controls="programmerLayouts">
        <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
        System Super Admin
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse show" id="programmerLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
@else
    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#programmerLayouts" aria-expanded="false" aria-controls="programmerLayouts">
        <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
        System Super Admin
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse" id="programmerLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
@endif
        <nav class="sb-sidenav-menu-nested nav ">
            @if(Route::currentRouteName() == 'add.company' || Route::currentRouteName() == 'company.setup' || Route::currentRouteName() == 'edit.company' || Route::currentRouteName() == 'company.list' || Route::currentRouteName() == 'add.company.type'  || Route::currentRouteName() == 'company.type.list' || Route::currentRouteName() == 'edit.company.type')
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#companies" aria-expanded="true" aria-controls="companies">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-city"></i></div>
                    Company Setup
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse show" id="companies" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
            @else
                <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#companies" aria-expanded="false" aria-controls="companies">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-city"></i></div>
                    Company Setup
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="companies" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
            @endif
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(Route::currentRouteName() == 'add.company')
                            <a class="nav-link" href="{{route('add.company')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-file-circle-plus"></i></div> Add Company</a>
                        @else
                            <a class="nav-link text-chl" href="{!! route('add.company') !!}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-file-circle-plus"></i></div> Add Company</a>
                        @endif
                        @if(Route::currentRouteName() == 'company.list')
                            <a class="nav-link" href="{{route('company.list')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-list"></i></div>Show List</a>
                        @else
                            <a class="nav-link text-chl" href="{!! route('company.list') !!}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>Show List</a>
                        @endif
                        @if(Route::currentRouteName() == 'edit.company')
                            <a class="nav-link" href="{{route('edit.company',['companyID'=>\Illuminate\Support\Facades\Request::route('companyID')])}}"><div class="sb-nav-link-icon"><i class='fas fa-edit'></i></div> Company Edit</a>
                        @endif
                        @if(Route::currentRouteName() == 'add.company.type')
                            <a class="nav-link" href="{{route('add.company.type')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Add Type</a>
                        @else
                            <a class="nav-link text-chl" href="{!! route('add.company.type') !!}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Add Type</a>
                        @endif
                        @if(Route::currentRouteName() == 'company.type.list')
                            <a class="nav-link" href="{{route('company.type.list')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-list"></i></div>Type List</a>
                        @else
                            <a class="nav-link text-chl" href="{!! route('company.type.list') !!}"><div class="sb-nav-link-icon"><i class="fas fa-list"></i></div>Type List</a>
                        @endif
                        @if(Route::currentRouteName() == 'edit.company.type')
                            <a class="nav-link" href="{{route('edit.company.type',['companyTypeID'=>\Illuminate\Support\Facades\Request::route('companyTypeID')])}}"><div class="sb-nav-link-icon"><i class='fas fa-edit'></i></div>Type Edit</a>
                        @endif
                    </nav>
                </div>
            @if(Route::currentRouteName() == 'permission.input')
                <a class="nav-link" href="{{route('permission.input')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Permission Input</a>
            @else
                <a class="nav-link text-chl" href="{!! route('permission.input') !!}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Permission Input</a>
            @endif
            @if(Route::currentRouteName() == 'op.reference.type')
                <a class="nav-link" href="{{route('op.reference.type')}}" title="Operation Reference Type"><div class="sb-nav-link-icon"><i class="fa fa-o"></i><i class="fa fa-r"></i><i class="fa fa-t"></i></div> Op. Ref. Type</a>
            @else
                <a class="nav-link text-chl" href="{!! route('op.reference.type') !!}" title="Operation Reference Type"><div class="sb-nav-link-icon"><i class="fa fa-o"></i><i class="fa fa-r"></i><i class="fa fa-t"></i></div> Op. Ref. Type</a>
            @endif
            @if(Route::currentRouteName() == 'op.reference.type.edit')
                <a class="nav-link" href="{{route('op.reference.type.edit',['typeID'=>\Illuminate\Support\Facades\Request::route('typeID')])}}" title="Operation Reference Type"><div class="sb-nav-link-icon"><i class="fa fa-edit"></i></div> Op. Ref. Type Edit</a>
            @endif
            @include('layouts.back-end.sidebar-components.interface._blood_group_menu_submenu')
            @if(Route::currentRouteName() == 'add.role' || Route::currentRouteName() == 'edit.role' || Route::currentRouteName() == 'delete.role' || Route::currentRouteName() == 'role.list')
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#roles" aria-expanded="true" aria-controls="roles">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-city"></i></div>
                    Roles
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse show" id="companies" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
            @else
                <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#roles" aria-expanded="false" aria-controls="roles">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-city"></i></div>
                    Roles
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="roles" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
            @endif
                    <nav class="sb-sidenav-menu-nested nav">
                        @if(Route::currentRouteName() == 'add.role')
                            <a class="nav-link" href="{{route('add.role')}}"><div class="sb-nav-link-icon"><i class="fas fa-users"></i></div> Add Role</a>
                        @else
                            <a class="nav-link text-chl" href="{!! route('add.role') !!}"><div class="sb-nav-link-icon"><i class="fas fa-users"></i></div> Add Role</a>
                        @endif
                    </nav>
                </div>
        </nav>
    </div>
