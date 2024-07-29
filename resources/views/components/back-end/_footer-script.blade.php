<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.4/xlsx.full.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/FileSaver.js/2.0.5/FileSaver.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.12.6/js/standalone/selectize.min.js" integrity="sha256-+C0A5Ilqmu4QcSPxrlGpaZxJ04VjsRjKu+G82kl5UJk=" crossorigin="anonymous"></script>
<script src="{{url("assets/js/scripts.js")}}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>--}}
{{--<script src="{{url("assets/js/chart-area-demo.js")}}"></script>--}}
{{--<script src="{{url("assets/js/chart-bar-demo.js")}}"></script>--}}
{{--<script src="https://cdn.jsdelivr.net/npm/simple-datatables@7.1.2/dist/umd/simple-datatables.min.js" crossorigin="anonymous"></script>--}}

<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{url("assets/js/datatables-simple-demo.js")}}"></script>
<script src="{{url("assets/js/custom.js")}}"></script>
@if(Route::CurrentRouteName() == 'add.complain'|| Route::CurrentRouteName() == 'edit.my.complain')
<script>
    ClassicEditor
        .create( document.querySelector( '#editor' ),{
            ckfinder: {
                uploadUrl: '{{route('editor-img-upload').'?_token='.csrf_token()}}'
            },
            // Changing the language of the interface requires loading the language file using the <script> tag.
            // language: 'es',
            list: {
                properties: {
                    styles: true,
                    startIndex: true,
                    reversed: true
                }
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/headings.html#configuration
            heading: {
                options: [
                    { model: 'paragraph', title: 'Paragraph', class: 'ck-heading_paragraph' },
                    { model: 'heading1', view: 'h1', title: 'Heading 1', class: 'ck-heading_heading1' },
                    { model: 'heading2', view: 'h2', title: 'Heading 2', class: 'ck-heading_heading2' },
                    { model: 'heading3', view: 'h3', title: 'Heading 3', class: 'ck-heading_heading3' },
                    { model: 'heading4', view: 'h4', title: 'Heading 4', class: 'ck-heading_heading4' },
                    { model: 'heading5', view: 'h5', title: 'Heading 5', class: 'ck-heading_heading5' },
                    { model: 'heading6', view: 'h6', title: 'Heading 6', class: 'ck-heading_heading6' }
                ]
            },
            // https://ckeditor.com/docs/ckeditor5/latest/features/editor-placeholder.html#using-the-editor-configuration
             placeholder: 'Write your complaint here in-details *',

        } )
        .catch( error => {
            console.error( error );
        } );
</script>
@endif
<script>
    $('.close').click(function() {
        $(".alert").hide(500)
    })
</script>
<script src="{!! url('assets/js/customRawScript.js') !!}"></script>
{{--<script>--}}
{{--    // Disable right-click context menu--}}
{{--    window.addEventListener('contextmenu', function (e) {--}}
{{--        e.preventDefault();--}}
{{--    });--}}

{{--    // Disable F12 key--}}
{{--    window.addEventListener('keydown', function (e) {--}}
{{--        if (e.key === 'F12') {--}}
{{--            e.preventDefault();--}}
{{--        }--}}
{{--    });--}}
{{--</script>--}}
