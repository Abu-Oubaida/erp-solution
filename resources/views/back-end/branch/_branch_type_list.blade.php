<div class="row">
    <h3 class="text-capitalize">List of Branch Type</h3>
    <table class="table table-sm" id="datatablesSimple2">
        <thead>
        <tr>
            <th>No</th>
            <th>Title</th>
            <th>Status</th>
            <th>Code</th>
            <th>Created By</th>
            <th>Updated By</th>
            <th>Remarks</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @if(isset($branchTypeAll) && count($branchTypeAll))
            @php
                $no= 1;
            @endphp
            @foreach($branchTypeAll as $b)
                <tr>
                    <td>{!! $no++ !!}</td>
                    <td>{!! $b->title !!}</td>
                    <td>@if($b->status) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">Inactive</span> @endif</td>
                    <td>{!! $b->code !!}</td>
                    <td>{!! ($b->createdBy)?$b->createdBy->name:'' !!}</td>
                    <td>{!! ($b->updatedBy)?$b->updatedBy->name:'' !!}</td>
                    <td>{!! $b->remarks !!}</td>
                    <td>
                        <form action="{{route('delete.blood.group')}}" class="display-inline" method="post">
                            @method('delete')
                            @csrf
                            <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($b->id) !!}">
                            <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
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
