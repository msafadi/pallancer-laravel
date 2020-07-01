@extends('layouts.front')

@section('content')

@if(session()->has('success'))
<div class="alert alert-success">
    {{ session()->get('success') }}
</div>
@endif
<div class="ps-content pt-80 pb-80">
        <div class="ps-container">
          <div class="ps-cart-listing">
            <table class="table ps-cart__table">
              <thead>
                <tr>
                  <th>All Products</th>
                  <th>Price</th>
                  <th>Quantity</th>
                  <th>Total</th>
                  <th></th>
                </tr>
              </thead>
              <tbody>
                  @php
                    $total = 0;
                  @endphp  
                  @foreach ($cart as $item)
                <tr>
                  <td><a class="ps-product__preview" href="{{ route('products.show', [$item->product_id]) }}"><img class="mr-15" height="60" src="{{ asset('images/' . $item->product->image) }}" alt=""> {{ $item->product->name }}</a></td>
                  <td>{{ $item->price }}</td>
                  <td>
                    <div class="form-group--number">
                      <button class="minus"><span>-</span></button>
                      <input class="form-control" type="text" value="{{ $item->quantity }}">
                      <button class="plus"><span>+</span></button>
                    </div>
                  </td>
                  <td>{{ $item->price * $item->quantity }}</td>
                  <td>
                    <div class="ps-remove"></div>
                  </td>
                </tr>
                @php
                    $total += $item->price * $item->quantity;
                @endphp
                @endforeach
              </tbody>
            </table>
            <div class="ps-cart__actions">
              <div class="ps-cart__promotion">
                <div class="form-group">
                  <div class="ps-form--icon"><i class="fa fa-angle-right"></i>
                    <input class="form-control" type="text" placeholder="Promo Code">
                  </div>
                </div>
                <div class="form-group">
                  <button class="ps-btn ps-btn--gray">Continue Shopping</button>
                </div>
              </div>
              <div class="ps-cart__total">
                <h3>Total Price: <span> {{ $total }}</span></h3><a class="ps-btn" href="{{ route('checkout') }}">Process to checkout<i class="ps-icon-next"></i></a>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="ps-subscribe">
        <div class="ps-container">
          <div class="row">
                <div class="col-lg-3 col-md-12 col-sm-12 col-xs-12 ">
                  <h3><i class="fa fa-envelope"></i>Sign up to Newsletter</h3>
                </div>
                <div class="col-lg-5 col-md-7 col-sm-12 col-xs-12 ">
                  <form class="ps-subscribe__form" action="do_action" method="post">
                    <input class="form-control" type="text" placeholder="">
                    <button>Sign up now</button>
                  </form>
                </div>
                <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 ">
                  <p>...and receive  <span>$20</span>  coupon for first shopping.</p>
                </div>
          </div>
        </div>
      </div>

@endsection