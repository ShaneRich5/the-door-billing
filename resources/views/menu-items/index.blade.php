@extends('layouts.app')

@section('content')
<tag-container
	:initial-tags="{{ json_encode($tags) }}"
></tag-container>
@endsection