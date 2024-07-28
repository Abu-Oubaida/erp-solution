@if(auth()->user()->hasPermission('fixed_asset') )

    @if(Request::segment(1) == "fixed-asset" || Request::segment(1) == "fixed-asset-distribution")
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
                @if(auth()->user()->hasPermission('add_fixed_asset'))
                    @if(Route::currentRouteName() == 'fixed.asset.add')
                        <a class="nav-link" href="{{route('fixed.asset.add')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Add Materials</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Add Materials</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('add_fixed_asset_specification'))
                    @if(Route::currentRouteName() == 'add.fixed.asset.specification')
                        <a class="nav-link" href="{{route('add.fixed.asset.specification')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div>Specification</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('add.fixed.asset.specification')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div>Specification</a>
                    @endif
                @endif
                @if(auth()->user()->hasPermission('fixed_asset_list'))
                    @if(Route::currentRouteName() == 'fixed.asset.show')
                        <a class="nav-link" href="{{route('fixed.asset.show')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-list"></i></div> Materials List</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('fixed.asset.show')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-list"></i></div> Materials List</a>
                    @endif
                @endif
                @if(Route::currentRouteName() == 'fixed.asset.edit')
                    <a class="nav-link" href="{{route('fixed.asset.edit',['fixedAssetID'=>\Illuminate\Support\Facades\Request::route('fixedAssetID')])}}"><div class="sb-nav-link-icon"><i class='fas fa-edit'></i></div> Edit Materials</a>
                @endif
            </nav>
            <nav class="sb-sidenav-menu-nested nav ">
                @if(auth()->user()->hasPermission('fixed_asset_distribution') )

                    @if(Request::segment(1) == "fixed-asset-distribution" )
                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetDistribution" aria-expanded="true" aria-controls="fixedAssetDistribution" title="Fixed Asset Distribution">
                            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-d"></i><sup><i class="fas fa-plus"></i></sup></div>
                            Distribution
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="fixedAssetDistribution" aria-labelledby="headingOne" data-bs-parent="#fixedAssetDistribution">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetDistribution" aria-expanded="false" aria-controls="fixedAssetDistribution" title="Fixed Asset Distribution">
                            <div class="sb-nav-link-icon"><i class="fas fa-solid fa-d"></i><sup><i class="fas fa-plus"></i></sup></div>
                            Distribution
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="fixedAssetDistribution" aria-labelledby="headingOne" data-bs-parent="#fixedAssetDistribution">
                    @endif
                            <nav class="sb-sidenav-menu-nested nav">
                                @if(auth()->user()->hasPermission('fixed_asset_opening_input'))
                                    @if(Route::currentRouteName() == 'fixed.asset.distribution.opening.input')
                                        <a class="nav-link" href="{{route('fixed.asset.distribution.opening.input')}}" title="Distribute Fixed Asset Opening Balance"><div class="sb-nav-link-icon">FA</div> Opening</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.distribution.opening.input')}}" title="Fixed Asset Opening Balance"><div class="sb-nav-link-icon">FA</div> Opening</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('fixed_asset_damage'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="{{route('fixed.asset.add')}}" title="Damage Fixed Asset"><div class="sb-nav-link-icon">FA</div> Damage</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}" title="Damage Fixed Asset"><div class="sb-nav-link-icon">FA</div> Damage</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('issue_fixed_asset'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="{{route('fixed.asset.add')}}" title="Issue Fixed Asset"><div class="sb-nav-link-icon">FA</div> Issue</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}" title="Issue Fixed Asset"><div class="sb-nav-link-icon">FA</div> Issue</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('issue_return_fixed_asset'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="{{route('fixed.asset.add')}}" title="Fixed Asset Issue Return"><div class="sb-nav-link-icon">FAI</div> Return</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}" title="Fixed Asset Issue Return"><div class="sb-nav-link-icon">FAI</div> Return</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('distribution_fixed_asset_mrf'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="{{route('fixed.asset.add')}}" title="Distribute Fixed Asset Via Materials Purchase Requisition (MRF) Reference"><div class="sb-nav-link-icon">MRF</div> Reference</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}" title="Distribute Fixed Asset Via Materials Purchase Requisition (MRF) Reference"><div class="sb-nav-link-icon">MRF</div> Reference</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('distribution_fixed_asset_gp'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="{{route('fixed.asset.add')}}" title="Distribute Fixed Asset Via Gate Pass (GP) Reference"><div class="sb-nav-link-icon">GP</div> Reference</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}" title="Distribute Fixed Asset Via Gate Pass (GP) Reference"><div class="sb-nav-link-icon">GP</div> Reference</a>
                                    @endif
                                @endif
                            </nav>
                        </div>
                    @endif

            </nav>
@endif

