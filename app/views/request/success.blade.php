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
        <div class="alert alert-success alert-block">
            <h4>Success</h4>
                 Ticket :: #{{ $ticketid }} Created successfully.
            </div>

    </div>
</div>

@stop