@extends('layouts.admin-master')

@section('content')
    <div class="container-fluid">

        <!-- Page Heading -->
        <h1 class="h3 mb-2 text-gray-800">Add new shipping charge</h1>

        <!-- DataTales Example -->
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Shipping charge create form</h6>
            </div>
            <div class="card-body">
                {!! Form::open(['method'=>'POST', 'action'=>'Admin\AdminShippingChargesController@store']) !!}
                <div class="form-group">
                   {!! Form::label('area_name') !!}
                   {!! Form::text('area_name', null, ['class'=>'form-control '.($errors->has('area_name')? 'is-invalid': '')]) !!}
                    @if($errors->has('area_name'))
                        <span class="invalid-feedback">
                            <strong>{{$errors->first('area_name')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                    {!! Form::label('amount') !!}
                    {!! Form::text('amount', null, ['class'=>'form-control '.($errors->has('amount')? 'is-invalid':'')]) !!}

                    @if($errors->has('amount'))
                        <span class="invalid-feedback">
                            <strong>{{$errors->first('amount')}}</strong>
                        </span>
                    @endif
                </div>
                <div class="form-group">
                   {!! Form::submit('Create', ['class'=>'btn btn-primary']) !!}
                   {!! Form::reset('Reset', ['class'=>'btn btn-danger']) !!}
                </div>
                {!! Form::close() !!}
            </div>
        </div>

    </div>
@endsection
