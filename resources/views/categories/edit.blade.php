@extends('layouts.app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-8 col-md-offset-2">
			<h1>Category</h1>
			<category-form
				@if($category)
					:category="{{ $category }}"
					:should-update=true
				@endif
			></category-form>
		</div>
	</div>
</div>
@endsection