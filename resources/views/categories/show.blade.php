@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<div class="panel panel-default">
				<div class="panel-heading">{{ $category->name }}
					<div style="float: right;">
						<a href="{{ route('categories.edit', $category->id) }}">Edit</a>
						<quick-delete-form url="{{ route('categories.destroy', $category->id) }}"></quick-delete-form>
					</div>
				</div>
				<div class="panel-body">
					@if (session('status'))
					<div class="alert alert-success">
						{{ session('status') }}
					</div>
					@endif
					Description: {{ $category->description }}</br>
					Catering Maximum: {{ $category->catering_maximum }}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection