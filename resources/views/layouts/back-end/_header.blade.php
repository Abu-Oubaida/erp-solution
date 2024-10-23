<nav class="sb-topnav navbar navbar-expand navbar-dark bg-chl-white">
    <!-- Navbar Brand-->
    <a class="navbar-brand ps-3 text-black" href="{{route('root')}}"><h5><img src="{{url("image/logo/default/icon/360.png")}}" alt="{{str_replace('-', '-', config('app.name'))}}" class="img-fluid" width="20%"> Smart Solution</h5></a>
    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 text-chl-important" id="sidebarToggle"><i class="fas fa-bars"></i></button>
    @if(!empty(\Illuminate\Support\Facades\Auth::user()->companyInfo()->logo))
        <img src="{{url(\Illuminate\Support\Facades\Auth::user()->companyInfo()->logo)
    }}" width="7%" alt="Logo not found">
    @else
        <h3 class="text-chl text-capitalize">{!! \Illuminate\Support\Facades\Auth::user()->companyInfo()->company_name !!}</h3>
    @endif
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
{{--            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />--}}
{{--            <button class="btn btn-primary btn-chl" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>--}}
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 bg-chl" style="border-radius: 10px;">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                <li><a class="dropdown-item @if(Route::currentRouteName() == 'dashboard') active @endif" href="{!! route("dashboard") !!}"><i class="fas fa-user"></i> Profile</a></li>
            @if(auth()->user()->hasPermission('app_setting'))
                <li><a class="dropdown-item @if(Route::currentRouteName() == 'app.setting') active @endif" href="{!! route('app.setting') !!}"><i class="fas fa-cog"></i> App Setting</a></li>
            @endif
    <li><hr class="dropdown-divider" /></li>
    <li><a class="dropdown-item" href="{{route('logout')}}"><i class="fas fa-sign-out"></i> Logout</a></li>
</ul>
</li>
</ul>
</nav>
