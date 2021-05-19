<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>TSF CHARITY | DONATION</title>
<link href="../Sparks Project/img/1.jpg" rel="icon">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<style>
body{
 margin:0;
 background: radial-gradient(#DBF3FA,#74D7F0);
}
input[type="submit"]
{
  margin-left:13%;
	border-radius:30px;
	border: solid 8px blue;
	background-color:transparent;
	padding:20px;
	width:10%;
	height:10%;
	cursor:pointer;
  margin-left:45%;
  margin-top:25%;
}
@media screen and (max-width:767px){
	input[type="submit"]{
	margin-left:25%;
  margin-top:20%;
	border-radius:30px;
	border: solid 8px blue;
	background-color:transparent;
	padding:20px;
	width:45%;
	height:10%;
	cursor:pointer;
   }
}
@media screen and (min-width:768px) and (max-width:1023px){
	input[type="submit"]{
	margin-left:20%;
  margin-top:20%;
	border-radius:30px;
	border: solid 8px blue;
	background-color:transparent;
	padding:20px;
	width:18%;
	height:10%;
	cursor:pointer;
   }
}
@media screen and (min-width:1024px) and (max-width:1439px){
	input[type="submit"]{
	margin-left:20%;
  margin-top:20%;
	border-radius:30px;
	border: solid 8px blue;
	background-color:transparent;
	padding:20px;
	width:18%;
	height:10%;
	cursor:pointer;
   }
}
input[type="submit"]:hover{
	background-color:blue;
  color:white;
}
h1{
  margin-left:25%;
  margin-top:5%;
}
</style>
<body>
  <h1>Thank You For Your Contribution Towards Saving A Life</h1>
<?php

require('config.php');
require('razorpay-php/Razorpay.php');
session_start();



use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$price = $_POST['price'];
$name = $_POST['name'];
$email = $_POST['email'];
$add = $_POST['address'];
$mobile = $_POST['mobile'];
$orderData = [
    'receipt'         => 3456,
    'amount'          => $price* 100,
    'currency'        => 'INR',
    'payment_capture' => 1
];

$razorpayOrder = $api->order->create($orderData);

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => "TSFCharity",
    "description"       => "Pay For Charity",
    "image"             => "../Sparks Project/img/download.png",
    "prefill"           => [
    "name"              => $name,
    "email"             => $email,
    "contact"           => $mobile,
    ],
    "notes"             => [
    "address"           => $add,
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);
?>
<form action="verify.php" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-prefill.name="<?php echo $data['prefill']['name']?>"
    data-prefill.email="<?php echo $data['prefill']['email']?>"
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="3456"
    data-order_id="<?php echo $data['order_id']?>"
    <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount']?>" <?php } ?>
    <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency']?>" <?php } ?>
  >
  </script>

  <input type="hidden" name="shopping_order_id" value="3456">
</form>
</body>
</html>
