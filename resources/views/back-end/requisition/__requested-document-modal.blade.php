<table class="table table-sm" id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Title</th>
        <th>Status</th>
        <th>Uploaded By</th>
        <th>Uploaded AT</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($data) && count($data))
        @php($n = 1)
        @foreach($data as $d)
            <tr>
                <td>{!! $n++ !!}</td>
                <td>{!! @$d->document_title !!}</td>
                <td>@if($d->document_upload_status == 1) <span class="badge bg-success">Done</span>@else <span class="badge bg-warning">Pending</span> @endif</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
