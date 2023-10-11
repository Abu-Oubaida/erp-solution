@if(Route::currentRouteName() == 'permission.input' )
    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#programmerLayouts" aria-expanded="true" aria-controls="programmerLayouts">
        @else
            <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#programmerLayouts" aria-expanded="false" aria-controls="programmerLayouts">
                @endif
                <div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div>
                For Programmer
                <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
            </a>
            @if(Route::currentRouteName() == 'permission.input')
                <div class="collapse show" id="programmerLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                    @else
                        <div class="collapse" id="programmerLayouts" aria-labelledby="headingOne" data-bs-parent="#sidenavAccordion">
                            @endif
                            <nav class="sb-sidenav-menu-nested nav ">
                                @if(Route::currentRouteName() == 'permission.input')
                                    <a class="nav-link" href="{{route('permission.input')}}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Permission Input</a>
                                @else
                                    <a class="nav-link text-chl" href="{!! route('permission.input') !!}"><div class="sb-nav-link-icon"><i class="fa-solid fa-circle-plus"></i></div> Permission Input</a>
                                @endif
                            </nav>
                        </div>
