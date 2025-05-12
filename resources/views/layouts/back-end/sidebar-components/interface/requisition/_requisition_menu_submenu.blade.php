@if(auth()->user()->hasPermission('document_requisition') )

@if(Request::segment(1) == "requisition")
    <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#documentRequisitionOption" aria-expanded="true" aria-controls="documentRequisitionOption">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-folder-tree"></i></div>
        Document
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse show" id="documentRequisitionOption" aria-labelledby="headingOne" data-bs-parent="#documentRequisitionOption">
@else
    <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#documentRequisitionOption" aria-expanded="false" aria-controls="documentRequisitionOption">
        <div class="sb-nav-link-icon"><i class="fa-solid fa-folder-tree"></i></div>
        Document
        <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
    </a>
    <div class="collapse" id="documentRequisitionOption" aria-labelledby="headingOne" data-bs-parent="#documentRequisitionOption">
@endif
        <nav class="sb-sidenav-menu-nested nav">
            @if(auth()->user()->hasPermission('project_document_requisition_entry'))
                @if(Route::currentRouteName() == 'project.document.requisition.entry')
                    <a class="nav-link" href="{{route('project.document.requisition.entry')}}" title="Create Project Document Requisition"><div class="sb-nav-link-icon"><i class="fa-solid fa-file-circle-plus"></i></div> Req. Create</a>
                @else
                    <a class="nav-link text-chl" href="{{route('project.document.requisition.entry')}}"><div class="sb-nav-link-icon" title="Create Project Document Requisition"><i class="fa-solid fa-file-circle-plus"></i></div> Req. Create</a>
                @endif
            @endif
            @if(auth()->user()->hasPermission('project_document_requisition_report'))
                @if(Route::currentRouteName() == 'project.document.requisition.report')
                    <a class="nav-link" href="{{route('project.document.requisition.report')}}" title="Project Document Requisition Report"><div class="sb-nav-link-icon"><i class="fas fa-file-lines"></i></div> Req. Report</a>
                @else
                    <a class="nav-link text-chl" href="{{route('project.document.requisition.report')}}"><div class="sb-nav-link-icon" title="Project Document Requisition Report"><i class="fas fa-file-lines"></i></div> Req. Report</a>
                @endif
            @endif
        </nav>
    </div>
@endif

