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
        <link href='http://fonts.googleapis.com/css?family=Lato:400,700,900,300,100' rel='stylesheet' type='text/css'>
            {{ HTML::style('assets/bootstrap-v3.2.0/css/bootstrap.min.css'); }}
            {{ HTML::style('assets/bootstrap-v3.2.0/css/bootstrap-theme.min.css'); }}
            {{ HTML::style('assets/font-awesome-v4.1.0/css/font-awesome.min.css'); }}
            {{ HTML::style('assets/jquery-ui-v1.10.3/css/smoothness/jquery-ui-1.10.3.custom.css'); }}
            {{ HTML::style('assets/jquery-jqGrid-v4.6.0/css/ui.jqgrid.css'); }}
            {{ HTML::style('assets/css/site.css'); }}

            {{ HTML::script('assets/jquery-v2.0.3/jquery.js'); }}
            {{ HTML::script('assets/jquery-ui-v1.10.3/dev/minified/jquery.ui.effect.min.js'); }}
            {{ HTML::script('assets/jquery-ui-v1.10.3/js/jquery-ui.js'); }}
            {{ HTML::script('assets/jquery-ui-v1.10.3/dev/minified/jquery.ui.effect-shake.min.js'); }}
            {{ HTML::script('assets/jquery-scrollto-v1.4.11/jquery.scrollTo.min.js'); }}

            <!-- {{ HTML::script('assets/bootstrap-v3.2.0/js/bootstrap.min.js'); }} -->
            {{ HTML::script('assets/jquery-jqMgVal-v0.1/jquery.jqMgVal.src.js'); }}
            {{ HTML::script('assets/prione/util.js'); }}
            {{ HTML::script('assets/jquery-jqGrid-v4.6.0/js/i18n/grid.locale-en.js'); }}
            {{ HTML::script('assets/jquery-jqGrid-v4.6.0/src/jquery.jqGrid.js'); }}

		<style>
        body {
            padding: 60px 0;
        }
		@section('styles')
		@show
		</style>
    <script type="text/javascript">
      (function () {
        baseURL = '<?php echo Config::get('app.url');?>';
      })();
    </script>
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
			 <div class="container col-md-12">
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
          						<li class="logo" {{ (Request::is('/') ? ' class="active"' : '') }}><a href="{{{ URL::to('') }}}"></a></li>
          					</ul>

                    <ul class="nav navbar-nav pull-right">
                        @if (Auth::check())
                        @if (Auth::user()->hasRole('admin'))
                          <li><a href="{{{ URL::to('admin/users') }}}">Admin Panel</a></li>
                          <li><a href="{{{ URL::to('report/admin') }}}">Admin Report</a></li>
                        @endif
                          <li {{ (Request::is('request*') ? ' class="active"' : '') }}><a href="{{{ URL::to('/request') }}}">Seller Request</a></li>
                          @if (Auth::user()->hasRole('Catalog Manager') || Auth::user()->hasRole('Catalog Team Lead') ||
                            Auth::user()->hasRole('Editing Manager') || Auth::user()->hasRole('Editing Team Lead') || Auth::user()->hasRole('Local Team Lead') )
                          <li  class="dropdown{{ (Request::is('ticket*|dashboard/*') ? ' active' : '') }}">
                              <a class="dropdown-toggle" data-toggle="dropdown" href="{{{ URL::to('admin/report') }}}">
                                 Tickets <span class="caret"></span>
                              </a>
                              <ul class="dropdown-menu" role="menu">
                                <li {{ (Request::is('dashboard/*') ? ' class="active"' : '') }}>
                                <a  href="{{{ URL::to('/') }}}">
                                <span class="glyphicon glyphicon-folder-open"></span> Open Tickets</a></li>
                                <li {{ (Request::is('ticket/status/resolved') ? ' class="active"' : '') }}>
                                  <a href="{{{ URL::to('ticket/status/resolved') }}}">
                                  <span class="glyphicon glyphicon-flag"></span> Resolved Tickets</a></li>
                                <li {{ (Request::is('ticket/status/rejected') ? ' class="active"' : '') }}>
                                  <a  href="{{{ URL::to('ticket/status/rejected') }}}">
                                  <span class="glyphicon glyphicon-remove"></span> Rejected Tickets</a>
                                </li>
                                <li {{ (Request::is('ticket/status/closed') ? ' class="active"' : '') }}>
                                  <a  href="{{{ URL::to('ticket/status/closed') }}}">
                                  <span class="glyphicon glyphicon-ok-sign"></span> Closed Tickets</a>
                                </li>
                            </ul>
                          @endif
                          <li class="dropdown user">
                            <img title="User" alt="User" src="/assets/css/images/user-pic.png" class="user-img img-circle">
                            <span>Welcome</span> <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                               {{{ Auth::user()->username }}} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                              <li><a  href="{{{ URL::to('user/settings') }}}"><span class="glyphicon glyphicon-wrench"></span> Settings</a></li>
                              <li class="divider"></li>
                              <li><a href="{{{ URL::to('user/logout') }}}"><span class="glyphicon glyphicon-share"></span> Logout</a></li>
                            </ul>
                        </li>
                        @else
                          <li {{ (Request::is('request') ? ' class="active"' : '') }}><a href="{{{ URL::to('/request') }}}"><span class="glyphicon glyphicon-plus"></span>Seller Request</a></li>
                          <li {{ (Request::is('user/login') ? ' class="active"' : '') }}><a href="{{{ URL::to('user/login') }}}">Login</a></li>
                        @endif
                    </ul>
					<!-- ./ nav-collapse -->
				</div>
			</div>
		</div>
		<!-- ./ navbar -->

		<!-- Container -->
		<div class="container col-md-12">
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
