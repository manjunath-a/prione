@extends('site.layouts.default')

{{-- Web site Title --}}
@section('title')
{{{ Lang::get('user/user.login') }}} ::
@parent
@stop
@section('styles')
    @parent
    .checkbox input[type=checkbox] {
      position: relative;
    }
@stop


{{-- Content --}}
@section('content')
<div class="page-header">
	<h1>Login into your account</h1>
</div>
{{ Confide::makeLoginForm()->render() }}
@stop
