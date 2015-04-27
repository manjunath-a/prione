@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
    {{{ Lang::get('user/user.settings') }}} ::
    @parent
@stop

{{-- New Laravel 4 Feature in use --}}
@section('styles')
    @parent
    body {
    background: #f2f2f2;
    }
@stop

{{-- Content --}}
@section('content')

<div class="row">
    <div class="col-sm-8 col-sm-offset-2">

        <div class="page-header">
            <h1><span class="glyphicon glyphicon-flash"></span> Requester Form</h1>
        </div>
        @if ($errors->count())
            <div class="alert alert-warning">
                {{ HTML::ul($errors->all()) }}
            </div>
        @endif
        <!-- FORM STARTS HERE -->
            {{ Form::open(array('url' => 'request/create', 'method' => 'post')) }}

            {{--<div class="page-header">--}}
                {{--<h2>Requester Details</h2>--}}
            {{--</div>--}}

            <div class="form-group">
                <label for="requester_name">Requester Name</label>
                {{ Form::text('requester_name', null, array('class' => 'form-control', 'placeholder' => "Your Name")) }}
            </div>

            <div class="form-group">
                <label for="email">Requester Email</label>
                {{ Form::text('email', null, array('class' => 'form-control', 'placeholder' => "xxxxxx@example.com")) }}
            </div>

            <div class="form-group">
                <label for="contact_number">Requester Contact Number</label>
                {{ Form::text('contact_number', null, array('class' => 'form-control', 'placeholder' => "+91-1234567890")) }}
            </div>

            <div class="page-header">
                <h2>Merchant Details</h2>
            </div>

            <div class="form-group">
                <label for="merchant_name">Merchant Name</label>
                {{ Form::text('merchant_name', null, array('class' => 'form-control', 'placeholder' => "Merchant Name")) }}
            </div>

            <div class="form-group">
                <label for="merchant_id">Merchant ID</label>
                {{ Form::text('merchant_id', null, array('class' => 'form-control', 'placeholder' => "Merchant Id")) }}
            </div>

            <div class="form-group">
                <label for="merchant_city_id">Merchant city</label>
                <div>
                   {{Form::select('merchant_city_id', $city, '', array('class' => 'form-control'))}}
                </div>
            </div>

            <div class="form-group">
                <label for="merchant_sales_channel_id">Merchant Sales Channel</label>
                <div>
                    {{Form::select('merchant_sales_channel_id', $salesChannel, '', array('class' => 'form-control'))}}
                </div>
            </div>

            <div class="form-group">
                <label for="poc_name">POC Name</label>
                {{ Form::text('poc_name', null, array('class' => 'form-control', 'placeholder' => "POC Name")) }}
            </div>

            <div class="form-group">
                <label for="poc_email">POC Email</label>
                {{ Form::text('poc_email', null, array('class' => 'form-control', 'placeholder' => "POC Email")) }}
            </div>

            <div class="form-group">
                <label for="poc_number">POC Contact Number</label>
                {{ Form::text('poc_number', null, array('class' => 'form-control', 'placeholder' => "POC Number")) }}
            </div>

            <div class="page-header">
                <h2>Request Details</h2>
            </div>

            <div class="form-group">
                <label for="category_id">Category</label>
                <div>
                    {{Form::select('category_id', $category, '', array('class' => 'form-control'))}}
                </div>
            </div>

            <div class="form-group">
                <label for="total_sku">Number of SKU</label>
                {{ Form::text('total_sku', null, array('class' => 'form-control', 'placeholder' => "Total SKU")) }}
            </div>


            <div class="form-group">
                <label for="image_available">Images Available</label>
                <div>
                    {{Form::select('image_available', array('1' => 'No','2' => 'Yes'), '1', array('class' => 'form-control'))}}
                </div>
            </div>

            <div class="form-group">
                <label for="comment">Comment</label>
                {{ Form::textarea('comment', null, array('class' => 'form-control','rows'=>4, 'cols' =>'50')) }}
            </div>
        {{ Form::submit('Submit', array('class' => 'btn btn-success')) }}
        {{ Form::close() }}

    </div>
</div>
   </div>

@stop
