<?php

namespace App\Http\Controllers\Admin;

use App\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SellingRequest;
use Illuminate\Support\Facades\DB;

class AdminSellingRequestsController extends Controller
{
    public function index()
    {
        $selling_requests = SellingRequest::with('category', 'author', 'image')
            ->orderBy('id', 'DESC')
            ->get();
        return view('admin.selling_requests.index', compact('selling_requests'));
    }
    public function acceptRequest(Request $request)
    {
        DB::beginTransaction();
        try {
            $selling_request = SellingRequest::find($request->id);
            $selling_request->status=true;
            $selling_request->save();
            $book=new Book();
            $book->title=$selling_request->book_title;
            $book->description=$selling_request->book_description;
            $book->slug=$selling_request->book_slug;
            $book->author_id=$selling_request->author_id;
            $book->category_id=$selling_request->category_id;
            $book->image_id=$selling_request->image_id;
            $book->init_price=$selling_request->selling_price;
            $book->price=$selling_request->selling_price;
            $book->discount_rate=0;
            $book->quantity=1;
            $book->price=$selling_request->selling_price;
            $book->is_second_hand=true;
            $book->selling_request_id=$selling_request->id;
            $book->save();
            DB::commit();
            return redirect()->back()
                ->with('success_message', 'Selling Request Accepted! An email sent to seller email.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()
                ->with('alert_message', 'Error:'.$th->getMessage());
            //throw $th;
        }

    }
    public function rejectRequest(Request $request)
    {
        $selling_request = SellingRequest::find($request->id);
        $selling_request->delete();
        return redirect()->back()
            ->with('alert_message', 'Selling Request Rejected! An email sent to seller email.');
    }
}
