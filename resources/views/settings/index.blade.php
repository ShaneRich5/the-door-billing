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
                    <delivery-cost-settings
                        @if ($delivery_cost)
                            delivery-cost="{{ $delivery_cost }}"
                        @endif
                    ></delivery-cost-settings>

                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">Zip Codes - Costs</div>
                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif

                    <zip-code-cost-list></zip-code-cost-list>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
