<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MpesaController extends Controller
{
    public function callback(Request $request)
    {
        $data = $request->all();

        if ($data['Body']['stkCallback']['ResultCode'] === 0) {
            // Payment successful
            $transaction = $data['Body']['stkCallback']['CallbackMetadata']['Item'];
            $phone = collect($transaction)->where('Name', 'PhoneNumber')->first()['Value'];
            $amount = collect($transaction)->where('Name', 'Amount')->first()['Value'];

            // Save transaction and send email
            // Notify user of success
        } else {
            // Handle payment failure
        }

        return response()->json(['status' => 'success']);
    }

}
