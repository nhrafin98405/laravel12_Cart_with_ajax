@extends('layout')



@section('content')

<div class="row">

    <div class="col-md-8 offset-md-2">

        <div class="card mt-5">

            <div class="card-header">
                <h3>Stripe Payment</h3>
            </div>

            <div class="card-body">

                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <h4 class="mb-3">
                    Total Amount :
                    <strong>${{ number_format($total, 2) }}</strong>
                </h4>

                <form id="checkout-form"
                      method="POST"
                      action="{{ route('stripe.post') }}">

                    @csrf

                    <div class="mb-3">

                        <label class="form-label">
                            Name
                        </label>

                        <input
                            type="text"
                            class="form-control"
                            name="name"
                            placeholder="Enter Your Name"
                            required>

                    </div>

                    <input
                        type="hidden"
                        name="stripeToken"
                        id="stripe-token-id">

                    <div
                        id="card-element"
                        class="form-control">
                    </div>

                    <button
                        id="pay-btn"
                        type="button"
                        class="btn btn-success mt-4 w-100"
                        onclick="createToken()">

                        Pay ${{ number_format($total,2) }}

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<script src="https://js.stripe.com/v3/"></script>

<script>

var stripe = Stripe('{{ env("STRIPE_KEY") }}');

var elements = stripe.elements();

var cardElement = elements.create('card');

cardElement.mount('#card-element');

function createToken(){

    document.getElementById('pay-btn').disabled = true;

    stripe.createToken(cardElement).then(function(result){

        if(result.error){

            document.getElementById('pay-btn').disabled = false;

            alert(result.error.message);

            return;
        }

        document.getElementById('stripe-token-id').value = result.token.id;

        document.getElementById('checkout-form').submit();

    });

}

</script>

@endsection
