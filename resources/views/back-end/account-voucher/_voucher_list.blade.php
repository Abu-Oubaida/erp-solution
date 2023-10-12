<table id="datatablesSimple">
    <thead>
    <tr>
        <th>SL</th>
        <th>Date</th>
        <th>Voucher Number</th>
        <th>Voucher Type</th>
        <th>Remarks</th>
        <th>Document</th>
        <th>Created By</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </thead>
    <tfoot>
    <tr>
        <th>SL</th>
        <th>Date</th>
        <th>Voucher Number</th>
        <th>Voucher Type</th>
        <th>Remarks</th>
        <th>Document</th>
        <th>Created By</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </tfoot>
    <tbody>
    @if(isset($voucherInfos) && count($voucherInfos))
        @php
            $no= 1;
        @endphp
        @foreach($voucherInfos as $data)
            <tr>
                <td>{!! $no++ !!}</td>
                <td>{!! date('d-M-y', strtotime($data->voucher_date)) !!}</td>
                <td>{!! $data->voucher_number !!}</td>
                <td>{!! $data->VoucherType->voucher_type_title !!}</td>
                <td>{!! $data->remarks !!}</td>
                <td>
                    @php $x = 1;@endphp
                    @foreach($data->VoucherDocument as $d)
                        <div>
                            <strong>{!! $x++ !!}.</strong> {!! $d->document !!} &nbsp; <a href="" data-bs-toggle="modal" data-bs-target="#staticBackdrop_{!! $d->id !!}" title="Quick View"> <i class="fa-solid fa-eye"></i></a> &nbsp;
                            <a href="{!! route('view.voucher.document',['vID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" title="View on new window"><i class="fa-solid fa-up-right-from-square"></i></a>
                        </div>
                        <!-- Modal -->
                        <div class="modal modal-xl fade" id="staticBackdrop_{!! $d->id !!}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel_{!! $d->id !!}" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h1 class="modal-title fs-5" id="staticBackdropLabel_{!! $d->id !!}">{!! $d->document !!}</h1>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <strong>Voucher No: {!! $data->voucher_number !!}</strong>
                                            </div>
                                            <div class="col-md-6 text-end">
                                                <strong>Voucher Type: {!! $data->VoucherType->voucher_type_title !!}</strong>
                                            </div>
                                        </div>
                                        @php
                                            // Extract the file extension
                                            $fileExtension = pathinfo($d->filepath.$d->document,PATHINFO_EXTENSION);
                                        @endphp

                                        @if (in_array($fileExtension, ['pdf', 'doc', 'txt','jpg','jpeg','png','JPG'])) <!-- Add your allowed extensions -->
                                        <!-- Embed the PDF using iframe -->
                                        <embed src="{{ url($d->filepath.$d->document) }}#toolbar=0" style="width:100%; height:700px;" />
                                        {{--                                                                @elseif (in_array($fileExtension, ['dox', 'excel', 'xlsx', 'txt','docx']))--}}
                                        {{--                                                                    <iframe src="https://docs.google.com/viewer?url={{ url($d->filepath.$d->document) }}&embedded=true" style="width: 100%; height: 600px;"></iframe>--}}
                                        @elseif ($fileExtension === 'mp4')
                                            <video controls style="width: 100%">
                                                <source src="{{ url($d->filepath.$d->document) }}" type="video/mp4">
                                                Your browser does not support the video tag.
                                            </video>
                                        @else
                                            <div class="row">
                                                <div class="col-md-12 text-center">
                                                    <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                                                    <a class="btn btn-success text-center" href="{{ url($d->filepath.$d->document) }}" download>
                                                        Click To Download
                                                    </a>
                                                </div>
                                            </div>

                                        @endif
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        <button type="button" class="btn btn-primary">Understood</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </td>
                {{--                                        <td></td>--}}
                {{--                                        <td></td>--}}
                <td>{!! ($data->createdBY)? $data->createdBY->name:'-' !!}</td>
                <td>{!! ($data->updatedBY)? $data->updatedBY->name:'-' !!}</td>
                <td>
                    {{--                                            <a href="{{route('edit.voucher.type',["voucherTypeID"=>\Illuminate\Support\Facades\Crypt::encryptString($vt->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>--}}
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
            <td colspan="9" class="text-danger text-center">Not Found!</td>
        </tr>
    @endif
    </tbody>
</table>
