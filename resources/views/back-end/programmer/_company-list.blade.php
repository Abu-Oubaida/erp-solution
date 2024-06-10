<table id="datatablesSimple">
    <thead>
    <tr>
        <th>SL</th>
        <th>Name</th>
        <th>Status</th>
        <th>Type</th>
        <th>Code</th>
        <th>Mobile</th>
        <th>Email</th>
        <th>Contract Person</th>
        <th>Contract Mobile</th>
        <th>Location</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(count($companies))
        @php($i=1)
        @foreach($companies as $company)
            <tr class="{!! (isset($editID) && $company->id == $editID->id)?'text-primary':'' !!}">
                <td>{!! $i++ !!}</td>
                <td><img class="img-thumbnail w-25" src="{!! url($company->logo) !!}" alt="{!! $company->company_code !!}">{!! $company->company_name !!}</td>
                <td>@if($company->status == 1) {!! '<span class="text-success">Active</span>' !!} @elseif($company->status == 3) {!! '<span class="text-warning">Deleted</span>' !!}  @else {!! '<span class="text-danger">Inactive</span>' !!} @endif</td>
                <td>{!! $company->companyType->company_type_title !!}</td>
                <td>{!! $company->company_code !!}</td>
                <td>{!! $company->phone !!}</td>
                <td>{!! $company->email !!}</td>
                <td>{!! $company->contract_person_name !!}</td>
                <td>{!! $company->contract_person_phone !!}</td>
                <td>{!! $company->location !!}</td>
                <td>
                    <a href="{{route('edit.company',["companyID"=>\Illuminate\Support\Facades\Crypt::encryptString($company->id)])}}" class="text-success" title="Edit"><i class='fas fa-edit'></i></a>
                    <form action="{{route('delete.company')}}" class="display-inline" method="post">
                        @method('delete')
                        @csrf
                        <input type="hidden" name="id" value="{!! \Illuminate\Support\Facades\Crypt::encryptString($company->id) !!}">
                        <button class="text-danger border-0 inline-block bg-none" onclick="return confirm('Are you sure delete the Company Type?')"><i class="fas fa-trash"></i></button>
                    </form>
                </td>
            </tr>
        @endforeach
    @else
        <tr>
            <td colspan="11" class="text-center text-danger">Not found!</td>
        </tr>
    @endif

    </tbody>
</table>
