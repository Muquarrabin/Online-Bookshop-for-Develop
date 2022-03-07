<?php

namespace App\Http\Controllers\Admin;

use App\Book;
use App\Order;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SecondHandAccount;

class AdminBaseController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    public function index()
    {
        $users = User::all();
        $books_quantity = Book::sum('quantity');
        $old_books_quantity = Book::where('is_second_hand','=',1)->sum('quantity');
        $total_earning = Order::where('order_status', 1)->sum('total_price');
        $pending_orders = Order::where('order_status', 0)->get();
        $commission_earning = SecondHandAccount::sum('commission_earned');
        return view('admin.dashboard', compact('users', 'books_quantity', 'total_earning',
                                     'pending_orders','commission_earning','old_books_quantity'));
    }

}
