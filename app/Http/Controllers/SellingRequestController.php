<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookSellingRequest;
use App\Notifications\SellingRequestMail;
use Illuminate\Http\Request;
use App\Image;
use App\SellingRequest;
use Intervention\Image\ImageManagerStatic as Photo;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Notification;

class SellingRequestController extends Controller
{
    public function index()
    {
        return view('public.sell-request');
    }
    public function store(BookSellingRequest $request)
    {
        $input = $request->all();
        $input['book_slug']=Str::slug($request->book_title).'-'.$request->seller_mobile;
        $input['request_id']=uniqid("BookReq-");

        if($file = $request->file('image_id'))
        {
            $name = time().$file->getClientOriginalName();

            $image_resize = Photo::make($file->getRealPath());
            $image_resize->resize(340,380);
            $image_resize->save(public_path('assets/img/' .$name));

            $image = Image::create(['file'=>$name]);
            $input['image_id'] = $image->id;
        }

        $create_request = SellingRequest::create($input);
        Notification::route('mail',$create_request->seller_email)->notify(new SellingRequestMail($create_request->request_id));
        return redirect('/sell-book')
            ->with('success_message', 'Selling Request is taken. We will contact with you soon via email.');
    }
}
