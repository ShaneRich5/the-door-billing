<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Order Invoice {{ $invoice_number }}</title>
    <style>
		* {
			margin: 0;
			padding: 0;
		}
		body {
			padding: 48px;
			font-family: sans-serif;
		}
		.column.left {
			width: 400px;
			float: left;
		}
		.column.right {
			width: 200px;
			float: right;
		}
		.row::after {
			content: "";
			clear: both;
			display: table;
		}
		.quarter {
			width: 24%;
			float: left;
		}
		.half {
			width: 48%;
			float: left;
		}
    </style>
</head>
<body>
	<div class="row" id="heading-details">
		<div class="column left">
			<h1>Invoice</h1>
			<h3>The Door Restaurant</h3>
			<p>163-07 Baisley Blvd.<br>Jamaica, NY 11434<br><b>(718) 525-1083</b></p>
		</div>
		<div class="column right">
			<img class="logo" src="http://thedoorrestaurant.nyc/wp-content/uploads/revslider/slider-home/logosmall2.png"/>
		</div>
	</div>
	<div class="row">
		<div class="quarter">
			<h2>Billed To</h2>
			<p>{{ $user['first_name'] }} {{ $user['last_name']  }}<br>
			{{ $billing_address['street'] }}<br>
			{{ $billing_address['city'] }}, {{ $billing_address['state'] }}<br>
			<b>{{ $user['phone'] }}</b></p>
		</div>
		<div class="quarter">
			<h2>Deliver To</h2>
			<p>{{ $delivery_location['owner'] }}<br>
			{{ $delivery_location['name'] }}<br>
			{{ $delivery_location['phone']}}<br>
			{{ $delivery_address['street'] }}<br>
			{{ $delivery_address['city'] }}, {{ $delivery_address['state'] }}</p>
		</div>
		<div class="quarter">
			<h2>Invoice #</h2>
			<h2>Serving</h2>
			<h2>Deliver By</h2>
		</div>
		<div class="half">
			<p>{{ $order->id }}</p>
			<p>{{ $delivery['attendance'] }} people</p>
			<p class="half">{{ $delivery['delivery_by'] }}</p>
		</div>
	</div>
	<div class="row">
		<div class="half">
			<h2>Menu Items Inclued</h2>
			<table>
				<tr>
					<th>Name</th>
				</tr>
				@foreach($menu_items as $menu_item)
				<tr>
					<td>{{ $menu_item['name'] }}</td>
				</tr>
				@endforeach
			</table>
		</div>
		<div class="half">
			<h3>Payment Details</h3>
			<table>
				<tr>
					<td>Subtotal</td>
					<td>${{ $order['subtotal'] . '' }}</td>
				</tr>
				<tr>
					<td>Delivery Charge</td>
					<td>${{ ($delivery['cost'] * 100) / 100 }}</td>
				</tr>
				<tr>
					<td>Tax</td>
					<td>${{ ceil($order['subtotal'] * $order['tax']) / 100 }}</td>
				</tr>
				<tr>
					<td>Total</td>
					<td>${{ $order['total'] }}</td>
				</tr>
			</table>
			<h3>Notes</h3>
			<p>{{ $order['note'] }}</p>
		</div>
	</div>
</body>
</html>