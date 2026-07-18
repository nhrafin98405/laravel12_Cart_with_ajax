@extends('layout')

   

@section('content')

    

<div class="row">

    <div class="col-md-8 offset-md-2">
            <div class="card mt-5">
                <h3 class="card-header p-3">Laravel 11 Stripe Payment Gateway Integration Example - ItSolutionStuff.com</h3>
                <div class="card-body">

                    @session('success')
                        <div class="alert alert-success" role="alert"> 
                            {{ $value }}
                        </div>
                    @endsession
          
                    <form id='checkout-form' method='post' action="{{ route('stripe.post') }}">   
                        @csrf    

                        <strong>Name:</strong>
                        <input type="input" class="form-control" name="name" placeholder="Enter Name">

                        <input type='hidden' name='stripeToken' id='stripe-token-id'>                              
                        <br>
                        <div id="card-element" class="form-control" ></div>
                        <button 
                            id='pay-btn'
                            class="btn btn-success mt-3"
                            type="button"
                            style="margin-top: 20px; width: 100%;padding: 7px;"
                            onclick="createToken()">{{$total}}
                        </button>
                    <form>
                </div>
            </div>
        </div>

    <p>

        @php
            $total = 0;
        @endphp

        @foreach (session('cart') as $item )

        @php
            $total +=  $item['price']
        @endphp
            
        @endforeach

        <h1 class="d">total : {{$total}}</h1>

    </p>

    <a href="{{route('order')}}" class=" btn btn-success">order</a>

    
</div>

    

@endsection