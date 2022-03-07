@extends('layouts.admin-master')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Second Hand Accounts</h1>
        <div class="my-2 px-1">
            <div class="row">
                {{-- <div class="col-6">
                    <div>
                        <a href="{{route('books.create')}}" class="btn-primary btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i>
                            Add Book
                        </a>
                    </div>
                </div> --}}
                {{-- <div class="col-6 text-right">
                    <span class="mr-2"><a href="{{route('books.index')}}">All books</a> |</span>
                    <span class="mr-2"><a href="{{route('admin.discountBooks')}}">Discount books</a> |</span>
                    <span class="mr-2"><a href="{{route('admin.trash-books')}}">Trash books</a></span>
                </div> --}}
            </div>
        </div>

        {{-- @if (isset($discount_books))
            <div class="alert alert-primary"><strong>{{$discount_books}}</strong></div>
        @endif --}}
        @include('layouts.includes.flash-message')
        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">All Earned Data from Second Hand Books</h6>
            </div>
            <div class="card-body">
                @if ($second_hand_sales->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Request Id</th>
                                    <th>Order Id</th>
                                    <th>Seller Name</th>
                                    <th>Selling Price</th>
                                    <th>Seller Asking Price</th>
                                    <th>Discount</th>
                                    <th>Commission Earned</th>
                                    <th>Payment Staus</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Request Id</th>
                                    <th>Order Id</th>
                                    <th>Seller Name</th>
                                    <th>Selling Price</th>
                                    <th>Seller Asking Price</th>
                                    <th>Discount</th>
                                    <th>Commission Earned</th>
                                    <th>Payment Staus</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($second_hand_sales as $sale)
                                    <tr>
                                        <td>{{ $sale->selling_request->request_id }}</td>
                                        <td>{{ $sale->order->id }}</td>
                                        <td>{{ $sale->selling_request->seller_name }}</td>
                                        <td>{{ $sale->selling_price }}</td>
                                        <td>{{ $sale->asking_price }}</td>
                                        <td>{{ $sale->discount }}</td>
                                        <td>{{ $sale->commission_earned }}</td>
                                        @if ($sale->payment_status)
                                            <td>
                                                <span class="badge badge-success">Paid</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('second-hand-account.unpay', $sale->id) }}"
                                                    class="btn-danger btn btn-sm mr-2"><i class="fas fa-times-circle"></i>
                                                    Set as Unpaid</a>
                                            </td>
                                        @else
                                            <td>
                                                <span class="badge badge-warning">Not Paid</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('second-hand-account.pay', $sale->id) }}"
                                                    class="btn-success btn btn-sm mr-2"><i class="fas fa-check-circle"></i>
                                                    Set as Paid</a>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

    </div>
@endsection
