<div class="modal-content">
    <div class="modal-header">
        <h1 class="modal-title fs-5" id="v_document_title">{!! $result->document !!}</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">
        <div class="row">
            <div class="col-md-12">
                <span><i class="fa-regular fa-envelope"></i> Email Address: <sup class="text-danger">*</sup> <small><b>Note:  Insert Space ( )/Comma (,) After Every Email Address</b></small></span>
                <div class="form-floating mb-3">
                    <div class="tags-input" id="tags-input">
                        <input class="tag-input" type="text" list="users_email_list" placeholder="Add a people and group *" id="tag-input" onkeyup="return Obj.tagInput(this)">
                        <datalist id="users_email_list">
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
                    <button class="btn btn-success" id="submit-tags" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($result->id) !!}" onclick="return Obj.sendDocumentEmail(this)" type="button"><i class="fa-solid fa-share-from-square"></i> Send Mail</button>
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
                <span><i class="fa-solid fa-earth-americas"></i> Generate Online Link</span>
            </div>
            <div class="col-md-2">
                <div class="form-group mb-3">
                    <select class="form-control" name="voucher_type" id="voucher_type" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($result->id) !!}" onchange="return Obj.archiveShareType(this)">
                        <option value="0">--Select--</option>
                        <option value="1">Only view</option>
                        <option value="2">View/Download</option>
                    </select>
                </div>
            </div>
            <div class="col-md-8">
                <div class="form-group mb-3">
                    <input type="text" class="form-control" readonly id="sharedLink">
                </div>
            </div>
            <div class="col-md-2 text-end">
                <button class="btn btn-success" onclick="return Obj.copyDocumentShareLink(this)" type="button"><i class="fa-regular fa-copy"></i> Copy link</button>
            </div>
            <div class="col-md-12">
                <span><i class="fa-regular fa-envelope"></i> This document had been shared with the following emails</span>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Emails</th>
                            <th>Status</th>
                            <th>Share By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    @foreach($shareData as $e)
                        <tr>
                            <td><a href="{!! $shareLink = route('archive.document.view',['document'=>Crypt::encryptString($e->share_document_id),'share'=>$e->share_id]); !!}" target="_blank">{!! $e->share_id !!}</a></td>
                            <td>
                                @if(count($e->ShareEmails))
                                    @foreach($e->ShareEmails as $email)
                                        {!! $email->email !!} <br>
                                    @endforeach
                                @endif
                            </td>
                            <td>@if($e->status) <span class="badge bg-success">Active</span> @else <span class="badge bg-danger">Inactive</span> @endif</td>
                            <td>{!! @$e->sharedBy->name !!}</td>
                            <td>
                                @if($e->status) <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure!')? Obj.emailLinkStatusChange(this):false" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($e->id) !!}" status="{!! \Illuminate\Support\Facades\Crypt::encryptString($e->status) !!}">Make Inactive</button> @else <button class="btn btn-success btn-sm" onclick="return confirm('Are you sure!')? Obj.emailLinkStatusChange(this):false" ref="{!! \Illuminate\Support\Facades\Crypt::encryptString($e->id) !!}" status="{!! \Illuminate\Support\Facades\Crypt::encryptString($e->status) !!}">Make Active</button> @endif

                            </td>
                        </tr>
                    @endforeach
                </table>
            </div>
        </div>

    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Understood</button>
    </div>
</div>
