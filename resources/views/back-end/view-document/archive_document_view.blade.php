<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>{{str_replace('-', ' | ', config('app.name'))}}</title>
    <link rel="icon" href="{{url("image/logo/chl_logo.png")}}">
    <x-back-end._header-link/>
    <meta name="csrf-token" content="{{ csrf_token() }}" />
</head>
<body class="sb-nav-fixed" style="margin: 0 auto;padding: 0; width: 80%;">
<nav class="sb-topnav navbar navbar-expand navbar-dark bg-chl-white">
    <!-- Navbar Brand-->
{{--    <a class="navbar-brand ps-3" href="{{route('root')}}"><img src="{{url("image/logo/default/icon/360.png")}}" width="70%" alt="Credence Housing Limited"></a>--}}
    <a class="navbar-brand ps-3 text-black" href="http://192.168.66.6"><h5><img src="http://192.168.66.6/image/logo/default/icon/360.png" alt="ERP-360˚" class="img-fluid" width="20%"> Smart Solution</h5></a>
    <!-- Sidebar Toggle-->
{{--    <button class="btn btn-link btn-sm order-1 order-lg-0 me-4 me-lg-0 text-chl-important" id="sidebarToggle"><i class="fas fa-bars"></i></button>--}}
    <!-- Navbar Search-->
    <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
        <div class="input-group">
            {{--            <input class="form-control" type="text" placeholder="Search for..." aria-label="Search for..." aria-describedby="btnNavbarSearch" />--}}
            {{--            <button class="btn btn-primary btn-chl" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>--}}
        </div>
    </form>
    <!-- Navbar-->
    <ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4 bg-chl" style="border-radius: 10px;">
        <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fas fa-user fa-fw"></i></a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                @if(auth()->user())
                    <li><a class="dropdown-item" href="{!! route("logout") !!}">Log Out</a></li>
                @else
                    <li><a class="dropdown-item" href="{!! route("login") !!}">Login</a></li>
                @endif
            </ul>
        </li>
    </ul>
</nav>
<div id="" class="">
    <div id="layoutSidenav_content">
        <main>
            <br><br><br>
            @if(isset($documentEmail))
                @php
                    // Extract the file extension
                    $fileExtension = pathinfo($documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document,PATHINFO_EXTENSION);
                    $pdfUrl = asset('storage/archive_data/'.$documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document);
                    $encodedUrl = urlencode($pdfUrl); // full encoding
                @endphp

                @if (in_array($fileExtension, ['jpg','jpeg','png','JPG']))
                    <embed src="{{ $pdfUrl }}#toolbar=0" style="width:100%;" />
                @elseif (in_array($fileExtension, ['pdf']))
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
                                @if(auth()->user() && auth()->user()->hasPermission('archive_document_print'))
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
                @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))
                    <video controls style="width: 80%">
                        <source src="{{ asset('storage/archive_data/'.$documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                            <a class="btn btn-success text-center" href="{{ asset('storage/archive_data/'.$documentEmail->voucherDocument->filepath.$documentEmail->voucherDocument->document) }}" download>
                                Click To Download
                            </a>
                        </div>
                    </div>
                @endif
            @elseif(isset($document))
                @php
                    // Extract the file extension
                    $fileExtension = pathinfo($document->voucherDocument->filepath.$document->voucherDocument->document,PATHINFO_EXTENSION);
                $pdfUrl = asset('storage/archive_data/'.$document->voucherDocument->filepath.$document->voucherDocument->document);
                $encodedUrl = urlencode($pdfUrl); // full encoding
                @endphp

                @if (in_array($fileExtension, ['jpg','jpeg','png','JPG']))
                    <embed src="{{ $pdfUrl }}#toolbar=0" style="width:100%;" />
                @elseif (in_array($fileExtension, ['pdf']))
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
                                @if(auth()->user() && auth()->user()->hasPermission('archive_document_print'))
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
                @elseif (in_array($fileExtension, ['mp4', 'webm', 'ogg']))
                    <video controls style="width: 80%">
                        <source src="{{ asset('storage/archive_data/'.$document->voucherDocument->filepath.$document->voucherDocument->document) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                @else
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <h1 class="text-center">Sorry! This file type is not supported for preview.</h1>
                            <a class="btn btn-success text-center" href="{{ asset('storage/archive_data/'.$document->voucherDocument->filepath.$document->voucherDocument->document) }}" download>
                                Click To Download
                            </a>
                        </div>
                    </div>
                @endif
            @endif
        </main>
    </div>
</div>
<x-back-end._footer-script/>
<script>
    document.getElementById('pdfViewer').addEventListener('contextmenu', function(event) {
        event.preventDefault(); // Prevent the default right-click behavior
    });
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    document.onkeydown = (e) => {
        if (e.key == 123) {
            e.preventDefault();
        }
        if (e.ctrlKey && e.shiftKey && e.key == 'I') {
            e.preventDefault();
        }
        if (e.ctrlKey && e.shiftKey && e.key == 'C') {
            e.preventDefault();
        }
        if (e.ctrlKey && e.shiftKey && e.key == 'J') {
            e.preventDefault();
        }
        if (e.ctrlKey && e.key == 'U') {
            e.preventDefault();
        }
    };
</script>
<script>
    document.addEventListener('contextmenu', event => event.preventDefault());
</script>
</body>
</html>
