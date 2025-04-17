<?php

namespace App\Http\Controllers;

use App\Models\Account_voucher;
use App\Models\Voucher_share_email_link;
use App\Models\VoucherDocument;
use App\Models\VoucherDocumentShareEmailLink;
use App\Models\VoucherDocumentShareLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class ShareDocumentViewController extends Controller
{
    //
    public function archiveDocumentView(Request $request)
    {
        $id = Crypt::decryptString($request->get('document'));
        $shareID = $request->get('share');
        $documentEmail = VoucherDocumentShareEmailLink::with('voucherDocument')->where('status',1)->where('share_document_id',$id)->where('share_id',$shareID)->first();
        $document = VoucherDocumentShareLink::with('voucherDocument')->where('status',1)->where('share_document_id',$id)->where('share_id',$shareID)->first();
        if ($document)
            return view('back-end/view-document/archive_document_view',compact('document'));
        elseif ($documentEmail)
            return view('back-end/view-document/archive_document_view',compact('documentEmail'));
        else
            abort(404, 'This is a custom 404 message.');
    }
    public function archiveView(Request $request)
    {
        $id = null;
        if ($request->get('archive'))
        {
            $id = Crypt::decryptString($request->get('archive')); //voucher info id
        }

        $shareID = $request->get('share');
        $shareArchives = null;
        if (!$id)
        {
            $shareArchives = Voucher_share_email_link::with('shareArchive','shareArchive.voucherDocuments')->where('status',1)->where('share_id',$shareID)->get()->pluck('share_voucher_id')->toArray();
        }
        else
        {
            $shareArchives = Voucher_share_email_link::with('shareArchive','shareArchive.voucherDocuments')->where('status',1)->where('share_voucher_id',$id)->where('share_id',$shareID)->get()->pluck('share_voucher_id')->toArray();
        }
        $archives = Account_voucher::with(['voucherDocuments','VoucherType','company'])->whereIn('id',$shareArchives)->get();
        if (count($archives))
            return view('back-end/view-document/archive_view',compact('archives'));
        else
            abort(404, 'This is a custom 404 message.');
    }

    public function viewDocument($id)
    {
        // Fetch the document from the database
        $document = VoucherDocument::findOrFail(Crypt::decryptString($id));
        // Build the file path
        $filePath = public_path($document->filepath . $document->document);
        // Check if the file exists
        if (!file_exists($filePath)) {
            abort(404, 'Document not found.');
        }
        // Secure headers to prevent download
        return response()->stream(function () use ($filePath) {
            readfile($filePath);
        }, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="document.pdf"',
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
            'Content-Security-Policy' => "default-src 'self'; script-src 'self'",
        ]);
    }
    public function streamSecurePdf($id){
        $document = VoucherDocument::findOrFail($id);
        $path = storage_path('app/public/archive_data/' . $document->filepath . $document->document);

        if (!file_exists($path)) {
            abort(404);
        }

        return response()->file($path, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$document->document.'"',
        ]);
    }
}
