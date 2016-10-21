<!DOCTYPE html>
<html>
	<head>
		<title>Sonrisas | Administración</title>

		<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/fancyAlert.min.css') }}" />
	</head>
	<body class="garra-body">
		<header>
			
		</header>

		<div class="garra-view">
			@yield('content')
		</div>

		<footer>
			
		</footer>

		<script type="text/javascript" src="{{ URL::asset('js/fancyAlert.min.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('js/easyModal.js') }}"></script>
		<script type="text/javascript" src="{{ URL::asset('js/main.js') }}"></script>
	</body>
</html>