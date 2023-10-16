<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="v_document_title">{!! $results->document !!}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <span><i class="fa-regular fa-envelope"></i> You can share your document link via email <sup class="text-danger">*</sup></span>
                <div class="form-floating mb-3">
                    <div class="tags-input" id="tags-input">
                        <input class="tag-input" type="text" list="users" placeholder="Add a people and group *" id="tag-input" onkeyup="return Obj.tagInput(this)">
                        <datalist id="users">
                    @if(count($userEmails))
                        @foreach($userEmails as $u)
                            <option value="{!! $u->email !!}">{!! $u->name !!}</option>
                        @endforeach
                        </datalist>
                    @endif
                        <div class="tags" id="tags"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating mb-3">
                    <input class="form-control" name="message" id="message"/>
                    <label for="message">Message</label>
                </div>
            </div>
            <div class="col-md-12">
                <div class="form-floating mb-3 float-end">
                    <button class="btn btn-success" id="submit-tags" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($results->id) !!}" onclick="return Obj.sendDocumentEmail(this)"><i class="fa-solid fa-share-from-square"></i> Send Mail</button>
                </div>
            </div>

            <div class="col-md-12">
                <div class="hr-text">
                    <hr>
                    <span>OR</span>
                    <hr>
                </div>
            </div>
            <div class="col-md-12">
                <br>
                <span><i class="fa-solid fa-earth-americas"></i> Anyone on the internet of this system with the link can view</span>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <select class="form-control" name="voucher_type" id="voucher_type">
                        <option value="1">Only view</option>
                        <option value="2">View/Download</option>
                    </select>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <input type="text" class="form-control">
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-success"><i class="fa-solid fa-link"></i> Copy link</button>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Understood</button>
    </div>
</div>
<script>
    {{--const tagInput = document.getElementById('tag-input');--}}
    {{--const tagsDiv = document.getElementById('tags');--}}
    {{--const submitButton = document.getElementById('submit-tags');--}}

    {{--const tags = [];--}}

    {{--tagInput.addEventListener('keydown', function(event) {--}}
    {{--    if (event.key === 'Enter' && tagInput.value.trim() !== '') {--}}
    {{--        const tagValue = tagInput.value.trim();--}}
    {{--        const tag = document.createElement('div');--}}
    {{--        tag.classList.add('tag');--}}
    {{--        tag.textContent = tagValue;--}}
    {{--        tagsDiv.appendChild(tag);--}}
    {{--        tagInput.value = '';--}}
    {{--        tags.push(tagValue);--}}
    {{--    }--}}
    {{--});--}}

    // tagsDiv.addEventListener('click', function(event) {
    //     if (event.target.classList.contains('tag')) {
    //         const tagValue = event.target.textContent;
    //         const index = tags.indexOf(tagValue);
    //         if (index !== -1) {
    //             tags.splice(index, 1);
    //         }
    //         event.target.remove();
    //     }
    // });

    {{--submitButton.addEventListener('click', function() {--}}
    {{--    // Send the tags to the server via an AJAX request--}}
    {{--    const url = window.location.origin + sourceDir + "/share-voucher-document-email";; // Replace with your server endpoint--}}
    {{--    const refId = this.getAttribute('ref')--}}
    {{--    const data = { tags: tags , refId: refId};--}}
    {{--    if(data.tags.length <= 0)--}}
    {{--    {--}}
    {{--        alert("Error! Empty Field")--}}
    {{--        return false--}}
    {{--    }else {--}}
    {{--        fetch(url, {--}}
    {{--            method: 'POST',--}}
    {{--            headers: {--}}
    {{--                'Content-Type': 'application/json',--}}
    {{--                'X-CSRF-TOKEN': '{{ csrf_token() }}' // Include CSRF token if needed--}}
    {{--            },--}}
    {{--            body: JSON.stringify(data)--}}
    {{--        })--}}
    {{--            .then(response => response.json())--}}
    {{--            .then(data => {--}}
    {{--                console.log('Tags submitted:', data);--}}
    {{--                // Handle the response from the server if needed--}}
    {{--            })--}}
    {{--            .catch(error => console.error('Error:', error));--}}
    {{--    }--}}
    {{--});--}}

</script>
