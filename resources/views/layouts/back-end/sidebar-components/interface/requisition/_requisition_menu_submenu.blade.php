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
                @if(auth()->user()->hasPermission('add_document_requisition'))
                    @if(Route::currentRouteName() == 'document.requisition.add')
                        <a class="nav-link" href="{{route('document.requisition.add')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Create Requisition</a>
                    @else
                        <a class="nav-link text-chl" href="{{route('document.requisition.add')}}"><div class="sb-nav-link-icon"><i class="fas fa-solid fa-plus"></i></div> Create Requisition</a>
                    @endif
                @endif

            </nav>
@endif

