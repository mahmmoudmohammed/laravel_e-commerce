@extends('layouts.app')

@section('content')
    <section class="section-content bg padding-y">
        <div class="container">
            <div class="row">
                <main class="col-sm-9">
                    @if(Session::has('message'))
                        <div class="alert alert-success">
                            {{session('message')}}
                        </div>
                    @else
                        <div class="card">
                            <table class="table table-hover shopping-cart-wrap">
                                <thead class="text-muted">
                                <tr>
                                    <th scope="col">Product</th>
                                    <th scope="col" width="120">Quantity</th>
                                    <th scope="col" width="120">Price</th>
                                    <th scope="col" class="text-right" width="200">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($Products as $items)
                                    @if(is_array($items))
                                            <tr>
                                            <td>
                                                <figure class="media">
                                                    <figcaption class="media-body">
                                                        <h6 class="title text-truncate">{{ $items['name']}}</h6>
                                                    </figcaption>
                                                </figure>
                                            </td>
                                            <td>
                                                <var class="price">{{ $items['quantity'] }}</var>
                                            </td>
                                            <td>
                                                <div class="price-wrap">
                                                    <var class="price">{{ config('settings.currency_symbol'). $items['price'] }}</var>
                                                    <small class="text-muted">each</small>
                                                </div>
                                            </td>
                                            <td class="text-right">
                                                <a href="{{route('delete_cart_product',$items['id'])}}" class="btn btn-outline-danger">delete </a>
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </main>
                <aside class="col-sm-3">
                    <a href="{{route('delete_cart')}}" class="btn btn-danger btn-block mb-4">Clear Cart</a>
                    <dl class="dlist-align h4">
                        <dt>Total:</dt>
                        <dd class="text-right"><strong>{{isset($total)?$total:''}}</strong></dd>
                    </dl>
                    <hr>
                    <a href="{{route('checkout')}}" class="btn btn-success btn-lg btn-block">Proceed To Checkout</a>
                </aside>
            </div>
        </div>
    </section>
@endsection
