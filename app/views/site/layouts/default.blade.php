<!DOCTYPE html>
<html lang="en">
	<head>
		<!-- Basic Page Needs
		================================================== -->
		<meta charset="utf-8" />
		<title>
			@section('title')
			DCST -Prione
			@show
		</title>
		@section('meta_keywords')
		<meta name="keywords" content="Prione, DCST, keywords, here" />
		@show
		@section('meta_author')
		<meta name="author" content="CompassitesInc" />
		@show
		@section('meta_description')
		<meta name="description" content="DCST , Prione" />
                @show
		<!-- Mobile Specific Metas
		================================================== -->
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<!-- CSS
		================================================== -->
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}">
            {{ HTML::style('assets/bootstrap-v3.2.0/css/bootstrap.min.css'); }}
            {{ HTML::style('assets/bootstrap-v3.2.0/css/bootstrap-theme.min.css'); }}
            {{ HTML::style('assets/font-awesome-v4.1.0/css/font-awesome.min.css'); }}
            {{ HTML::style('assets/jquery-ui-v1.10.3/css/smoothness/jquery-ui-1.10.3.custom.css'); }}
            {{ HTML::style('assets/jquery-jqGrid-v4.6.0/css/ui.jqgrid.css'); }}
            <!-- {{ HTML::style('assets/adminlte/css/AdminLTE.css'); }} -->
            {{ HTML::script('assets/jquery-v2.0.3/jquery.js'); }}
            {{ HTML::script('assets/jquery-ui-v1.10.3/dev/minified/jquery.ui.effect.min.js'); }}
            {{ HTML::script('assets/jquery-ui-v1.10.3/js/jquery-ui.js'); }}
            {{ HTML::script('assets/jquery-ui-v1.10.3/dev/minified/jquery.ui.effect-shake.min.js'); }}
            {{ HTML::script('assets/jquery-scrollto-v1.4.11/jquery.scrollTo.min.js'); }}
            {{ HTML::script('assets/bootstrap-v3.2.0/js/bootstrap.min.js'); }}
            {{ HTML::script('assets/jquery-jqMgVal-v0.1/jquery.jqMgVal.src.js'); }}
            {{ HTML::script('assets/prione/util.js'); }}
            {{ HTML::script('assets/jquery-jqGrid-v4.6.0/js/i18n/grid.locale-en.js'); }}
            {{--{{ HTML::script('assets/jquery-jqGrid-v4.6.0/js/jquery.jqGrid.src.js'); }}--}}
            {{--{{ HTML::script('assets/jquery-jqGrid-v4.6.0/js/jquery.jqGrid.min.js'); }}--}}
            {{ HTML::script('assets/jquery-jqGrid-v4.6.0/src/jquery.jqGrid.js'); }}

		<style>
        body {
            padding: 60px 0;
        }
		@section('styles')
		@show
		</style>

		<!-- HTML5 shim, for IE6-8 support of HTML5 elements -->
		<!--[if lt IE 9]>
		<script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
		<![endif]-->

		<!-- Favicons
		================================================== -->
		<link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{{ asset('assets/ico/apple-touch-icon-144-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{{ asset('assets/ico/apple-touch-icon-114-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{{ asset('assets/ico/apple-touch-icon-72-precomposed.png') }}}">
		<link rel="apple-touch-icon-precomposed" href="{{{ asset('assets/ico/apple-touch-icon-57-precomposed.png') }}}">
		<link rel="shortcut icon" href="{{{ asset('assets/ico/favicon.png') }}}">
	</head>

	<body>
		<!-- To make sticky footer need to wrap in a div -->
		<div id="wrap">
		<!-- Navbar -->
		<div class="navbar navbar-default navbar-inverse navbar-fixed-top">
			 <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse navbar-ex1-collapse">
                    <ul class="nav navbar-nav">
						<li {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ URL::to('') }}}">Home</a></li>
					</ul>

                    <ul class="nav navbar-nav pull-right">
                        @if (Auth::check())
                        @if (Auth::user()->hasRole('admin'))
                        <li><a href="{{{ URL::to('admin/users') }}}">Admin Panel</a></li>
                        <li><a href="{{{ URL::to('report/admin') }}}">Admin Report</a></li>
                        @endif
                        {{--<li><a href="{{{ URL::to('admin/locallead') }}}">Local Lead</a></li>--}}
                        <li {{ (Request::is('request') ? ' class="active"' : '') }}><a href="{{{ URL::to('/request') }}}"><span class="glyphicon glyphicon-plus"></span>Seller Request</a></li>
                        <li><a href="{{{ URL::to('user') }}}">Logged in as {{{ Auth::user()->username }}}</a></li>
                        <li><a href="{{{ URL::to('user/logout') }}}">Logout</a></li>
                        @else
                        <li {{ (Request::is('request') ? ' class="active"' : '') }}><a href="{{{ URL::to('/request') }}}"><span class="glyphicon glyphicon-plus"></span>Seller Request</a></li>
                        <li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
                        <!--
                        <li {{ (Request::is('user/create') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/create') }}}">{{{ Lang::get('site.sign_up') }}}</a></li>
                        -->
                        @endif
                    </ul>
					<!-- ./ nav-collapse -->
				</div>
			</div>
		</div>
		<!-- ./ navbar -->

		<!-- Container -->
		<div class="container">
			<!-- Notifications -->
			@include('notifications')
			<!-- ./ notifications -->

			<!-- Content -->
			@yield('content')
			<!-- ./ content -->
		</div>
		<!-- ./ container -->

		<!-- the following div is needed to make a sticky footer -->
		<div id="push"></div>
		</div>
		<!-- ./wrap -->


	    <div id="footer">
	      <div class="container">
	        {{--<p class="muted credit">Prion</p>--}}
	      </div>
	    </div>

		<!-- Javascripts
		================================================== -->
        {{--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>--}}
        <script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>

        @yield('scripts')
	</body>
</html>
