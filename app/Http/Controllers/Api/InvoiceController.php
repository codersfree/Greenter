<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\SunatService;
use Greenter\Report\XmlUtils;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    public function send(Request $request)
    {

        $company = Company::where('user_id', auth()->id())->firstOrFail();

        $sunat = new SunatService;
        $see = $sunat->getSee($company);

        $invoice = $sunat->getInvoice();

        $result = $see->send($invoice);

        $response['xml'] = $see->getFactory()->getLastXml();
        $response['hash'] = (new XmlUtils())->getHashSign($response['xml']);
        $response['sunatResponse'] = $sunat->sunatResponse($result);

        return $response;
    }
}