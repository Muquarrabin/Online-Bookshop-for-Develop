<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SecondHandAccount;
use Illuminate\Support\Facades\DB;
class AdminSecondHandAccController extends Controller
{
    public function index()
    {
        $second_hand_sales = SecondHandAccount::with('order', 'selling_request')
            ->orderBy('id', 'DESC')
            ->get();
        return view('admin.accounts.second_hand.index', compact('second_hand_sales'));
    }

    public function paymentStatusPaid(Request $request)
    {
        DB::beginTransaction();
        try {
            $sale = SecondHandAccount::find($request->id);
            $sale->payment_status=true;
            $sale->save();
            DB::commit();
            return redirect()->back()
                ->with('success_message', 'Amount paid to seller.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()
                ->with('alert_message', 'Error:'.$th->getMessage());
            //throw $th;
        }

    }
    public function paymentStatusUnpaid(Request $request)
    {
        $sale = SecondHandAccount::find($request->id);
        $sale->payment_status=false;
        $sale->save();
        return redirect()->back()
            ->with('alert_message', 'Payment status set not paid!');
    }
}
