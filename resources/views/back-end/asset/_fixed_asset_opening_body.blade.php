<script>
    $(document).ready(function() {
        $('select').selectize({
            // create: true,
            sortField: 'text'
        });
    })
</script>
<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            <div class="row md-1">
                <h5 class="text-capitalize">Fixed Asset Opening</h5>
                <hr>
            </div>
            <div class="row">
                <strong>Reference Number:{!! isset($reference)?$reference:'' !!}</strong>
                <div class="col-md-2">
                    <div class="mb-1">
                        <label for="date">Date<span class="text-danger">*</span></label>
                        <input class="form-control" id="date" name="date" type="date" placeholder="date" value="" required/>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label for="project">Enter Project Name<span class="text-danger">*</span></label>
                                <select placeholder="Pick a state..." id="project" name="project" class="cursor-pointer">
                                    <option value="">Pick a state...</option>
                                    @if(count($projects))
                                        @foreach($projects as $p)
                                            <option value="{!! $p->id !!}">{!! $p->branch_name !!}</option>
                                        @endforeach
                                    @endif
                                </select>
                                <input type="hidden" id="ref_hide" value="{!! isset($reference)?$reference:'' !!}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label for="recourse_code">Materials<span class="text-danger">*</span></label>
                                <select id="materials_id" onchange="return Obj.getFixedAssetSpecification(this)">
                                    <option value="">Pick a state...</option>
                                    @if(count($fixed_assets))
                                        @foreach($fixed_assets as $fx)
                                            <option value="{!! $fx->id !!}">{!! $fx->materials_name !!} ({{$fx->recourse_code}})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-1">
                                <label for="specification">Specification<span class="text-danger">*</span></label>
                                <select id="specification" >
                                    <option value="">Pick a state...</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-1">
                        <label for="ref">Unite<span class="text-danger">*</span></label>
                        <input class="form-control bg-secondary text-white" id="unite" type="text" placeholder="Unite" value="" required readonly/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-1">
                        <label for="ref">Rate<span class="text-danger">*</span></label>
                        <input class="form-control" id="rate" onfocusout="return Obj.priceTotal(this, 'qty','total')" type="text" placeholder="Rate" value="" required/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-1">
                        <label for="ref">Qty.<span class="text-danger">*</span></label>
                        <input class="form-control" onfocusout="return Obj.priceTotal(this, 'rate','total')" id="qty" type="text" placeholder="Qty" value="" required/>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="mb-1">
                        <label for="ref">Total<span class="text-danger">*</span></label>
                        <input class="form-control bg-secondary text-white" value="" id="total" type="text" placeholder="Total" required readonly step="100"/>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="">
                        <label for="purpose">Purpose of use</label>
                        <input class="form-control" value="" id="purpose" placeholder="Purpose use" type="text">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="">
                        <label for="remarks">Remarks</label>
                        <input class="form-control" value="" id="remarks" placeholder="Remarks" type="text">
                    </div>
                </div>
                <div class="col">
                    <div class="mb-3 mt-4 float-end">
                        <button class="btn btn-chl-outline btn-sm" type="button" onclick="return Obj.fixedAssetOpeningAddList(this)"> <i class="fas fa-plus"></i> Add</button>
                    </div>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col"><strong>Date:</strong></div>
                <div class="col"><strong>Project:</strong></div>
                <div class="col"><strong>References:</strong></div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="" class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>Materials</th>
                            <th>Specifications</th>
                            <th>Unit</th>
                            <th>Rate</th>
                            <th>Qty.</th>
                            <th>Total</th>
                            <th>Purpose</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>abc</td>
                            <td>cdb</td>
                            <td>nos</td>
                            <td>10</td>
                            <td>1</td>
                            <td>10</td>
                            <td></td>
                            <td></td>
                            <td>
                                {{--                                            @if(auth()->user()->hasPermission('fixed_asset_delete'))--}}
                                {{--                                                <form action="{{route('fixed.asset.delete')}}" class="display-inline" method="post">--}}
                                {{--                                                    @method('delete')--}}
                                {{--                                                    @csrf--}}
                                <input type="hidden" name="id" value="">
                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                                {{--                                                </form>--}}
                                {{--                                            @endif--}}
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-8">
                    <div>
                        <label for="remarks">Narration:</label>
                        <textarea class="form-control form-control-sm"  id="narration"></textarea>
                    </div>
                </div>
                <div class="col-md-4">
                    <button class="btn btn-lg btn-outline-success float-end mt-4" type="button"><i class="fas fa-save"></i> Final Update</button>
                </div>
            </div>
        </div>
    </div>
</div>
