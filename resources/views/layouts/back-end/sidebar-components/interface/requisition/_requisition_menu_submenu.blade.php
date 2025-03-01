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
                @if(auth()->user()->hasPermission('document_requisition_list') )
                    @if(Request::segment(2) == "document-report" )
                        <a class="nav-link" href="#" data-bs-toggle="collapse" data-bs-target="#reqDocumentReport" aria-expanded="true" aria-controls="reqDocumentReport" title="Document Requisition List">
                            <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                            List
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse show" id="reqDocumentReport" aria-labelledby="headingOne" data-bs-parent="#reqDocumentReport">
                    @else
                        <a class="nav-link collapsed text-chl" href="#" data-bs-toggle="collapse" data-bs-target="#reqDocumentReport" aria-expanded="false" aria-controls="reqDocumentReport" title="Document Requisition List">
                            <div class="sb-nav-link-icon"><i class="fas fa-file"></i></div>
                            List
                            <div class="sb-sidenav-collapse-arrow"><i class="fas fa-angle-down"></i></div>
                        </a>
                        <div class="collapse" id="reqDocumentReport" aria-labelledby="headingOne" data-bs-parent="#reqDocumentReport">
                    @endif
                            <nav class="sb-sidenav-menu-nested nav">
                                @if(auth()->user()->hasPermission('document_requisition_received_list'))
                                    @if(Route::currentRouteName() == 'document.requisition.received.list')
                                        <a class="nav-link" href="{{route('document.requisition.received.list')}}" title="Receive requisition list"><div class="sb-nav-link-icon"><i class="fa-solid fa-inbox"></i></div>Received</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('document.requisition.received.list')}}" title="Receive requisition list"><div class="sb-nav-link-icon"><i class="fa-solid fa-inbox"></i></div>Received</a>
                                    @endif
                                @endif
                                @if(auth()->user()->hasPermission('document_requisition_sent_list'))
                                    @if(Route::currentRouteName() == 'document.requisition.sent.list')
                                        <a class="nav-link" href="{{route('document.requisition.sent.list')}}" title="Receive requisition list"><div class="sb-nav-link-icon"><i class="fa-solid fa-paper-plane"></i></div>Sent</a>
                                    @else
                                        <a class="nav-link text-chl" href="{{route('document.requisition.sent.list')}}" title="Receive requisition list"><div class="sb-nav-link-icon"><i class="fa-solid fa-paper-plane"></i></div>Sent</a>
                                    @endif
                                @endif
                            </nav>
                        </div>
                @endif
            </nav>
@endif

