@if(auth()->user()->hasPermission('fixed_asset') )

    @if(Request::segment(1) == "fixed-asset" )
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetOption" aria-expanded="true" aria-controls="fixedAssetOption">
            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-f"></i><i class="fas fa-solid fa-a"></i></div>
            Fixed Asset
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="fixedAssetOption" aria-labelledby="headingOne" data-bs-parent="#fixedAssetOption">
    @else
        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetOption" aria-expanded="false" aria-controls="fixedAssetOption">
            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-f"></i><i class="fas fa-solid fa-a"></i></div>
            Fixed Asset
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse" id="fixedAssetOption" aria-labelledby="headingOne" data-bs-parent="#fixedAssetOption">
   @endif
            <nav class="sb-sidenav-menu-nested nav">
{{--            Add Lead Permission Check Start--}}
                @if(auth()->user()->hasPermission('add_fixed_asset'))
                    @if(Route::currentRouteName() == 'fixed.asset.add')
                        <a class="nav-link" href="{{route('fixed.asset.add')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Add Materials</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Add Materials</a>
                    @endif
                @endif
{{--             Add Lead Permission Check End--}}
{{--            List of Lead Permission Check Start--}}
                @if(auth()->user()->hasPermission('fixed_asset_list'))
                    @if(Route::currentRouteName() == 'fixed.asset.show')
                        <a class="nav-link" href="{{route('fixed.asset.show')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-list"></i></div> Materials List</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('fixed.asset.show')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-list"></i></div> Materials List</a>
                    @endif
                @endif
{{--            List of Lead Permission Check End--}}
            </nav>
        </div>
@endif {{--Upload Option End Here--}}
