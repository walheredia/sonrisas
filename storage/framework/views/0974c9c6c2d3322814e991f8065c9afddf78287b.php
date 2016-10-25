<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Sonrisas | Administración</title>
	<link rel="stylesheet" href="<?php echo e(URL::asset('css/bootstrap.min.css')); ?>">
	<link rel="stylesheet" href="<?php echo e(URL::asset('css/bootstrap-theme.min.css')); ?>">
	<style>
		table tr {
			text-align: left;
			margin: 10px;
		}
	</style>

</head>
<body>
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="navbar-header">
	    	<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
	      		<span class="sr-only">Toggle navigation</span>
	      		<span class="icon-bar"></span>
	      		<span class="icon-bar"></span>
	      		<span class="icon-bar"></span>
	    	</button>
	    	<a class="navbar-brand" href="<?php echo e(URL::asset('')); ?>">Inicio</a>
	  	</div>

		  	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		  		 <ul class="nav navbar-nav">
		  		 	<li class="dropdown">
				        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Usuario <b class="caret"></b></a>
				        <ul class="dropdown-menu">
				  		 	<li class="<?php echo e((Request::is('alta')) ? 'active' : ''); ?>">
				  		 		<a href="<?php echo e(url('alta')); ?>">Alta</a>
				  		 	</li>
				        </ul>
			      	</li>
		  		 </ul>
		  		 <ul class="nav navbar-nav navbar-right">
		  		 	<li class="dropdown">
				        <a href="#" class="dropdown-toggle" data-toggle="dropdown"> Opciones del usuario <strong></strong> <b class="caret"></b></a>
				        <ul class="dropdown-menu">
				  		 	<li>Registrar un nuevo usuario</li>
				        </ul>
			      	</li>
			    </ul>
		  	</div>
	  	
	</nav>

	<?php echo $__env->yieldContent('content'); ?>
	
	<script src="<?php echo e(URL::asset('js/jquery-2.0.3.min.js')); ?>"></script>
	<script src="<?php echo e(URL::asset('js/bootstrap.min.js')); ?>"></script>
	<script src="<?php echo e(URL::asset('js/jquery-ui-1.10.3.custom.min.js')); ?>"></script>
	<script src="<?php echo e(URL::asset('js/main.js')); ?>"></script>
</body>
</html>