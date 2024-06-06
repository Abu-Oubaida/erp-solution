@if(auth()->user()->hasPermission('add_sales_lead') ||auth()->user()->hasPermission('sales_lead_list') )

    @if(Route::currentRouteName() == 'add.sales.lead' || Route::currentRouteName() == 'sales.lead.list')
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#SalesLeadOption" aria-expanded="true" aria-controls="SalesLeadOption">
            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-envelope-circle-check"></i></div>
            Lead
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="SalesLeadOption" aria-labelledby="headingOne" data-bs-parent="#SalesLeadOption">
    @else
        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#SalesLeadOption" aria-expanded="false" aria-controls="SalesLeadOption">
            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-envelope-circle-check"></i></div>
            Lead
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="SalesLeadOption" aria-labelledby="headingOne" data-bs-parent="#SalesLeadOption">
   @endif
            <nav class="sb-sidenav-menu-nested nav">
{{--            Add Lead Permission Check Start--}}
                @if(auth()->user()->hasPermission('add_sales_lead'))
                    @if(Route::currentRouteName() == 'add.sales.lead')
                        <a class="nav-link" href="{{route('add.sales.lead')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-user-plus"></i></div> Add Lead</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('add.sales.lead')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-user-plus"></i></div> Add Lead</a>
                    @endif
                @endif
{{--             Add Lead Permission Check End--}}
{{--            List of Lead Permission Check Start--}}
                @if(auth()->user()->hasPermission('sales_lead_list'))
                    @if(Route::currentRouteName() == 'sales.lead.list')
                        <a class="nav-link" href="{{route('sales.lead.list')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-address-card"></i></div> Lead List</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('sales.lead.list')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-address-card"></i></div> Lead List</a>
                    @endif
                @endif
{{--            List of Lead Permission Check End--}}
            </nav>
        </div>
@endif {{--Upload Option End Here--}}
