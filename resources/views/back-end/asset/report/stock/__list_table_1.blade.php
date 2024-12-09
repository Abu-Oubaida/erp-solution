<table @if(isset($report) && count($report)) id="simpleDataTableCustom" @endif class="table display" style="font-size: 12.5px">
    <thead>
    <tr>
        <th>SL.</th>
        <th>Company</th>
        <th title="Materials Name">Materials</th>
        <th title="Materials Code">Code</th>
        <th title="Unit">Unit</th>
        <th title="Received balance using reference count">W.R.Count</th>
        <th title="Received balance using reference quantity total">W.R.Qty</th>
        <th title="Received balance using reference amount total">W.R.Amount</th>
        <th title="Transfer in self company count">T.S. Count</th>
        <th title="Transfer in from other company count">T. In</th>
        <th title="Transfer in from other company quantity total">In Qty</th>
        <th title="Transfer in from other company amount total">In Amount</th>
        <th title="Transfer out from this company count">T. Out</th>
        <th title="Transfer out from this company quantity total">Out Qty</th>
        <th title="Transfer out from this company amount total">Out Amount</th>
        <th>Total Qty</th>
        <th>Total Amount</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($report) && count($report))
        @php($n=1)
        @foreach($report as $item)
            <tr>
                <td>{!! $n++ !!}</td>
                <td>{!! @$company->company_name !!}</td>
                <td>{!! $item->materials_name !!}</td>
                <td>{!! $item->recourse_code !!}</td>
                <td>{!! $item->unit !!}</td>
                <td><a href="#">{!! $item->opening_count !!}</a></td>
                <td>{!! $item->withRef_total_qty !!}</td>
                <td>{!! $item->withRef_total_price !!}</td>
                <td><a href="#">{!! $item->in_company_transfer_count !!}</a></td>
                <td><a href="#">{!! $item->transfer_in_company_count !!}</a></td>
                <td>{!! $item->transfer_in_qty !!}</td>
                <td>{!! $item->transfer_in_total_price !!}</td>
                <td><a href="#">{!! $item->transfer_out_company_count !!}</a></td>
                <td>{!! $item->transfer_out_qty !!}</td>
                <td>{!! $item->transfer_out_total_price !!}</td>
                <td>{!! ($item->withRef_total_qty + $item->transfer_in_qty) !!}</td>
                <td>{!! ($item->withRef_total_price + (($item->transfer_in_total_price+$item->transfer_out_total_price))) !!}</td>
            </tr>
        @endforeach
    @else
        <tr>
            <td class="text-center" colspan="15">Not Found!</td>
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
