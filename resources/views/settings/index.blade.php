@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Printer Settings</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <printer-settings
                        @if ($printer_id)
                            id="{{ $printer_id }}"
                        @endif
                    ></printer-settings>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
