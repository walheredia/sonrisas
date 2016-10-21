<!DOCTYPE html>
<html>
	<head>
		<title>Axialent | Administration</title>

		<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/main.css') }}" />
		<link rel="stylesheet" type="text/css" href="{{ URL::asset('css/fancyAlert.min.css') }}" />
	</head>
	<body class="garra-body">
		<header>
			<nav>
				<ul class="nav">
					<div class="logo">
						<img src="{{ URL::asset('images/bconsicious-logo.png') }}">
					</div>

					<li class="{{ (Request::is('/') || Request::is('/*')) ? 'active' : '' }}">
						<a href="{{ url('') }}">Basic Information</a>
					</li>

					<li class="{{ (Request::is('consultants') || Request::is('consultants/*')) ? 'active' : '' }}">
						<a href="{{ url('consultants') }}">Consultants</a>

						<ul class="submenu">
							<li class="{{ (Request::is('consultants')) ? 'active' : '' }}">
								<a href="{{ url('consultants') }}">Manage</a>
							</li>

							<li class="{{ (Request::is('consultants/add')) ? 'active' : '' }}">
								<a href="{{ url('consultants/add') }}">Create</a>
							</li>
						</ul>
					</li>

					<li class="{{ (Request::is('thematics') || Request::is('thematics/*')) ? 'active' : '' }}">
						<a href="{{ url('thematics') }}">Thematics</a>

						<ul class="submenu">
							<li class="{{ (Request::is('thematics')) ? 'active' : '' }}">
								<a href="{{ url('thematics') }}">Manage</a>
							</li>

							<li class="{{ (Request::is('thematics/add')) ? 'active' : '' }}">
								<a href="{{ url('thematics/add') }}">Create</a>
							</li>
						</ul>
					</li>

					<li class="{{ (Request::is('excercises') || Request::is('excercises/*')) ? 'active' : '' }}">
						<a href="{{ url('excercises') }}">Excercises</a>

						<ul class="submenu">
							<li class="{{ (Request::is('excercises')) ? 'active' : '' }}">
								<a href="{{ url('excercises') }}">Manage</a>
							</li>

							<li class="{{ (Request::is('excercises/add')) ? 'active' : '' }}">
								<a href="{{ url('excercises/add') }}">Create</a>
							</li>
						</ul>
					</li>

					<li class="{{ (Request::is('medias') || Request::is('medias/*')) ? 'active' : '' }}">
						<a href="{{ url('medias') }}">All Medias</a>
					</li>

					
					<li class="{{ (Request::is('app/*')) ? 'active' : '' }}">
						<a href="{{ url('app/users') }}">App</a>

						<ul class="submenu">
							<li class="{{ (Request::is('app/users') || Request::is('app/users/*')) ? 'active' : '' }}">
								<a href="{{ url('app/users') }}">Users</a>
							</li>

							<li class="{{ (Request::is('app/comments') || Request::is('app/comments/*')) ? 'active' : '' }}">
								<a href="{{ url('app/comments') }}">Comments</a>
							</li>
						</ul>
					</li>




					<!--
					<li class="{{ (Request::is('app/users') || Request::is('app/users/*')) ? 'active' : '' }}">
						<a href="{{ url('app/users') }}">App Users</a>
					</li>

					<li class="{{ (Request::is('app/comments') || Request::is('app/comments/*')) ? 'active' : '' }}">
						<a href="{{ url('app/comments') }}">App Comments</a>
					</li>
					-->

					<li class="menu-config {{ (Request::is('profile') || Request::is('users')) ? 'active' : '' }}" >


						<ul class="submenu">
							<!--
							<li><a href="{{ url('profile') }}">{{ Auth::user()->name }}</a></li>
							<li><a href="{{ url('users') }}">Users</a></li>
							-->
							<li><a href="{{ url('auth/logout') }}">Logout</a></li>
						</ul>


						<a href="">Configuration</a>

					</li>
				</ul>
			</nav>
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