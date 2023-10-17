<?php

namespace App\Http\Controllers;

use App\Models\VoucherDocumentShareEmailLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ShareDocumentViewController extends Controller
{
    //
    public function voucherDocumentView(Request $request)
    {
        $id = Crypt::decryptString($request->get('document'));
        $shareID = $request->get('share');
        $document = VoucherDocumentShareEmailLink::with('voucherDocument')->where('status',1)->where('share_document_id',$id)->where('share_id',$shareID)->first();
        if ($document)
            return view('back-end/view-documet/voucher_document_view',compact('document'));
        else
            abort(404, 'This is a custom 404 message.');
    }
}
