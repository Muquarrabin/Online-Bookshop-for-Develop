@extends('layouts.admin-master')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Shipping Charges</h1>
        <div class="my-2 px-1">
            <div class="row">
                <div class="col-6">
                    <div>
                        <a href="{{route('shipping-charges.create')}}" class="btn-primary btn-sm">
                            <i class="fas fa-plus-circle mr-1"></i>
                            Add Charges
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{--Flash Message--}}
        @include('layouts.includes.flash-message')

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <span class="m-0 font-weight-bold text-primary">Shipping Charges List</span>
            </div>
            <div class="card-body">
                @if($shipping_charges->count())
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                            <tr>
                                <th>Action</th>
                                <th>Area Name</th>
                                <th>Amount</th>
                            </tr>
                            </thead>
                            <tfoot>
                            <tr>
                                <th>Action</th>
                                <th>Area Name</th>
                                <th>Amount</th>
                            </tr>
                            </tfoot>
                            <tbody>
                            @foreach($shipping_charges as $charge)
                                <tr>
                                    <td>
                                        {!! Form::open(['method'=>'DELETE', 'action'=>['Admin\AdminShippingChargesController@destroy', $charge->id]]) !!}

                                        <a href="{{route('shipping-charges.edit', $charge->id)}}" class="btn btn-sm btn-primary"><i class="fas fa-edit"></i></a>

                                        <button type="submit" onclick="return confirm('Shipping Charges will delete permanently.  Are you sure to delete??')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>

                                        {!! Form::close() !!}
                                    </td>
                                    <td><a href="{{route('shipping-charges.edit', $charge->id)}}">{{$charge->area_name}}</a></td>
                                    <td>{{$charge->amount}}</td>
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
