<table id="datatablesSimple" class="table table-hover table-bordered">
    <thead>
    <tr>
        <th>SL.</th>
        <th>Date</th>
        <th>Materials (Code)</th>
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
    @if(isset($withRefData->withSpecifications) && count($withRefData->withSpecifications))
        @php($n=1)
        @foreach($withRefData->withSpecifications as $opm)
            <tr>
                <td>{!! $n++ !!}</td>
                <td>{!! date('d-M-Y', strtotime($opm->date)) !!}</td>
                <td>{!! $opm->asset->materials_name !!} ({!! $opm->asset->recourse_code !!})</td>
                <td>{!! $opm->specification->specification !!}</td>
                <td>{!! $opm->asset->unit !!}</td>
                <td>{!! $opm->rate !!}</td>
                <td>{!! $opm->qty !!}</td>
                <td>{!! (float)($opm->qty * $opm->rate) !!}</td>
                <td>{!! (isset($opm->purpose))?$opm->purpose:'' !!}</td>
                <td>{!! (isset($opm->remarks))?$opm->remarks:'' !!}</td>
                <td>
                    <button class="text-success border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.fixedAssetOpeningSpecEdit(this)"><i class="fas fa-edit"></i></button>
                    <button class="text-danger border-0 inline-block bg-none" ref="{!! $opm->id !!}" onclick="return Obj.deleteFixedAssetOpeningSpec(this)"><i class="fas fa-trash"></i></button>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td class="text-center" colspan="12">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>

