@if(auth()->user()->hasPermission('salary_certificate_input'))
    @if(Route::currentRouteName() == 'input.salary.certificate')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#pagesAddOption" aria-expanded="true" aria-controls="pagesAddOption">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-file-circle-plus"></i></div>
            Add Option
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="pagesAddOption" aria-labelledby="headingOne" data-bs-parent="#pagesAddOption">
            @else
                <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#pagesAddOption" aria-expanded="false" aria-controls="pagesAddOption">
                    <div class="sb-nav-link-icon"><i class="fa-solid fa-file-circle-plus"></i></div>
                    Add Option
                    <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                </a>
                <div class="collapse" id="pagesAddOption" aria-labelledby="headingOne" data-bs-parent="#pagesAddOption">
                    @endif
                    <nav class="sb-sidenav-menu-nested nav">
                        {{--            Salary Certificate Input Permission Check Start--}}
                        @if(auth()->user()->hasPermission('salary_certificate_input'))
                            @if(Route::currentRouteName() == 'input.salary.certificate')
                                <a class="nav-link" href="{{route('input.salary.certificate')}}" title="Input Salary Certificate">
                                    <div class="sb-nav-link-icon"><i class="fa-regular fa-money-bill-1"></i></div> Salary Cert. Input
                                </a>
                            @else
                                <a class="nav-link text-chl" href="{{route('input.salary.certificate')}}" title="Input Salary Certificate">
                                    <div class="sb-nav-link-icon"><i class="fa-regular fa-money-bill-1"></i></div>Salary Cert. Input
                                </a>
                            @endif
                        @endif
                        {{--            Upload Voucher Permission Check End--}}
                    </nav>
                </div>
    @endif {{--Upload Option End Here--}}

