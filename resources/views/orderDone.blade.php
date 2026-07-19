@extends('layout')

   

@section('content')

<div class="row justify-content-center mt-5">

    <div class="col-md-8">

        <div class="card shadow text-center">

            <div class="card-body p-5">

                <h1 class="text-success mb-4">
                    Payment Successful
                </h1>

                <p class="lead">
                    Your order has been placed successfully.
                </p>

                <p>
                    Thank you for shopping with us.
                </p>

                <a href="{{ url('/') }}"
                   class="btn btn-primary mt-3">

                    Continue Shopping

                </a>

            </div>
        </div>

    </div>

</div>

@endsection