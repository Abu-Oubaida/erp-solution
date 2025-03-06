<?php

namespace App\Http\Controllers;

use App\Models\Voucher_share_email_link;
use App\Models\VoucherDocumentShareEmailLink;
use App\Models\VoucherDocumentShareLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ShareDocumentViewController extends Controller
{
    //
    public function voucherDocumentView(Request $request)
    {
        $id = Crypt::decryptString($request->get('document'));
        $shareID = $request->get('share');
        $documentEmail = VoucherDocumentShareEmailLink::with('voucherDocument')->where('status',1)->where('share_document_id',$id)->where('share_id',$shareID)->first();
        $document = VoucherDocumentShareLink::with('voucherDocument')->where('status',1)->where('share_document_id',$id)->where('share_id',$shareID)->first();
        if ($document)
            return view('back-end/view-document/voucher_document_view',compact('document'));
        elseif ($documentEmail)
            return view('back-end/view-document/voucher_document_view',compact('documentEmail'));
        else
            abort(404, 'This is a custom 404 message.');
    }
    public function voucherView(Request $request)
    {
//        $id = Crypt::decryptString($request->get('document'));
//        $shareID = $request->get('share');
//        $documentEmail = VoucherDocumentShareEmailLink::with('voucherDocument')->where('status',1)->where('share_document_id',$id)->where('share_id',$shareID)->first();
//        $document = VoucherDocumentShareLink::with('voucherDocument')->where('status',1)->where('share_document_id',$id)->where('share_id',$shareID)->first();
//        if ($document)
//            return view('back-end/view-document/voucher_document_view',compact('document'));
//        elseif ($documentEmail)
//            return view('back-end/view-document/voucher_document_view',compact('documentEmail'));
//        else
//            abort(404, 'This is a custom 404 message.');
    }
}
