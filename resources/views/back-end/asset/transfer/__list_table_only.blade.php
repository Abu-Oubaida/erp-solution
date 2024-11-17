<table @if(isset($transferData->specifications) && count($transferData->specifications)) id="simpleDataTableCustom" @endif class="table display">
    <thead>
    <tr>
        <th>SL.</th>
        <th>Entry Date</th>
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
    @if(isset($transferData->specifications) && count($transferData->specifications))
        @php($n=1)
        @foreach($transferData->specifications as $tm)
            <tr>
                <td>{!! $n++ !!}</td>
                <td>{!! date('d-M-Y', strtotime($tm->created_at)) !!}</td>
                <td>{!! $tm->asset->materials_name !!} ({!! $tm->asset->recourse_code !!})</td>
                <td>{!! ($tm->spec_id == '0')?'None':$tm->specification->specification !!}</td>
                <td>{!! $tm->asset->unit !!}</td>
                <td>{!! $tm->rate !!}</td>
                <td>{!! $tm->qty !!}</td>
                <td>{!! (float)($tm->qty * $tm->rate) !!}</td>
                <td>{!! (isset($tm->purpose))?$tm->purpose:'' !!}</td>
                <td>{!! (isset($tm->remarks))?$tm->remarks:'' !!}</td>
                <td>
                    <button class="text-success border-0 inline-block bg-none" ref="{!! $tm->id !!}" onclick="return Obj.fixedAssetTransferSpecEdit(this)"><i class="fas fa-edit"></i></button>
                    <button class="text-danger border-0 inline-block bg-none" ref="{!! $tm->id !!}" onclick="return Obj.deleteFixedAssetTransferSpec(this)"><i class="fas fa-trash"></i></button>
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
<script>
    (function ($){
        $(document).ready(function(){
            $('#simpleDataTableCustom').DataTable({
                dom: 'lfrtip',
                lengthMenu: [[5, 10, 15, 25, 50, 100, -1],[5, 10, 15, 25, 50, 100, "ALL"]],
                pageLength: 15,
            })
        })
    }(jQuery))
</script>
