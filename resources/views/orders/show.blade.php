@extends('layouts.app')

@section('styles')
<style>
.container {
	background: white;
	border-radius: 5px;
}

.row:first-child {
	padding: 0 14px;
}

.row::not(:last-child) {
	border-bottom: 1px solid black;
}

</style>
@endsection

@section('content')
<div class="container">
	<div class="row">
		<h1>Order #{{ $order->id }} <small>{{ $order->status }}</small></h1>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<p><b>Billed To</b></p>
			<p>{{ $user->first_name }} {{ $user->last_name }}</p>
			<p>{{ $user->phone }}</p>
			<p>{{ $user->email }}</p>
			<p>{{ $billing_address->street }}, {{ $billing_address->city }}, {{ $billing_address->state }}</p>
		</div>
		<div class="col-xs-4">
			<p><b>Deliver To</b></p>
			<p>{{ $delivery_location->owner }}</p>
			<p>{{ $delivery_location->name }}</p>
			<p>{{ $delivery_location->phone }}</p>
			<p>{{ $delivery_address->street }}, {{ $delivery_address->city }}, {{ $delivery_address->state }}</p>
		</div>
		<div class="col-xs-4">
			<p><b>Delivery Summary</b></p>
			<p>Deliver by {{ $delivery->deliver_by }}</p>
			<p>Serving {{ $delivery->attendance }} people</p>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-4">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th>Menu Items Requested
				<tbody>
					@forelse ($menu_items as $menu_item)
					<tr>
						<td>{{ $menu_item->name }}</td>
					</tr>
					@empty
					<tr>
						<td>No menu items specified</td>
					</tr>
					@endforelse
				</tbody>
			</table>
		</div>
		<div class="col-xs-4">
			<p><b>Notes</b></p>
			@if ($order->note)
				<p>{{ $order->note }}</p>
			@else
				<p>No notes</p>
			@endif
		</div>
		<div class="col-xs-4">
			<table class="table table-bordered">
				<thead>
					<tr>
						<th colspan="2">Payment Details</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<th>Subtotal</th>
						<td>${{ $order->subtotal }}</td>
					</tr>
					<tr>
						<th>Tax</th><td>${{ $order->tax }}</td>
					</tr>
					<tr>
						<th>Delivery</th><td>${{ $delivery->cost }}</td>
					</tr>
					<tr>
						<th>Total</th><td>${{ $order->total }}</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>
@endsection