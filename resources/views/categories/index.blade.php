@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">Categories
					<a style="float: right;" href="{{ route('categories.create') }}">Create</a>
				</div>
				<div class="list-group">
					@forelse ($categories as $category)
						<a
							class="list-group-item list-group-item-action"
							href="{{ route('categories.show', $category->id) }}"
						>{{ $category->name }}</a>
					@empty
						<a class="list-group-item" href="#">No categories available</a>
					@endforelse
				</div>
			</div>
		</div>
	</div>
</div>
@endsection