<nav class="sb-sidenav accordion " id="sidenavAccordion" style="background: #f0ffff47;">
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
            <div class="sb-sidenav-menu-heading">Interface</div>
            @if(Route::currentRouteName() == 'add.complain' || Route::currentRouteName() == 'list.complain')
                <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="true" aria-controls="collapseLayouts">
            @else
                <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#collapseLayouts" aria-expanded="false" aria-controls="collapseLayouts">
            @endif
                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                Complains
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            @if(Route::currentRouteName() == 'add.complain' || Route::currentRouteName() == 'list.complain')
                <div class="collapse show" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @else
                <div class="collapse" id="collapseLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
            @endif
                <nav class="sb-sidenav-menu-nested nav ">
                @if(Route::currentRouteName() == 'add.complain')
                    <a class="nav-link" href="{{route('add.complain')}}">New Complain</a>
                @else
                    <a class="nav-link text-chl" href="{{route('add.complain')}}">New Complain</a>
                @endif
                @if(Route::currentRouteName() == 'list-complain')
                        <a class="nav-link" href="{{route("list.complain")}}">Complain List</a>
                @else
                        <a class="nav-link text-chl" href="{{route("list.complain")}}">Complain List</a>
                @endif


                </nav>
            </div>
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#collapsePages" aria-expanded="false" aria-controls="collapsePages">
                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                Pages
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            <div class="collapse" id="collapsePages" aria-labelledby="headingTwo" data-bs-parent="#sidenavAccordion">
                <nav class="sb-sidenav-menu-nested nav accordion" id="sidenavAccordionPages">
                    <a class="nav-link collapsed" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseAuth" aria-expanded="false" aria-controls="pagesCollapseAuth">
                        Authentication
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseAuth" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link text-chl" href="login.html">Login</a>
                            <a class="nav-link text-chl" href="register.html">Register</a>
                            <a class="nav-link text-chl" href="password.html">Forgot Password</a>
                        </nav>
                    </div>
                    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#pagesCollapseError" aria-expanded="false" aria-controls="pagesCollapseError">
                        Error
                        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                    </a>
                    <div class="collapse" id="pagesCollapseError" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordionPages">
                        <nav class="sb-sidenav-menu-nested nav">
                            <a class="nav-link text-chl" href="401.html">401 Page</a>
                            <a class="nav-link text-chl" href="404.html">404 Page</a>
                            <a class="nav-link text-chl" href="500.html">500 Page</a>
                        </nav>
                    </div>
                </nav>
            </div>
            <div class="sb-sidenav-menu-heading">Addons</div>
            <a class="nav-link text-chl" href="charts.html">
                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                Charts
            </a>
            <a class="nav-link text-chl" href="tables.html">
                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                Tables
            </a>
        </div>
    </div>
    <div class="sb-sidenav-footer text-chl">
        <div class="small">Logged in as:</div>
        {{config('app.name')}}
    </div>
</nav>
