<table id="datatablesSimple">
    <thead>
    <tr>
        <th>SL</th>
        <th>Employee ID</th>
        <th>Name</th>
        <th>Department (Branch)</th>
        <th><span title="Financial Year From">F.Y. From</span></th>
        <th><span title="Financial Year To">F.Y. To</span></th>
        <th>Basic</th>
        <th>House Rent</th>
        <th>Conveyance</th>
        <th>Medical Allowance</th>
        <th>Festival Bonus</th>
        <th>Others</th>
        <th>Total</th>
        <th>Remarks</th>
    @if(Route::currentRouteName() == 'salary.certificate.list')
        <th>Created By</th>
        <th>Updated By</th>
    @endif
        <th>Action</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>SL</th>
        <th>Employee ID</th>
        <th>Name</th>
        <th>Department (Branch)</th>
        <th><span title="Financial Year From">F.Y. From</span></th>
        <th><span title="Financial Year To">F.Y. To</span></th>
        <th>Basic</th>
        <th>House Rent</th>
        <th>Conveyance</th>
        <th>Medical Allowance</th>
        <th>Festival Bonus</th>
        <th>Others</th>
        <th>Total</th>
        <th>Remarks</th>
    @if(Route::currentRouteName() == 'salary.certificate.list')
        <th>Created By</th>
        <th>Updated By</th>
    @endif
        <th>Action</th>
    </tr>
    </tfoot>
    <tbody>
    @if(isset($datas) && count($datas))
        @php
            $no= 1;
        @endphp
        @foreach($datas as $d)
            <tr>
                <td>{!! $no++ !!}</td>
                <td>{!! $d->userInfo->employee_id !!}</td>
                <td>{!! $d->userInfo->name !!}</td>
                <td>{!! $d->userInfo->getDepartment->dept_name !!}, ({!! $d->userInfo->getBranch->branch_name !!})</td>
                <td>{!! date('M-y', strtotime($d->financial_yer_from)) !!}</td>
                <td>{!! date('M-y', strtotime($d->financial_yer_to)) !!}</td>
                <td>{!! $d->basic !!}</td>
                <td>{!! $d->house_rent !!}</td>
                <td>{!! $d->conveyance !!}</td>
                <td>{!! $d->medical_allowance !!}</td>
                <td>{!! $d->festival_bonus !!}</td>
                <td>{!! $d->others !!}</td>
                <td>{!! ($d->basic+$d->house_rent+$d->conveyance+$d->medical_allowance+$d->festival_bonus+$d->others) !!}</td>
                <td>{!! $d->remarks !!}</td>
            @if(Route::currentRouteName() == 'salary.certificate.list')
                <td>{!! ($d->createdBY)? $d->createdBY->name:'-' !!}</td>
                <td>{!! ($d->updatedBY)? $d->updatedBY->name:'-' !!}</td>
            @endif
                <td>
                    <div class="text-center">
                        <a href="{{route('salary.certificate.view',["salaryInfoID"=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)])}}" class="text-success" title="View"><i class='fas fa-eye'></i></a>
                    </div>

                    {{--                                            <form action="{{route('delete.voucher.type')}}" class="display-inline" method="post">--}}
                    {{--                                                @method('delete')--}}
                    {{--                                                @csrf--}}
                    {{--                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($vt->id) !!}">--}}
                    {{--                                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the voucher type?')"><i class="fas fa-trash"></i></button>--}}
                    {{--                                            </form>--}}
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="15" class="text-danger text-center">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>
