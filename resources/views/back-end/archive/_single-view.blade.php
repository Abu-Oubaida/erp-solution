@if($document)
    @php
        // Extract the file extension
        $fileExtension = pathinfo($document->filepath.$document->document,PATHINFO_EXTENSION);
        $pdfUrl = asset('storage/archive_data/'.$document->filepath.$document->document);
        $encodedUrl = urlencode($pdfUrl); // full encoding
    @endphp
    <div class="row">
        <div class="col-md-12 mb-2">
            @if($previous_document_id)
                <a href="{!! route('view.archive.document',['vID'=>\Illuminate\Support\Facades\Crypt::encryptString($previous_document_id),'ref'=>$ref]) !!}" class="btn btn-sm btn-outline-danger float-left"><i class="fas fa-angle-left"></i> Previous <i class="fas fa-file-lines"></i></a>
            @endif
            @if($next_document_id)
                <a href="{!! route('view.archive.document',['vID'=>\Illuminate\Support\Facades\Crypt::encryptString($next_document_id), 'ref'=>$ref]) !!}" class="btn btn-sm btn-outline-primary float-end"> <i class="fas fa-file-lines"></i> Next <i class="fas fa-angle-right"></i></a>
            @endif
        </div>
        <div id="" class="">
            @if(auth()->user()->hasPermission('share_archive_data_individual'))
                <button class="btn btn-outline-success float-end m-1" href="" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($document->id) !!}" onclick="return Obj.fileSharingModal(this)" title="Share Document"><i class="fa-solid fa-envelope"></i> Email Document</button>
            @endif
            @if(auth()->user()->hasPermission('archive_document_edit'))
                <!-- Button trigger modal -->
                <button type="button" class="btn btn-outline-info float-end m-1" title="Edit Document" data-bs-toggle="modal" data-bs-target="#editDocumentModal">
                    <i class="fa-solid fa-edit"></i> Replace Document
                </button>

                <!-- Modal -->
                <div class="modal fade" id="editDocumentModal" tabindex="-1" aria-labelledby="editDocumentModallLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editDocumentModalLabel"><i class="fas fa-file-edit"></i> Replace Document</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <label>
                                    <a href="{!! $pdfUrl !!}" download="{!! $document->document !!}">click here</a> to download previously uploaded document
                                </label>
                                <hr>
                                <form action="{{ route('archive.document.update') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="id" value="{{ $document->id }}">
                                    <div class="form-group mb-1">
                                        <label for="document">Upload Modified Document Here</label>
                                        <input type="file" class="form-control" id="document" name="document">
                                    </div>

                                    <button type="submit" class="btn btn-sm btn-chl-outline mb-1 mt-1 float-end"><i class="fas fa-save"></i> Replace</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            <div class="col-md-12">
                <h1 class="modal-title fs-5 d-inline-block" id="v_document_name"><b>File Name</b>: {!! $document->document !!}</h1>
            </div>
            <div class="col">
                <span><strong>Reference Number</strong>:{!! $document->accountVoucherInfo->voucher_number !!}</span>
            </div>
            <div class="col">
                <span class="float-end"><strong>Type</strong>:{!! $document->accountVoucherInfo->VoucherType->voucher_type_title !!}</span>
            </div>
            <div class="col-md-12">
                <strong>Remarks:</strong> {!! $document->accountVoucherInfo->remarks !!}
            </div>
        </div>
        @if (in_array($fileExtension, ['jpg','jpeg','png','JPG']))
            <embed src="{{ $pdfUrl }}" style="width:100%;" />
        @elseif (in_array($fileExtension, ['pdf']))
            @if(\Illuminate\Support\Facades\Request::get('ocr'))
                <embed src="{{ $pdfUrl }}" style="width:100%; min-height: 800px;" class="mobile-none"/>
                <script>
                    document.addEventListener("DOMContentLoaded", function () {
                        // Basic mobile detection
                        const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                        if (isMobile) {
                            const link = document.createElement("a");
                            link.href = "{{ $pdfUrl }}";
                            link.download = ""; // Optional: you can add a filename like "document.pdf"
                            document.body.appendChild(link);
                            link.click();
                            document.body.removeChild(link);
                        }
                    });
                </script>
            @else
                <style>
                    html {
                        scroll-behavior: smooth;
                    }
                    footer {
                        z-index: 1!important;
                    }
                    #pdf-render {
                        width: 100%;
                        height: auto;
                    }
                    #pdf-container {
                        flex-direction: column;
                        align-items: center;
                    }
                    #download {
                        display: none !important;
                    }

                    .textLayer {
                        pointer-events: none; /* Makes text unselectable */
                    }
                    .fixed {
                        position: fixed;
                        top: 5%;
                        left: 14%;
                        right: 0;
                        width: 86%;
                        z-index: 999;
                        background-color: white;
                        padding: 10px 15px;
                        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                    }
                    #pdf-container-wrapper {
                        position: relative;
                        top: 0;
                        bottom: 0;
                        left: 0;
                        right: 0;
                        overflow-y: auto;
                        padding-top: 20px; /* if you want top spacing */
                        text-align: center;
                        background: white;
                        max-height: 700px;
                        scroll-behavior: smooth; /* smooth scroll */
                    }

                    #pdf-container {
                        margin: 0 auto;
                        z-index: 1;
                        align-items: center;
                    }
                </style>
                <div class="col-md-12 mb-1">
                    <div class="row">
                        <div class="col-md-3">
                            <button onclick="goToPrevPage()" class="btn btn-sm text-danger"><i class="fas fa-angle-left"></i> Previous</button>
                            <span>Page: <span id="page-num">1</span> / <span id="page-count">--</span></span>
                            <button onclick="goToNextPage()" class="btn btn-sm text-primary">Next <i class="fas fa-angle-right"></i></button>
                        </div>
                        <div class="col-md-3">
                            <div class="row">
                                <div class="col-sm-9">
                                    <input class="form-control form-control-sm" type="number" id="page-input" min="1" placeholder="Go to page">
                                </div>
                                <div class="col-sm-3">
                                    <button onclick="goToPage()" class="btn btn-sm btn-outline-success"><i class="fas fa-search"></i> Go</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-9 text-center">
                            <button onclick="zoomOut()" class="btn btn-sm btn-outline-warning"><i class="fa-solid fa-magnifying-glass-minus"></i> Zoom Out</button>
                            <button onclick="zoomIn()" class="btn btn-sm btn-outline-info"><i class="fa-solid fa-magnifying-glass-plus"></i> Zoom In</button>
                        </div>
                        <div class="col-md-3 col-sm-3">
                            @if(auth()->user()->hasPermission('archive_document_print'))
                                <button class="btn btn-outline-primary btn-sm float-end"  onclick="printPDF()"><i class="fas fa-print"></i> Print</button>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="col-md-12">
                    <div id="pdf-container-wrapper">
                        <div class="text-center" id="pdf-container"></div>
                    </div>
                    <script>
                        var url = "{{ $pdfUrl }}";

                        var pdfjsLib = window['pdfjs-dist/build/pdf'];
                        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.11.174/pdf.worker.min.js';

                        var container = document.getElementById('pdf-container');
                        var currentPage = 1;
                        var totalPages = 0;
                        var canvasList = [];
                        var scale = 1.5; // initial zoom
                        var pdfDoc = null;

                        // Render a single page
                        const renderPage = (page, num) => {
                            const viewport = page.getViewport({ scale });

                            const pageWrapper = document.createElement('div');
                            pageWrapper.style.textAlign = 'center';
                            pageWrapper.style.marginBottom = '20px';

                            const canvas = document.createElement('canvas');
                            const ctx = canvas.getContext('2d');

                            canvas.width = viewport.width;
                            canvas.height = viewport.height;
                            canvas.style.border = '1px solid #ccc';
                            canvas.setAttribute('data-page-number', num);

                            const pageLabel = document.createElement('div');
                            pageLabel.textContent = `Page ${num}`;
                            pageLabel.style.marginTop = '5px';
                            pageLabel.style.fontWeight = 'bold';

                            pageWrapper.appendChild(canvas);
                            pageWrapper.appendChild(pageLabel);
                            container.appendChild(pageWrapper);
                            canvasList.push(canvas);

                            const renderContext = {
                                canvasContext: ctx,
                                viewport: viewport
                            };
                            page.render(renderContext);
                        };

                        const renderAllPages = async () => {
                            container.innerHTML = '';
                            canvasList.length = 0;

                            for (let i = 1; i <= totalPages; i++) {
                                const page = await pdfDoc.getPage(i);
                                renderPage(page, i);
                            }

                            scrollToPage(currentPage);
                        };
                        const scrollToPage = (num) => {
                            const canvas = canvasList[num - 1];
                            const wrapper = document.getElementById('pdf-container-wrapper');

                            if (canvas && wrapper) {
                                const offset = 300; // optional top margin
                                const canvasTop = canvas.offsetTop - offset;
                                wrapper.scrollTo({ top: canvasTop, behavior: 'smooth' });

                                currentPage = num;
                                document.getElementById('page-num').textContent = currentPage;
                            }
                        };

                        const goToPrevPage = () => {
                            if (currentPage > 1) {
                                scrollToPage(currentPage - 1);
                            }
                        };

                        const goToNextPage = () => {
                            if (currentPage < totalPages) {
                                scrollToPage(currentPage + 1);
                            }
                        };

                        const goToPage = () => {
                            const input = document.getElementById('page-input');
                            const targetPage = parseInt(input.value);
                            if (targetPage >= 1 && targetPage <= totalPages) {
                                scrollToPage(targetPage);
                            }
                        };

                        const zoomIn = () => {
                            scale += 0.2;
                            renderAllPages();
                        };

                        const zoomOut = () => {
                            if (scale > 0.4) {
                                scale -= 0.2;
                                renderAllPages();
                            }
                        };

                        const printPDF = () => {
                            const printWindow = window.open(url, '_blank');
                            printWindow.focus();
                            printWindow.print();
                        };

                        // Load PDF and assign to global
                        pdfjsLib.getDocument(url).promise.then(loadedPdf => {
                            pdfDoc = loadedPdf; // ✅ properly assign to global
                            totalPages = pdfDoc.numPages;
                            document.getElementById('page-count').textContent = totalPages;
                            renderAllPages();
                        });
                    </script>

                </div>
            @endif
        @elseif ($fileExtension === ['mp4'])
            <video controls style="width: 80%">
                <source src="{{ asset('storage/archive_data/'.$document->filepath.$document->document) }}" type="video/mp4">
                Your browser does not support the video tag.
            </video>
        @else
            <div class="row">
                <div class="col-md-12 text-center">
                    <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                    <a class="btn btn-success text-center" href="{{ asset('storage/archive_data/'.$document->filepath.$document->document) }}" download>
                        Click To Download
                    </a>
                </div>
            </div>

        @endif
    </div>
    <!-- Modal-2 For Share -->
    <div class="modal modal-xl fade" id="shareModel" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="shareModelLabel" aria-hidden="true">
        <div class="modal-dialog" id="model_dialog">

        </div>
        <div id='ajax_loader2' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
            <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
        </div>
    </div>

@endif


<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>
