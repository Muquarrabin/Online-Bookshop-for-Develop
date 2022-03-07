@extends('layouts.admin-master')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Selling Requests</h1>
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
                <h6 class="m-0 font-weight-bold text-primary">All Selling Requests</h6>
            </div>
            <div class="card-body">
                @if ($selling_requests->count())
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Image</th>
                                    <th>Request Id</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Seller Name</th>
                                    <th>Seller Mobile</th>
                                    <th>Seller Email</th>
                                    <th>Seller Address</th>
                                    <th>Commission</th>
                                    <th>Selling Price</th>
                                    <th>Asking Price</th>
                                    <th>Staus</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>Image</th>
                                    <th>Request Id</th>
                                    <th>Title</th>
                                    <th>Category</th>
                                    <th>Author</th>
                                    <th>Seller Name</th>
                                    <th>Seller Mobile</th>
                                    <th>Seller Email</th>
                                    <th>Seller Address</th>
                                    <th>Commission</th>
                                    <th>Selling Price</th>
                                    <th>Asking Price</th>
                                    <th>Staus</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                                @foreach ($selling_requests as $selling_request)
                                    <tr>
                                        <td><img src="{{ $selling_request->image_url }}" width="60" height="70" alt="">
                                        </td>
                                        <td>{{ $selling_request->request_id }}</td>
                                        <td>{{ $selling_request->book_title }}</td>
                                        <td>{{ $selling_request->category->name }}</td>
                                        <td>{{ $selling_request->author->name }}</td>
                                        <td>{{ $selling_request->seller_name }}</td>
                                        <td>{{ $selling_request->seller_mobile }}</td>
                                        <td>{{ $selling_request->seller_email }}</td>
                                        <td>{{ $selling_request->seller_address }}</td>
                                        <td>{{ $selling_request->commission }}%</td>
                                        <td>{{ $selling_request->selling_price }}</td>
                                        <td>{{ $selling_request->asking_price }}</td>
                                        @if ($selling_request->status)
                                            <td>
                                                <span class="badge badge-success">Accepted</span>
                                            </td>
                                            <td></td>
                                        @else
                                            <td>
                                                <span class="badge badge-warning">Pending</span>
                                            </td>
                                            <td>
                                                <a href="{{ route('selling-requests.accept', $selling_request->id) }}"
                                                    class="btn-success btn btn-sm mr-2"><i class="fas fa-check-circle"></i>
                                                    Accept</a>
                                                <a href="{{ route('selling-requests.reject', $selling_request->id) }}"
                                                    class="btn-danger btn btn-sm mr-2"><i class="fas fa-times-circle"></i>
                                                    Reject</a>
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
