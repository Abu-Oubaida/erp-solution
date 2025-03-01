<div class="card-body">
    <table class="table table-sm" id="datatablesSimple">
        <thead>
        <tr>
            <th>No</th>
            <th title="Requisition date">Req. Date</th>
            <th>Title</th>
            <th>Sender</th>
            <th>Receiver count</th>
            <th>Deadline</th>
            <th>Response need</th>
            <th>Response submit</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($documents))
            @php($n = 1)
            @foreach($documents as $d)
                <tr>
                    <td>{!! $n++ !!}</td>
                    <td>{!! date('d-M-y', strtotime(@$d->created_at)) !!}</td>
                    <td>{!! @$d->subject !!}</td>
                    <td>{!! @$d->sender->name !!}</td>
                    <td><a href="#" onclick="return Obj.requisitionDocumentUsersInfo(this)" ref="{!! @$d->id !!}">{!! count(@$d->receivers) !!}</a></td>
                    <td>{!! date('d-M-y', strtotime(@$d->deadline)) !!}</td>
                    <td><a href="#" onclick="return Obj.requisitionDocumentNeed(this)" ref="{!! @$d->id !!}">{!! count(@$d->attachmentInfos) !!}</a></td>
                    <td>{!! $d->attachmentInfos->flatMap->attestedDocument->unique('id')->count() !!}</td>
                    <td>
                        <a href="{!! route('document.requisition.view.self',["requisitionDocumentId"=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)]) !!}" class="text-primary" title="View"><i class='fas fa-eye'></i></a>
                    @if(@$d->status == 1)
                        <a href="{{route('document.requisition.edit.self',["requisitionDocumentId"=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                        <a href="{{route('document.requisition.delete.self',["requisitionDocumentId"=>\Illuminate\Support\Facades\Crypt::encryptString($d->id)])}}" class="text-danger" title="Delete"><i class='fas fa-trash'></i></a>
                    @endif

                    </td>
                </tr>
            @endforeach
        @endif
        </tbody>
    </table>

    <!-- Modal For Receiver List -->
    <div class="modal modal-xl fade" id="receiverList" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="receiverListLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="heading"></h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="documentPreview"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
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
</div>
