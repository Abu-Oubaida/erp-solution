<div class="col-md-12">
    <table id="datatablesSimple">
        <thead>
        <tr>
            <th>No</th>
            <th>Voucher type title</th>
            <th>Status</th>
            <th>Code</th>
            <th>Remarks</th>
            <th>Created By</th>
            <th>Updated By</th>
            <th>Action</th>
        </tr>
        </thead>
        <tfoot>
        <tr>
            <th>No</th>
            <th>Voucher type title</th>
            <th>Status</th>
            <th>Code</th>
            <th>Remarks</th>
            <th>Created By</th>
            <th>Updated By</th>
            <th>Action</th>
        </tr>
        </tfoot>
        <tbody>
        @if(isset($voucherTypes) && count($voucherTypes))
            @php
                $no= 1;
            @endphp
            @foreach($voucherTypes as $vt)
                <tr>
                    <td>{!! $no++ !!}</td>
                    <td>{!! $vt->voucher_type_title !!}</td>
                    <td>@if($vt->status ==1) Active @else Inactive @endif</td>
                    <td>{!! $vt->code !!}</td>
                    <td>{!! $vt->remarks !!}</td>
                    <td>{!! $vt->createdBY->name !!}</td>
                    <td>{!! $vt->updatedBY->name !!}</td>
                    <td>
                        <a href="{{route('edit.voucher.type',["voucherTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($vt->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                        <form action="{{route('delete.voucher.type')}}" class="display-inline" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($vt->id) !!}">
                            <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the voucher type?')"><i class="fas fa-trash"></i></button>
                        </form>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="8" class="text-danger text-center">Not Found!</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
