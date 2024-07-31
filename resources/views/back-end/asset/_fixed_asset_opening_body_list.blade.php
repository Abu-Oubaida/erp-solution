<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-body">
            @if(isset($final_opening))
            <div class="row">
                <div class="col"><strong>Date:</strong> {!! $final_opening->date !!}</div>
                <div class="col"><strong>Project:</strong> {!! $final_opening->branch->branch_name !!}</div>
                <div class="col"><strong>References:</strong> {!! $final_opening->references !!}</div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table id="datatablesSimple" class="table table-hover table-bordered">
                        <thead>
                        <tr>
                            <th>SL.</th>
                            <th>Date</th>
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
{{--                        @php--}}
{{--                            foreach ($final_opening->withSpecifications as $opm)--}}
{{--                            {--}}
{{--                                var_dump((float)($opm->qty * $opm-->rate));--}}
{{--                            }--}}
{{--                        @endphp--}}
                        @if(isset($final_opening->withSpecifications) && count($final_opening->withSpecifications))
                            @php($n=1)
                            @foreach($final_opening->withSpecifications as $opm)
                                <tr>
                                    <td>{!! $n++ !!}</td>
                                    <td>{!! $opm->date !!}</td>
                                    <td>{!! $opm->asset->materials_name !!}</td>
                                    <td>{!! $opm->specification->specification !!}</td>
                                    <td>{!! $opm->asset->unit !!}</td>
                                    <td>{!! $opm->rate !!}</td>
                                    <td>{!! $opm->qty !!}</td>
                                    <td>{!! (float)($opm->qty * $opm->rate) !!}</td>
                                    <td>{!! (isset($opm->purpose))?$opm->purpose:'' !!}</td>
                                    <td>{!! (isset($opm->remarks))?$opm->remarks:'' !!}</td>
                                    <td>
                                        <button class="text-success border-0 inline-block bg-none" data-bs-toggle="modal" data-bs-target="#editModal"><i class="fas fa-edit"></i></button>
{{--                                @if(auth()->user()->hasPermission('fixed_asset_delete'))--}}
{{--                                    <form action="{{route('fixed.asset.delete')}}" class="display-inline" method="post">--}}
{{--                                        @method('delete')--}}
{{--                                        @csrf--}}
                                        <input type="hidden" name="id" value="{!! $opm->id !!}">
                                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
{{--                                    </form>--}}
{{--                                @endif--}}
                                    </td>
                                </tr>
                            @endforeach
                        @endif
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
            @endif
        </div>
    </div>
</div>
<div class="modal modal-xl fade" id="editModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="v_document_name"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <h1>Hello</h1>
                </div>
                <div id="documentPreview"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
            <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
                <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
            </div>
        </div>
    </div>
</div>
