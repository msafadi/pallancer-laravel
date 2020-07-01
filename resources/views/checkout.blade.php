@extends('layouts.front')

@section('content')

<div class="ps-checkout pt-80 pb-80">
        <div class="ps-container">
          <form class="ps-checkout__form" action="{{ route('checkout') }}" method="post">
              @csrf
            <div class="row">
                  <div class="col-lg-8 col-md-8 col-sm-12 col-xs-12 ">
                    <div class="ps-checkout__billing">
                      <h3>Billing Detail</h3>
                            <div class="form-group form-group--inline">
                              <label>First Name<span>*</span>
                              </label>
                              <input class="form-control" type="text">
                            </div>
                            <div class="form-group form-group--inline">
                              <label>Last Name<span>*</span>
                              </label>
                              <input class="form-control" type="text">
                            </div>
                            <div class="form-group form-group--inline">
                              <label>Company Name<span>*</span>
                              </label>
                              <input class="form-control" type="text">
                            </div>
                            <div class="form-group form-group--inline">
                              <label>Email Address<span>*</span>
                              </label>
                              <input class="form-control" type="email">
                            </div>
                            <div class="form-group form-group--inline">
                              <label>Company Name<span>*</span>
                              </label>
                              <input class="form-control" type="text">
                            </div>
                            <div class="form-group form-group--inline">
                              <label>Phone<span>*</span>
                              </label>
                              <input class="form-control" type="text">
                            </div>
                            <div class="form-group form-group--inline">
                              <label>Address<span>*</span>
                              </label>
                              <input class="form-control" type="text">
                            </div>
                      <div class="form-group">
                        <div class="ps-checkbox">
                          <input class="form-control" type="checkbox" id="cb01">
                          <label for="cb01">Create an account?</label>
                        </div>
                      </div>
                      <h3 class="mt-40"> Addition information</h3>
                      <div class="form-group form-group--inline textarea">
                        <label>Order Notes</label>
                        <textarea class="form-control" rows="5" placeholder="Notes about your order, e.g. special notes for delivery."></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12 ">
                    <div class="ps-checkout__order">
                      <header>
                        <h3>Your Order</h3>
                      </header>
                      <div class="content">
                        <table class="table ps-checkout__products">
                          <thead>
                            <tr>
                              <th class="text-uppercase">Product</th>
                              <th class="text-uppercase">Total</th>
                            </tr>
                          </thead>
                          <tbody>
                              @php
                              $total = 0;
                              @endphp
                          @foreach ($cart as $item)
                            <tr>
                              <td>{{ $item->product->name }}</td>
                              <td>{{ $item->quantity * $item->price }}</td>
                            </tr>
                            @php
                              $total += $item->quantity * $item->price;
                              @endphp
                            @endforeach
                              <td>Order Total</td>
                              <td>{{ $total }}</td>
                            </tr>
                          </tbody>
                        </table>
                      </div>
                      <footer>
                        <h3>Payment Method</h3>
                        <div class="form-group cheque">
                          <div class="ps-radio">
                            <input class="form-control" type="radio" id="rdo01" name="payment" checked>
                            <label for="rdo01">Cheque Payment</label>
                            <p>Please send your cheque to Store Name, Store Street, Store Town, Store State / County, Store Postcode.</p>
                          </div>
                        </div>
                        <div class="form-group paypal">
                          <div class="ps-radio ps-radio--inline">
                            <input class="form-control" type="radio" name="payment" id="rdo02">
                            <label for="rdo02">Paypal</label>
                          </div>
                          <ul class="ps-payment-method">
                            <li><a href="#"><img src="images/payment/1.png" alt=""></a></li>
                            <li><a href="#"><img src="images/payment/2.png" alt=""></a></li>
                            <li><a href="#"><img src="images/payment/3.png" alt=""></a></li>
                          </ul>
                          <button type="submit" class="ps-btn ps-btn--fullwidth">Place Order<i class="ps-icon-next"></i></button>
                        </div>
                      </footer>
                    </div>
                    <div class="ps-shipping">
                      <h3>FREE SHIPPING</h3>
                      <p>YOUR ORDER QUALIFIES FOR FREE SHIPPING.<br> <a href="#"> Singup </a> for free shipping on every order, every time.</p>
                    </div>
                  </div>
            </div>
          </form>
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