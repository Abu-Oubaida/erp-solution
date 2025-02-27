<table class="table table-sm" id="datatablesSimple">
    <thead>
    <tr>
        <th>No</th>
        <th>Name</th>
        <th>Employee ID</th>
        <th>Designation</th>
        <th>Department</th>
        <th>Company</th>
        <th>Action</th>
    </tr>
    </thead>
    <tbody>
    @if(isset($data) && count($data))
        @php($n = 1)
        @foreach($data as $d)
            <tr>
                <td>{!! $n++ !!}</td>
                <td>{!! @$d->name !!}</td>
                <td>{!! @$d->employee_id !!}</td>
                <td>{!! @$d->designation->title !!}</td>
                <td>{!! @$d->department->dept_name !!}</td>
                <td>{!! @$d->getCompany->company_name !!}</td>
                <td></td>
            </tr>
        @endforeach
    @endif
    </tbody>
</table>
