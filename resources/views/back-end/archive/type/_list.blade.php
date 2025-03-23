
<div class="col-md-12">
    <div class="card mb-4">
        <div class="card-header">
            <div class="row">
                <h3 class="text-capitalize"><i class="fas fa-list"></i> Data Type List</h3>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <table id="datatablesSimple">
                        <thead>
                        <tr>
                            <th>No</th>
                            <th>Voucher type title</th>
                            <th>Permission User</th>
                            <th>Company Name</th>
                            <th>Status</th>
                            <th>Code</th>
                            <th>Remarks</th>
                            <th>Created By</th>
                            <th>Updated By</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        {{--        <tfoot>--}}
                        {{--        <tr>--}}
                        {{--            <th>No</th>--}}
                        {{--            <th>Voucher type title</th>--}}
                        {{--            <th>Status</th>--}}
                        {{--            <th>Code</th>--}}
                        {{--            <th>Remarks</th>--}}
                        {{--            <th>Created By</th>--}}
                        {{--            <th>Updated By</th>--}}
                        {{--            <th>Action</th>--}}
                        {{--        </tr>--}}
                        {{--        </tfoot>--}}
                        <tbody>
                        @if(isset($voucherTypes) && count($voucherTypes))
                            @php
                                $no= 1;
                            @endphp
                            @foreach($voucherTypes as $vt)
                                <tr>
                                    <td>{!! $no++ !!}</td>
                                    <td>{!! $vt->voucher_type_title !!}</td>
                                    <td>{!! $vt->voucher_with_users_count !!}</td>
                                    <td>
                                        @if(isset($vt->company_id) && isset($vt->company->id))
                                            @if($vt->company_id == $vt->company->id)
                                                <p>{!! $vt->company->company_name !!}</p>
                                            @endif
                                        @endif
                                    </td>
                                    <td>@if($vt->status ==1) Active @else Inactive @endif</td>
                                    <td>{!! $vt->code !!}</td>
                                    <td>{!! $vt->remarks !!}</td>
                                    <td>{!! ($vt->createdBY)? $vt->createdBY->name:'-' !!}</td>
                                    <td>{!! ($vt->updatedBY)? $vt->updatedBY->name:'-' !!}</td>
                                    <td>
                                        @if(auth()->user()->hasPermission('edit_archive_data_type'))
                                            <a href="{{route('edit.archive.type',["archiveTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($vt->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                                        @endif
                                        @if(auth()->user()->hasPermission('archive_data_type_delete'))
                                            <form action="{{route('delete.archive.type')}}" class="display-inline" method="post">
                                                @method('delete')
                                                @csrf
                                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($vt->id) !!}">
                                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the voucher type?')"><i class="fas fa-trash"></i></button>
                                            </form>
                                        @endif
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
            </div>
        </div>
    </div>

</div>

