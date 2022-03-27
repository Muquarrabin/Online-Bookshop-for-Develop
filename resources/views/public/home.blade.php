@extends('layouts.master')

@section('content')
    <!-- Slider Area -->
    <section class="welcome-area">
        <div id="carouselExampleCaptions" class="carousel slide" data-ride="carousel">
            <ol class="carousel-indicators">
                <li data-target="#carouselExampleCaptions" data-slide-to="0" class="active"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="1"></li>
                <li data-target="#carouselExampleCaptions" data-slide-to="2"></li>
            </ol>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <div class="slider-img slider-bg-1"></div>
                    <div class="carousel-caption">
                        <h2>Welcome CTG Book Shop</h2>
                        <blockquote class="blockquote mb-4">
                            <p class="font-italic">"Books are the quietest and most constant of friends; they are the most accessible and wisest of counselors, and the most patient of teachers."</p>
                            <figcaption class="blockquote-footer">
                                Charles W. Eliot
                            </figcaption>
                        </blockquote>
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="slider-img slider-bg-2"></div>
                    <div class="carousel-caption">
                        <h2>Buy New Books</h2>
                        <blockquote class="blockquote mb-4">
                            <p class="font-italic">The library is inhabited by spirits that come out of the pages at night.</p>
                            <figcaption class="blockquote-footer">
                                Isabel Allende
                            </figcaption>
                        </blockquote>
                    
                    </div>
                </div>
                <div class="carousel-item">
                    <div class="slider-img slider-bg-3"></div>
                    <div class="carousel-caption">
                        <h2>Sell books & earn money!</h2>
                        <blockquote class="blockquote mb-4">
                            <p class="font-italic">A book is a version of the world. If you do not like it, ignore it; or offer your own version in return.</p>
                            <figcaption class="blockquote-footer">
                                Salman Rushdie
                            </figcaption>
                        </blockquote>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="main-content">
        <div class="container">
            <div class="row">
                <!-- Sidebar Content -->
                @include('layouts.includes.side-bar')
                <!-- //Sidebar End -->
                <div class="col-md-8">
                    <div class="content-area">
                        <div class="card my-4">
                            <div class="card-header bg-dark">
                                <h4><a href="{{route('category', 'educational')}}" class="text-white">Educational Books</a></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if(! $engineering_books->count())
                                       <div class="alert alert-warning">No books availble</div>
                                    @else
                                        @foreach($engineering_books as $book)
                                            <div class="col-lg-3 col-6">
                                                <div class="book-wrap">
                                                    <div class="book-image mb-2">
                                                        <a href="{{route('book-details', $book->id)}}"><img src="{{$book->image_url}}" alt=""></a>
                                                        @if($book->discount_rate)
                                                            <h6><span class="badge badge-warning discount-tag">Discount {{$book->discount_rate}}%</span></h6>
                                                        @endif
                                                    </div>
                                                    <div class="book-title mb-2">
                                                        <a href="{{route('book-details', $book->id)}}">{{str_limit($book->title, 30)}}</a>
                                                    </div>
                                                    <div class="book-title mb-2">
                                                        @if ($book->is_second_hand)
                                                            Condition: 2nd Hand
                                                        @else
                                                            Condition: New
                                                        @endif
                                                    </div>
                                                    <div class="book-author mb-2">
                                                        <small>By <a href="{{route('author', $book->author->slug)}}">{{$book->author->name}}</a></small>
                                                    </div>
                                                    <div class="pbook-price mb-3">
                                                        @if($book->discount_rate)
                                                            <span class="line-through mr-3">{{$book->init_price}} TK</span>
                                                        @endif
                                                        <span class=""><strong>{{$book->price}} TK</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="show-more pt-2 text-right">
                                    <a href="{{route('category', 'educational')}}" class="text-secondary">See More <i class="fas fa-angle-double-right"></i></a>
                                </div>
                            </div>
                        </div>
                        <div class="card my-4">
                            <div class="card-header bg-dark">
                                <h4><a href="{{route('category', 'fiction')}}" class="text-white">Literature Books</a></h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    @if(! $literature_books->count())
                                        <div class="alert alert-warning">No books availble</div>
                                    @else
                                        @foreach($literature_books as $book)
                                            <div class="col-lg-3 col-6">
                                                <div class="book-wrap">
                                                    <div class="book-image mb-2">
                                                        <a href="{{route('book-details', $book->id)}}"><img src="{{$book->image_url}}" alt=""></a>
                                                        @if($book->discount_rate)
                                                            <h6><span class="badge badge-warning discount-tag">Discount {{$book->discount_rate}}%</span></h6>
                                                        @endif
                                                    </div>
                                                    <div class="book-title mb-2">
                                                        <a href="{{route('book-details', $book->id)}}">{{str_limit($book->title, 30)}}</a>
                                                    </div>
                                                    <div class="book-title mb-2">
                                                        @if ($book->is_second_hand)
                                                            Condition: 2nd Hand
                                                        @else
                                                            Condition: New
                                                        @endif
                                                    </div>
                                                    <div class="book-author mb-2">
                                                        <small>By <a href="{{route('author', $book->author->slug)}}">{{$book->author->name}}</a></small>
                                                    </div>
                                                    <div class="pbook-price mb-3">
                                                        @if($book->discount_rate)
                                                            <span class="line-through mr-3">{{$book->init_price}} TK</span>
                                                        @endif
                                                        <span class=""><strong>{{$book->price}} TK</strong></span>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    @endif
                                </div>
                                <div class="show-more pt-2 text-right">
                                    <a href="{{route('category', 'fiction')}}" class="text-secondary">See More <i class="fas fa-angle-double-right"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="discount-book mb-5" id="discount-books">
        <div class="container">
            <div class="card mb-10">
                <div class="card-header bg-dark text-white">
                    <h4><a href="{{route('discount-books')}}" class="text-white">Discount's Book</a></h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @if(! $discount_books->count())
                            <div class="alert alert-warning">No books available</div>
                        @else
                            @foreach($discount_books as $book)
                                <div class="col-lg-2 col-6">
                                    <div class="book-wrap">
                                        <div class="book-image mb-2">
                                            <a href="{{route('book-details', $book->id)}}"><img src="{{$book->image_url}}" alt=""></a>
                                            @if($book->discount_rate)
                                                <h6><span class="badge badge-warning discount-tag">Discount {{$book->discount_rate}}%</span></h6>
                                            @endif
                                        </div>
                                        <div class="book-title mb-2">
                                            <a href="{{route('book-details', $book->id)}}">{{str_limit($book->title, 30)}}</a>
                                        </div>
                                        <div class="book-title mb-2">
                                            @if ($book->is_second_hand)
                                                Condition: 2nd Hand
                                            @else
                                                Condition: New
                                            @endif
                                        </div>
                                        <div class="book-author mb-2">
                                            <small>By <a href="{{route('author', $book->author->slug)}}">{{$book->author->name}}</a></small>
                                        </div>
                                        <div class="pbook-price mb-3">
                                            @if($book->discount_rate)
                                                <span class="line-through mr-3">{{$book->init_price}} TK</span>
                                            @endif
                                            <span class=""><strong>{{$book->price}} TK</strong></span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @endif
                    </div>
                    <div class="show-more pt-2 text-right">
                        <a href="{{route('discount-books')}}" class="text-secondary">See More <i class="fas fa-angle-double-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
