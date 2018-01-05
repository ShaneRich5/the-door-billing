@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Orders</div>
				<div class="list-group">
					@forelse ($orders as $order)
						<a
							class="list-group-item list-group-item-action"
							href="{{ route('orders.show', $order->id) }}"
						>{{ $order->id }}</a>
					@empty
						<a class="list-group-item" href="#">No orders available</a>
					@endforelse
				</div>
			</div>
		</div>
	</div>
</div>
@endsection