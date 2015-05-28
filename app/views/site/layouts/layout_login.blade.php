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
		<link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css'>
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
        <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap-theme.min.css')}}">
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
		<div class="login_logo">
		</div>
		<!-- Container -->
		<div class="login_container">
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
