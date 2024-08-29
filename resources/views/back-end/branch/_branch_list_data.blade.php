<div class="row">
    <h3 class="text-capitalize">List of Branch</h3>
    <table class="table table-sm" id="datatablesSimple">
        <thead>
        <tr>
            <th>No</th>
            <th>Branch Name</th>
            <th>Company</th>
            <th>Branch Type</th>
            <th>Status</th>
            <th>Address</th>
            <th>Created By</th>
            <th>Updated By</th>
            <th>Remarks</th>
            <th><div class="text-center">Action</div></th>
        </tr>
        </thead>
        <tbody>
        @if(isset($branches) && count($branches))
            @php
                $no= 1;
            @endphp
            @foreach($branches as $br)
                <tr>
                    <td>{!! $no++ !!}</td>
                    <td>{!! $br->branch_name !!}</td>
                    <td>{!! $br->company->company_name !!}</td>
                    <td>{!! $br->branchType->title !!}</td>
                    <td>@if($br->status) <span class="badge bg-success">Active</span>@else <span class="badge bg-danger">Inactive</span> @endif</td>
                    <td>{!! $br->address !!}</td>
                    <td>{!! ($br->createdBy)?$br->createdBy->name:'' !!}</td>
                    <td>{!! ($br->updatedBy)?$br->updatedBy->name:'' !!}</td>
                    <td>{!! $br->remarks !!}</td>
                    <td>
                        <div class="text-center">
                            @if(auth()->user()->hasPermission('edit_branch'))
                                <a href="{!! route('edit.branch',['branchID'=>\Illuminate\Support\Facades\Crypt::encryptString($br->id)]) !!}" class="text-success"><i class="fas fa-edit"></i></a>
                            @endif
                            <form action="{!! route('delete.branch') !!}" class="display-inline" method="post">
                                @method('delete')
                                @csrf
                                <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($br->id) !!}">
                                <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete this data?')"><i class="fas fa-trash"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
            @endforeach
        @else
            <tr>
                <td colspan="10" class="text-danger text-center">Not Found!</td>
            </tr>
        @endif
        </tbody>
    </table>
</div>
