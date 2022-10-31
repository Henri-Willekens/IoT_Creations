@extends('layouts.app')
@section('title', $viewData["title"])
@section('subtitle', $viewData["subtitle"])
@section('content')
    @include('layouts.pages')
    <div class="row">
        @foreach ($products as $product)
            <div class="col-md-4 col-lg-3 mb-2">
                <a href="{{ route('product.show', ['id'=> $product->getId()]) }}" class="card pb">
                    <img src="{{ asset('/storage/'.$product->getImage()) }}" class="card-img-top img-card">
                    <div class="card-body text-center pb-0">
                        <p class="btn bg-primary text-white">{{ $product->getName() }} - {{ $product->getPrice() / 100 }}$</p>
                    </div>
                    </a>
                </div>
                @endforeach
            </div>
@endsection
