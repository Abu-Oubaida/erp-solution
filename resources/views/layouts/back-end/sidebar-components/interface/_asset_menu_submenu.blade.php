@if(auth()->user()->hasPermission('fixed_asset') )

    @if(Request::segment(1) == "fixed-asset" || Request::segment(1) == "fixed-asset-distribution" || Request::segment(1) == "fixed-asset-report")
        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetOption" aria-expanded="true" aria-controls="fixedAssetOption">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder-tree"></i></div>
            Fixed Asset
            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
        </a>
        <div class="collapse show" id="fixedAssetOption" aria-labelledby="headingOne" data-bs-parent="#fixedAssetOption">
    @else
        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetOption" aria-expanded="false" aria-controls="fixedAssetOption">
            <div class="sb-nav-link-icon"><i class="fa-solid fa-folder-tree"></i></div>
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
                    @if(Route::currentRouteName() == 'fixed.asset.specification')
                        <a class="nav-link" href="{{route('fixed.asset.specification')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div>Specification</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('fixed.asset.specification')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div>Specification</a>
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
                @if(Route::currentRouteName() == 'edit.fixed.asset.specification')
                    <a class="nav-link" href="{{route('edit.fixed.asset.specification',['fasid'=>\Illuminate\Support\Facades\Request::route('fasid')])}}" title="Edit Specification"><div class="sb-nav-link-icon"><i class='fas fa-edit'></i></div> Edit Spec.</a>
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
                                @if(auth()->user()->hasPermission('fixed_asset_with_reference_input'))
                                    @if(Route::currentRouteName() == 'fixed.asset.distribution.opening.input')
                                        <a class="nav-link" href="{{route('fixed.asset.distribution.opening.input')}}" title="Distribute Fixed Asset With Reference Number">With Reference</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.distribution.opening.input')}}" title="Distribute Fixed Asset With Reference Number">With Reference</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('edit_fixed_asset_distribution_with_reference'))
                                        @if(Route::currentRouteName() == 'edit.fixed.asset.distribution.with.reference.balance')
                                            <a class="nav-link" href="{{route('edit.fixed.asset.distribution.with.reference.balance',['faobid'=>\Illuminate\Support\Facades\Request::route('faobid')])}}" title="Edit With Reference"><div class="sb-nav-link-icon"><i class='fas fa-edit'></i></div> Edit With Reference</a>
                                        @endif
                                @endif
                                @if(auth()->user()->hasPermission('fixed_asset_transfer_entry'))
                                    @if(Route::currentRouteName() == 'fixed.asset.transfer')
                                        <a class="nav-link" href="{{route('fixed.asset.transfer')}}" title="Distribute Fixed Asset Via Gate Pass (GP) Reference"><div class="sb-nav-link-icon">GP</div> Gate Pass</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.transfer')}}" title="Distribute Fixed Asset Via Gate Pass (GP) Reference"><div class="sb-nav-link-icon">GP</div> Gate Pass</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('edit_fixed_asset_transfer'))
                                    @if(Route::currentRouteName() == 'edit.fixed.asset.transfer')
                                        <a class="nav-link" href="{{route('edit.fixed.asset.transfer',['ftid'=>\Illuminate\Support\Facades\Request::route('ftid')])}}" title="Edit Fixed Asset Transfer"><div class="sb-nav-link-icon"><i class='fas fa-edit'></i></div>GP Edit</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('fixed_asset_damage'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="#" title="Damage Fixed Asset"><div class="sb-nav-link-icon">FA</div> Damage</a>
                                    @else
                                        <a class="nav-link text-chl" href="#" title="Damage Fixed Asset"><div class="sb-nav-link-icon">FA</div> Damage</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('issue_fixed_asset'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="#" title="Issue Fixed Asset"><div class="sb-nav-link-icon">FA</div> Issue</a>
                                    @else
                                        <a class="nav-link text-chl" href="#" title="Issue Fixed Asset"><div class="sb-nav-link-icon">FA</div> Issue</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('issue_return_fixed_asset'))
                                    @if(Route::currentRouteName() == 'fixed.asset.add')
                                        <a class="nav-link" href="{{route('fixed.asset.add')}}" title="Fixed Asset Issue Return"><div class="sb-nav-link-icon">FAI</div> Return</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}" title="Fixed Asset Issue Return"><div class="sb-nav-link-icon">FAI</div> Return</a>
                                    @endif
                                @endif
{{--                                @if(auth()->user()->hasPermission('distribution_fixed_asset_mrf'))--}}
{{--                                    @if(Route::currentRouteName() == 'fixed.asset.add')--}}
{{--                                        <a class="nav-link" href="{{route('fixed.asset.add')}}" title="Distribute Fixed Asset Via Materials Purchase Requisition (MRF) Reference"><div class="sb-nav-link-icon">MRF</div> Reference</a>--}}
{{--                                    @else--}}
{{--                                        <a class="nav-link text-chl" href="{{route('fixed.asset.add')}}" title="Distribute Fixed Asset Via Materials Purchase Requisition (MRF) Reference"><div class="sb-nav-link-icon">MRF</div> Reference</a>--}}
{{--                                    @endif--}}
{{--                                @endif--}}

                            </nav>
                        </div>
                    @endif

            </nav>
            <nav class="sb-sidenav-menu-nested nav ">
                @if(auth()->user()->hasPermission('fixed_asset_report') )

                    @if(Request::segment(1) == "fixed-asset-report" )
                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetReport" aria-expanded="true" aria-controls="fixedAssetReport" title="Fixed Asset Report">
                            <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                            Reports
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="fixedAssetReport" aria-labelledby="headingOne" data-bs-parent="#fixedAssetReport">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#fixedAssetReport" aria-expanded="false" aria-controls="fixedAssetReport" title="Fixed Asset Report">
                            <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                            Reports
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="fixedAssetReport" aria-labelledby="headingOne" data-bs-parent="#fixedAssetReport">
                    @endif
                            <nav class="sb-sidenav-menu-nested nav">
                                @if(auth()->user()->hasPermission('fixed_asset_stock_report'))
                                    @if(Route::currentRouteName() == 'fixed.asset.stock.report')
                                        <a class="nav-link" href="{{route('fixed.asset.stock.report')}}" title="Distribute Report 1">Stock Report</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('fixed.asset.stock.report')}}" title="Distribute Report 1">Stock Report</a>
                                    @endif
                                @endif
                            </nav>
                        </div>
                    @endif

            </nav>
@endif

