<div class="col-md-12 mt-1" id="documents">
    <div class="card">
        <div class="card-header">
            <h5><i class="fas fa-list"></i> Attested Documents</h5>
        </div>
        <div class="card-body">
            <table @if(count($item->documents))id="simpleDataTable2" class="display table-sm"
                   @else id="datatablesSimple" class="table table-sm" @endif>
                <thead>
                <tr>
                    <th>SL.</th>
                    <th>Name</th>
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @if(count($item->documents))
                    @php($n=1)
                    @foreach($item->documents as $d)
                        <tr>
                            <td>{!! $n++ !!}</td>
                            <td>{!! $d->document_name !!}</td>
                            <td class="text-center">
                                <a href="{!! url($d->document_url.$d->document_name) !!}" class=""
                                   target="_blank"><i class="fas fa-eye"></i></a>
                                <button class="text-danger border-0 inline-block bg-none"
                                        onclick="return Obj.deleteFixedAssetTransferDocument(this)"
                                        ref="{!! $d->id !!}"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td colspan="3">Not Found!</td>
                    </tr>
                @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
