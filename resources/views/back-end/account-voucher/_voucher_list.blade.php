{{--<form action="{!! route('voucher.multiple.submit') !!}" method="post">--}}
{{--<form>--}}
{{--    @csrf--}}
@if(\Illuminate\Support\Facades\Auth::user()->roles()->first()->display_name == "Systemsuperadmin")
    <div class="row" id="fixedDiv">
        <div class="col-md-10">
            <input type="checkbox" name="" id="select_all">
            <label for="select_all">Select All</label>
            <button class="btn btn-outline-danger btn-sm" name="submit_selected" type="submit" value="delete"> <i class="fas fa-trash"></i> Delete</button>
            <button class="btn btn-outline-primary btn-sm"><i class="fa-regular fa-file-zipper"></i> Download Zip</button>
        </div>

    </div>
@endif
<table id="permissionstable">
    <thead>
    <tr>
    @if(\Illuminate\Support\Facades\Auth::user()->roles()->first()->display_name == "Systemsuperadmin")
        <th>Select</th>
    @endif
        <th>SL</th>
        <th>Date</th>
        <th>Company</th>
        <th>Reference Number</th>
        <th>Type</th>
        <th>Remarks</th>
        <th>Document</th>
        <th>Created By</th>
        <th>Updated By</th>
        <th>Action</th>
    </tr>
    </thead>
{{--    <tfoot>--}}
{{--    <tr>--}}
{{--        <th>Select</th>--}}
{{--        <th>SL</th>--}}
{{--        <th>Date</th>--}}
{{--        <th>Voucher Number</th>--}}
{{--        <th>Voucher Type</th>--}}
{{--        <th>Remarks</th>--}}
{{--        <th>Document</th>--}}
{{--        <th>Created By</th>--}}
{{--        <th>Updated By</th>--}}
{{--        <th>Action</th>--}}
{{--    </tr>--}}
{{--    </tfoot>--}}
    <tbody>
    @if(isset($voucherInfos) && count($voucherInfos))
        @php
            $no= 1;
        @endphp
        @foreach($voucherInfos as $data)
            <tr>
            @if(\Illuminate\Support\Facades\Auth::user()->roles()->first()->display_name == "Systemsuperadmin")
                <th><input class="check-box" type="checkbox" name="selected[]" id="select_{!! $data->id !!}" value="{!! $data->id !!}"></th>
            @endif
                <td>{!! $no++ !!}</td>
                <td>{!! date('d-M-y', strtotime($data->voucher_date)) !!}</td>
                <td>{!! $data->company->company_code !!}</td>
                <td>{!! $data->voucher_number !!}</td>
                <td>{!! $data->VoucherType->voucher_type_title !!}</td>
                <td>{!! $data->remarks !!}</td>
                <td>
                    @php $x = 1;@endphp
                    @foreach($data->VoucherDocument as $d)
                        <div>
                            <strong>{!! $x++ !!}.</strong> {!! $d->document !!} &nbsp; <a href="" title="Quick View" vtype="{!! $data->VoucherType->voucher_type_title !!}" vno="{!! $data->voucher_number !!}" path="{!! \Illuminate\Support\Facades\Crypt::encryptString(url($d->filepath.$d->document)) !!}" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" onclick="return Obj.findDocument(this,'documentPreview','v_type','v_no')"> <i class="fa-solid fa-eye"></i></a>
                            &nbsp;
                            <a href="{!! route('view.voucher.document',['vID'=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" title="View on new window" target="_blank"><i class="fa-solid fa-up-right-from-square"></i></a>
                            &nbsp
                            @if(auth()->user()->hasPermission('share_voucher_document_individual'))
                            <a href="" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}" onclick="return Obj.fileSharingModal(this)" title="Share Document"><i class="fas fa-share"></i></a>
                            @endif
                            &nbsp
                            @if(auth()->user()->hasPermission('delete_voucher_document_individual'))
                                <form action="{{route('delete.voucher.document.individual')}}" class="display-inline" method="post">
                                    @method('delete')
                                    @csrf
                                    <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($d->id) !!}">
                                    <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this?')" title="Delete"><i class="fas fa-trash"></i></button>
                                </form>
                            @endif
                            <hr>
                        </div>
                    @endforeach
                @if(auth()->user()->hasPermission('add_voucher_document_individual'))
                    <a href="" class="text-end float-end badge bg-success text-decoration-none" onclick="return Obj.addVoucherDocumentIndividual(this)" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}"><i class="fas fa-plus"></i> Add New</a>
                @endif
                </td>
                {{--                                        <td></td>--}}
                {{--                                        <td></td>--}}
                <td>{!! ($data->createdBY)? $data->createdBY->name:'-' !!}</td>
                <td>{!! ($data->updatedBY)? $data->updatedBY->name:'-' !!}</td>
                <td>
                    @if(auth()->user()->hasPermission('voucher_document_edit'))
                    <a href="{{route('edit.voucher.info',["voucherDocumentID"=>\Illuminate\Support\Facades\Crypt::encryptString($data->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                    @endif
                    @if(auth()->user()->hasPermission('share_voucher'))
                        <a href="" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}" onclick="return Obj.voucherShare(this)" title="Share Voucher"><i class="fas fa-share"></i></a>
                    @endif
                    @if(auth()->user()->hasPermission('voucher_document_delete'))
                    <form action="{{route('delete.voucher.info')}}" class="display-inline" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($data->id) !!}">
                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the voucher type?')"><i class="fas fa-trash"></i></button>
                    </form>
                    @endif
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
    @method('post')
{{--</form>--}}
<!-- Modal For Preview -->
<div class="modal modal-xl fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="v_document_name"></h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <strong>Voucher No: <span id="v_no"></span></strong>
                    </div>
                    <div class="col-md-6 text-end">
                        <strong>Voucher Type: <span id="v_type"></span></strong>
                    </div>
                </div>
                <div id="documentPreview"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Understood</button>
            </div>
        </div>
    </div>
</div>
<!-- Modal-2 For Share -->
<div class="modal modal-xl fade" id="shareModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="shareModelLabel" aria-hidden="true">
    <div class="modal-dialog" id="model_dialog">

    </div>
    <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
        <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
    </div>
</div>
