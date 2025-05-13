@php
    $getLead = @$getLead ?? null;
@endphp
<form id="leadForm">
    <div class="card-body" id="commonSlot_for_multiple_step">
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="form-group">
                    <label for="company">Company Name<span class="text-danger">*</span></label>
                    <select class="text-capitalize form-control company_dropdown" id="company_id" name="company">
                        <option value="">Pick options...</option>
                        @if (isset($companies) || count($companies) > 0)
                            @foreach ($companies as $c)
                                <option value="{{ $c->id }}" @if ($getLead && $getLead->company_id == $c->id) selected @endif>
                                    {{ $c->company_name }}
                                    ({!! $c->company_code !!})
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                {{-- <input type="hidden" id="company_group" value="{{$companies}}"> --}}
            </div>
            <div class="col-md-4 mb-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="full_name" type="text" placeholder="Enter full name"
                                value="{{ $getLead->full_name ?? null }}" />
                            <label for="full_name">Full name <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    {{-- <div class="col-12">
                        <input type="hidden" value="{{$geatLead->id ?? null}}">
                    </div> --}}
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="spouse" type="text"
                                placeholder="Enter husband/wife name" value="{{ $getLead->spouse ?? null }}" />
                            <label for="relation">Husband/Wife </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="primary_mobile" type="number" placeholder="Primary Mobile"
                                value="{{ $getLead->primary_mobile ?? null }}" />
                            <label for="primary_mobile">Primary Mobile <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input class="form-control" id="mobile_1" type="number" placeholder="Enter phone 1"
                                @if (isset($getLead->extraMobiles) && count($getLead->extraMobiles)) value="{{ $getLead->extraMobiles[0]->mobile }}" @endif />
                            <label for="phone2">Alternative
                                Mobile 1</label>
                        </div>
                    </div>
                @if (!$getLead)
                    <div class="col-12 mt-1">
                    <a href="#" class="float-end text-decoration-none"
                        onclick="Sales.addEmailPhoneForLead(this,'mobile','add_extra_info')"><i
                            class="fas fa-plus small"></i> Add
                        Another Mobile</a>
                </div>
                @endif
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="row">
                    <div class="col-12">
                        <div class="form-floating mb-3">
                            <input class="form-control" id="primary_email" type="email" placeholder="Primary Email"
                                value="{{ $getLead->primary_email ?? null }}" />
                            <label for="primary_email">Primary Email <span class="text-danger">*</span></label>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="form-floating">
                            <input class="form-control" id="email_1" type="email" placeholder="Enter Email 2"  @if (isset($getLead->extraEmails) && count($getLead->extraEmails)) value="{{ $getLead->extraEmails[0]->email }}" @endif/>
                            <label for="alternative_email_1">Alternative Email 1</label>
                        </div>
                    </div>
                    @if (!$getLead)
                    <div class="col-12 mt-1">
                        <a href="#" class="float-end text-decoration-none"
                            onclick="Sales.addEmailPhoneForLead(this,'email','add_extra_info')"><i
                                class="fas fa-plus small"></i> Add
                            Another
                            Email</a>
                    </div>
                    @endif
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-floating mb-3">
                    <div class="form-floating mb-3">
                        <textarea type="text" class="form-control" id="notes" name="notes" placeholder="Notes">{{ $getLead->notes ?? null }}</textarea>
                        <label for="Designation">Notes</label>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="row" id="add_extra_info">
                    @if (isset($getLead->extraMobiles) && ($extraMobileCount = count($getLead->extraMobiles)))
                        @for ($i = 1; $i < $extraMobileCount; $i++)
                            <div class="col-3">
                                <div class="form-floating">
                                    <input class="form-control" id="mobile_{{ $i + 1 }}" type="number"
                                        placeholder="Enter Mobile {{ $i + 1 }}"
                                        value="{{ $getLead->extraMobiles[$i]->mobile }}" />
                                    <label for="alternative_mobile_".{{ $i + 1 }}>Alternative Mobile
                                        {{ $i + 1 }}</label>
                                </div>
                            </div>
                        @endfor
                    @endif
                    @if (isset($getLead->extraEmails) && ($extraEmailCount = count($getLead->extraEmails)))
                        @for ($i = 1; $i < $extraEmailCount; $i++)
                            <div class="col-3">
                                <div class="form-floating">
                                    <input class="form-control" id="email_{{ $i + 1 }}" type="email"
                                        placeholder="Enter Email {{ $i + 1 }}"
                                        value="{{ $getLead->extraEmails[$i]->email }}" />
                                    <label for="alternative_email_".{{ $i + 1 }}>Alternative Email
                                        {{ $i + 1 }}</label>
                                </div>
                            </div>
                        @endfor
                    @endif
                </div>
                <div class="row">
                    <div class="col">
                        <button type="button" class="btn btn-chl-outline float-end mt-3"
                            onclick="return addLeadStep1('{{ $getLead ? 'update' : 'create' }}',{{$getLead->id ?? null}})"><i
                                class="fa-solid fa-arrow-right"></i>
                            Next</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
