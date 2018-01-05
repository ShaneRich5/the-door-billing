@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Orders #{{ $order->id }}</div>
				<p>Created By {{ $user->first_name }} {{ $user->last_name }} at {{ $order->created_at }}</p>
				<p>Billed to {{ $order->billing->street }}, {{ $order->billing->city }}, {{ $order->billing->state }}</p>
				<p>Deliver by {{ $delivery->created_at }}</p>
				<p>Delivery location {{ $delivery->location->owner }} {{ $delivery->location->name }}</p>
				<p>Delivery address {{ $delivery->location->address->street }}, {{ $delivery->location->address->city }}, {{ $delivery->location->address->state }}</p>

				<p>Subtotal: {{ $order->subtotal }}</p>
				<p>Tax: {{ $order->subtotal }}</p>
				<p>Delivery Cost: {{ $order->subtotal }}</p>
				<p>Total: {{ $order->total }}</p>

				@forelse ($order->menuItems as $menuItem)
					<p>{{ $menuItem }}</p>
				@empty
					<p>No menu items selected</p>
				@endforelse
			</div>
		</div>
	</div>
</div>
@endsection