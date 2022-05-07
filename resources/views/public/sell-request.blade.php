@extends('layouts.master')
@section('title')
    CTG Book Shop - Sell Request
@endsection
@section('content')
    <section class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-md-8">
                    <div class="content-area">

                        <div class="card my-4">
                            <div class="card-header bg-dark">
                                <h4 class="text-white">Selling Request</h4>
                            </div>

                            <div class="card-body">
                                @include('layouts.includes.flash-message')
                                <div class="row">
                                    {!! Form::open(['method' => 'POST', 'action' => 'SellingRequestController@store', 'files' => true]) !!}

                                    <div class="form-group">
                                        {!! Form::label('book_title', 'Book Title') !!}
                                        {!! Form::text('book_title', null, ['class' => 'form-control ' . ($errors->has('book_title') ? 'is-invalid' : '')]) !!}
                                        @if ($errors->has('book_title'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('book_title') }}</strong>
                                            </span>
                                        @endif
                                    </div>


                                    <div class="form-group">
                                        {!! Form::label('book_description', 'Book Description') !!}
                                        {!! Form::textarea('book_description', null, ['class' => 'form-control ' . ($errors->has('book_description') ? 'is-invalid' : ''), 'rows' => 10]) !!}
                                        @if ($errors->has('book_description'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('book_description') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('author_id', 'Author') !!}
                                        {!! Form::select('author_id', App\Author::pluck('name', 'id'), null, ['placeholder' => 'Select author', 'class' => 'form-control ' . ($errors->has('author_id') ? 'is-invalid' : '')]) !!}
                                        @if ($errors->has('author_id'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('author_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('category_id', 'Category') !!}
                                        {!! Form::select('category_id', App\Category::pluck('name', 'id'), null, ['placeholder' => 'Select category', 'class' => 'form-control ' . ($errors->has('category_id') ? 'is-invalid' : '')]) !!}
                                        @if ($errors->has('category_id'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('category_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('asking_price', 'Asking Price') !!}
                                        {!! Form::text('asking_price', null, ['class' => 'form-control ' . ($errors->has('asking_price') ? 'is-invalid' : '')]) !!}
                                        @if ($errors->has('asking_price'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('asking_price') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('commission', 'Commission Rate: 4%') !!}
                                        <input id="commission" type="hidden" name="commission" value="4">
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('selling_price', 'Selling Price (This will show as the price)') !!}
                                        {!! Form::text('selling_price', null, ['class' => 'form-control ' . ($errors->has('selling_price') ? 'is-invalid' : ''), 'readonly'=>true]) !!}
                                        @if ($errors->has('selling_price'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('selling_price') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {!! Form::label('image_id', 'Book Image') !!}
                                        {!! Form::file('image_id', ['class' => 'form-control ' . ($errors->has('image_id') ? 'is-invalid' : '')]) !!}
                                        <small>Max size 1MB</small>
                                        @if ($errors->has('image_id'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('image_id') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('seller_name', 'Seller Name') !!}
                                        {!! Form::text('seller_name', null, ['class' => 'form-control ' . ($errors->has('seller_name') ? 'is-invalid' : '')]) !!}
                                        @if ($errors->has('seller_name'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('seller_name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('seller_mobile', 'Seller Mobile') !!}
                                        {!! Form::text('seller_mobile', null, ['class' => 'form-control ' . ($errors->has('seller_mobile') ? 'is-invalid' : '')]) !!}
                                        @if ($errors->has('seller_mobile'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('seller_mobile') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('seller_email', 'Seller Email') !!}
                                        {!! Form::text('seller_email', null, ['class' => 'form-control ' . ($errors->has('seller_email') ? 'is-invalid' : '')]) !!}
                                        @if ($errors->has('seller_email'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('seller_email') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        {!! Form::label('seller_address') !!}
                                        {!! Form::textarea('seller_address', null, ['class' => 'form-control ' . ($errors->has('seller_address') ? 'is-invalid' : ''), 'rows' => 10]) !!}
                                        @if ($errors->has('seller_address'))
                                            <span class="invalid-feedback">
                                                <strong>{{ $errors->first('seller_address') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                        {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                                    </div>
                                    {!! Form::close() !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar -->
                {{-- @include('layouts.includes.side-bar') --}}
                <!-- Sidebar end -->
            </div>
        </div>
    </section>
@endsection
@section('script')
    <script type="text/javascript">
        /*
         making slug automatically
        */
        $('#asking_price').on('blur', function() {
            var askingPrice = parseFloat(this.value);
            var sellingPriceInput = $('#selling_price');
            var commission=$('#commission').val()/100;
            var sellingPrice = askingPrice*commission+askingPrice;

            sellingPriceInput.val(sellingPrice);
        });
    </script>
@endsection
