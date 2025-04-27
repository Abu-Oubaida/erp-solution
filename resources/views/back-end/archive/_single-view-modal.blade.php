<div class="row">
    <div id="" class="">
        <div class="col-md-12 mb-2">
            @if($previous_document_id)
                <a ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($previous_document_id) !!}" onclick="return Obj.findDocument(this,'documentPreview', '{!! $root_ref !!}')" class="btn btn-sm btn-outline-danger float-left"><i class="fas fa-angle-left"></i> Previous <i class="fas fa-file-lines"></i></a>
            @endif
            @if($next_document_id)
                <a ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($next_document_id) !!}" onclick="return Obj.findDocument(this,'documentPreview', '{!! $root_ref !!}')" class="btn btn-sm btn-outline-primary float-end"> <i class="fas fa-file-lines"></i> Next <i class="fas fa-angle-right"></i></a>
            @endif
        </div>
        <div class="col-md-12">
            <h1 class="modal-title fs-5 d-inline-block" id="v_document_name"><b>File Name</b>: {!! $document->document !!}</h1>
        </div>
    @if($document->accountVoucherInfo->voucher_number == $root_ref)
        <div class="col">
            <span><strong>Reference</strong>: {!! $document->accountVoucherInfo->voucher_number !!}</span>
        </div>
    @else
        <div class="col">
            <span><strong>Original Reference</strong>: {!! $document->accountVoucherInfo->voucher_number !!}</span>
        </div>
        <div class="col">
            <span><strong>Linked Reference</strong>: {!! $root_ref !!}</span>
        </div>
    @endif
        <div class="col">
            <span class="float-end"><strong>Type</strong>:{!! $document->accountVoucherInfo->VoucherType->voucher_type_title !!}</span>
        </div>
        <div class="col-md-12">
            <strong>Remarks:</strong> {!! $document->accountVoucherInfo->remarks !!}
        </div>
    </div>
    @php
        // Extract the file extension
        $fileExtension = pathinfo($document->filepath.$document->document,PATHINFO_EXTENSION);
        $pdfUrl = asset('storage/archive_data/'.$document->filepath.$document->document);
        $encodedUrl = urlencode($pdfUrl); // full encoding
    @endphp
    @if (in_array($fileExtension, ['jpg','jpeg','png','JPG']))
        <embed src="{{ $pdfUrl }}#toolbar=0" style="width:100%;" />
    @elseif (in_array($fileExtension, ['pdf']))
        <style>
            html {
                scroll-behavior: smooth;
            }
            canvas {
                width: 100%!important;
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
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <button onclick="goToPrevPage()" class="btn btn-sm text-danger"><i class="fas fa-angle-left"></i> Previous</button>
                    <span>Page: <span id="page-num">1</span> / <span id="page-count">--</span></span>
                    <button onclick="goToNextPage()" class="btn btn-sm text-primary">Next <i class="fas fa-angle-right"></i></button>
                </div>
                <div class="col-md-4">
                    <div class="row">
                        <div class="col-sm-9">
                            <input class="form-control form-control-sm" type="number" id="page-input" min="1" placeholder="Go to page">
                        </div>
                        <div class="col-sm-3">
                            <button onclick="goToPage()" class="btn btn-sm btn-outline-success"><i class="fas fa-search"></i> Go</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-3">
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

                // Render a single page
                var renderPage = (page, num) => {
                    var viewport = page.getViewport({ scale: 1.5 });

                    // Create a container for canvas and page number
                    var pageWrapper = document.createElement('div');
                    pageWrapper.style.textAlign = 'center';
                    pageWrapper.style.marginBottom = '20px';

                    var canvas = document.createElement('canvas');
                    var ctx = canvas.getContext('2d');

                    canvas.width = viewport.width;
                    canvas.height = viewport.height;
                    canvas.style.border = '1px solid #ccc';
                    canvas.setAttribute('data-page-number', num);

                    var pageLabel = document.createElement('div');
                    pageLabel.textContent = `Page ${num}`;
                    pageLabel.style.marginTop = '5px';
                    pageLabel.style.fontWeight = 'bold';

                    pageWrapper.appendChild(canvas);
                    pageWrapper.appendChild(pageLabel);
                    container.appendChild(pageWrapper);
                    canvasList.push(canvas);

                    var renderContext = {
                        canvasContext: ctx,
                        viewport: viewport
                    };
                    page.render(renderContext);
                };


                var scrollToPage = (num) => {
                    var canvas = canvasList[num - 1];
                    if (canvas) {
                        canvas.scrollIntoView({ behavior: 'smooth', block: 'start' });
                        currentPage = num;
                        document.getElementById('page-num').textContent = currentPage;
                    }
                };

                var goToPrevPage = () => {
                    if (currentPage > 1) {
                        scrollToPage(currentPage - 1);
                    }
                };

                var goToNextPage = () => {
                    if (currentPage < totalPages) {
                        scrollToPage(currentPage + 1);
                    }
                };

                var goToPage = () => {
                    var input = document.getElementById('page-input');
                    var targetPage = parseInt(input.value);
                    if (targetPage >= 1 && targetPage <= totalPages) {
                        scrollToPage(targetPage);
                    }
                };

                var printPDF = () => {
                    var printWindow = window.open(url, '_blank');
                    printWindow.focus();
                    printWindow.print();
                };

                pdfjsLib.getDocument(url).promise.then(pdfDoc => {
                    totalPages = pdfDoc.numPages;
                    document.getElementById('page-count').textContent = totalPages;

                    var renderAllPages = async () => {
                        for (let i = 1; i <= totalPages; i++) {
                            var page = await pdfDoc.getPage(i);
                            renderPage(page, i);
                        }
                    };

                    renderAllPages().then(() => {
                        scrollToPage(1);
                    });
                });
            </script>
        </div>
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
<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>
<div id='ajax_loader' style="position: fixed; left: 50%; top: 40%;z-index: 1000; display: none">
    <img width="50%" src="{{url('image/ajax loding/ajax-loading-gif-transparent-background-2.gif')}}"/>
</div>
