<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<meta name="description" content="Magz is a HTML5 & CSS3 magazine template is based on Bootstrap 3.">
		<meta name="author" content="Kodinger">
		<meta name="keyword" content="magz, html5, css3, template, magazine template">
		<!-- Shareable -->
		<meta property="og:title" content="HTML5 & CSS3 magazine template is based on Bootstrap 3" />
		<meta property="og:type" content="article" />
		<meta property="og:url" content="http://github.com/nauvalazhar/Magz" />
		<meta property="og:image" content="https://raw.githubusercontent.com/nauvalazhar/Magz/master/images/preview.png" />
		<title>@yield('title')</title>
		<!-- Bootstrap -->
		<link rel="stylesheet" href="{{ asset('layouts/scripts/bootstrap/bootstrap.min.css') }}">
		<!-- IonIcons -->
		<link rel="stylesheet" href="{{ asset('layouts/scripts/ionicons/css/ionicons.min.css') }}">
		<!-- Toast -->
		<link rel="stylesheet" href="{{ asset('layouts/scripts/toast/jquery.toast.min.css') }}">
		<!-- OwlCarousel -->
		<link rel="stylesheet" href="{{ asset('layouts/scripts/owlcarousel/dist/assets/owl.carousel.min.css') }}">
		<link rel="stylesheet" href="{{ asset('layouts/scripts/owlcarousel/dist/assets/owl.theme.default.min.css') }}">
		<!-- Magnific Popup -->
		<link rel="stylesheet" href="{{ asset('layouts/scripts/magnific-popup/dist/magnific-popup.css') }}">
		<link rel="stylesheet" href="{{ asset('layouts/scripts/sweetalert/dist/sweetalert.css') }}">
		<!-- Custom style -->
		<link rel="stylesheet" href="{{ asset('layouts/css/style.css') }}">
		<link rel="stylesheet" href="{{ asset('layouts/css/skins/all.css') }}">
		<link rel="stylesheet" href="{{ asset('layouts/css/demo.css') }}">
	</head>

	<body class="skin-orange">
	  	@include('shared.header')

	  	@yield('content')

		<!-- Start footer -->
		@include('shared.footer')
		<!-- End Footer -->

		<!-- JS -->
		<script src="{{ asset('layouts/js/jquery.js') }}"></script>
		<script src="{{ asset('layouts/js/jquery.migrate.js') }}"></script>
		<script src="{{ asset('layouts/scripts/bootstrap/bootstrap.min.js') }}"></script>
		<script>var $target_end=$(".best-of-the-week");</script>
		<script src="{{ asset('layouts/scripts/jquery-number/jquery.number.min.js') }}"></script>
		<script src="{{ asset('layouts/scripts/owlcarousel/dist/owl.carousel.min.js') }}"></script>
		<script src="{{ asset('layouts/scripts/magnific-popup/dist/jquery.magnific-popup.min.js') }}"></script>
		<script src="{{ asset('layouts/scripts/easescroll/jquery.easeScroll.js') }}"></script>
		<script src="{{ asset('layouts/scripts/sweetalert/dist/sweetalert.min.js') }}"></script>
		<script src="{{ asset('layouts/scripts/toast/jquery.toast.min.js') }}"></script>
		<script src="{{ asset('layouts/js/demo.js') }}"></script>
		<script src="{{ asset('layouts/js/e-magz.js') }}"></script>
	</body>
</html>
