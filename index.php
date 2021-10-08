<?php 

include('vendor/stripe/stripe-php/init.php');

// Set your secret key. Remember to switch to your live secret key in production!
// See your keys here: https://dashboard.stripe.com/account/apikeys
\Stripe\Stripe::setApiKey('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');

$intent = \Stripe\PaymentIntent::create([
  'amount' => 1000,
  'currency' => 'inr',
  'metadata' => ['integration_check' => 'accept_a_payment'],
]);




// $stripe = [
//   "secret_key"      => "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",
//   "publishable_key" => "pk_live_51IklLkSG9ckFwleRSIwYnDKEogEppwehScADZc8wteS0NHqbBqf3qauoxJuZtqt3TckBGMOeV96qG70rDkwuhQnR00FOunEUrE",
// ];





?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Stripe Payment</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
	<script src="https://js.stripe.com/v3/"></script>
	<link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
	

<div class="container mt-5">
	<div class="row">
		<div class="col-4 offset-4">
			<div class="card">
				
				<div class="card-body">
					<h4 class="card-title">Stripe Payment</h4>

					<form id="payment-form">
						<div class="form-group">
							<label class="font-weight-bold">Full Name</label>
							<input type="text" name="fullname" class="form-control" placeholder="Full Name">
						</div>

                        <div class="form-group">
                        	<label class="font-weight-bold">Email</label>
                        	<input type="email" name="email" class="form-control" placeholder="Email">
                        </div>

                       <!--card--->
                       <div class="form-group">
                       	<label class="font-weight-bold">Card Details</label>
						<div id="card-element">
						<!-- Elements will create input elements here -->
						</div>

						<!-- We'll put the error messages in this element -->
						<div id="card-errors" role="alert"></div>

                       </div>
                       <!---card end-->



                        <div class="form-group">
                        	<button id="submit" class="btn btn-block btn-success">Pay Now</button>
                        </div>


					</form>
					
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	// Set your publishable key: remember to change this to your live publishable key in production
// See your keys here: https://dashboard.stripe.com/account/apikeys
var stripe = Stripe('pk_live_51IklLkSG9ckFwleRSIwYnDKEogEppwehScADZc8wteS0NHqbBqf3qauoxJuZtqt3TckBGMOeV96qG70rDkwuhQnR00FOunEUrE');
var elements = stripe.elements();

// Set up Stripe.js and Elements to use in checkout form
var style = {
  base: {
    color: "#32325d",
  }
};

var card = elements.create("card", { style: style });
card.mount("#card-element");


card.addEventListener('change', ({error}) => {
  const displayError = document.getElementById('card-errors');
  if (error) {
    displayError.textContent = error.message;
  } else {
    displayError.textContent = '';
  }
});

var form = document.getElementById('payment-form');

form.addEventListener('submit', function(ev) {
  ev.preventDefault();
  stripe.confirmCardPayment('<?= $intent->client_secret; ?>', {
    payment_method: {
      card: card,
      billing_details: {
        name: 'Aqib awan'
      }
    }
  }).then(function(result) {
    if (result.error) {
      // Show error to your customer (e.g., insufficient funds)
      console.log(result.error.message);
      $('#card-errors').text(result.error.message);
    } else {
      // The payment has been processed!
      if (result.paymentIntent.status === 'succeeded') {
        // Show a success message to your customer
        // There's a risk of the customer closing the window before callback
        // execution. Set up a webhook or plugin to listen for the
        // payment_intent.succeeded event that handles any business critical
        // post-payment actions.

           console.log(result);
               

        $('#card-errors').text('Payment Completed');

      }
    }
  });
});






</script>

</body>
</html>
