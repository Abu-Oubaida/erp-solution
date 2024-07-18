<div class="row">
    <h3 class="text-capitalize">Fixed Asset Materials List</h3>
</div>
<table class="table table-sm" id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Recourse Code</th>
        <th>Materials</th>
        <th>Status</th>
        <th>Rate</th>
        <th>Unit</th>
        <th>Depreciation Rate</th>
        <th>Remarks</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($fixed_assets) && count($fixed_assets))
        @php
            $no= 1;
        @endphp
        @foreach($fixed_assets as $fa)
            <tr>
                <td>{!! $no++ !!}</td>
                <td>{!! $fa->recourse_code !!}</td>
                <td>{!! $fa->materials_name !!}</td>
                <td>@if($fa->status==1) Active @else Inactive @endif</td>
                <td>{!! $fa->rate !!}</td>
                <td>{!! $fa->unit !!}</td>
                <td>{!! $fa->depreciation !!}</td>
                <td>{!! $fa->remarks !!}</td>
                <td></td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>

