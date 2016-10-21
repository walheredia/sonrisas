<!DOCTYPE html>
<html>
	<head>
		<title>Sonrisas | Administración</title>

		<link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('css/main.css')); ?>" />
		<link rel="stylesheet" type="text/css" href="<?php echo e(URL::asset('css/fancyAlert.min.css')); ?>" />
	</head>
	<body class="garra-body">
		<header>
			
		</header>

		<div class="garra-view">
			<?php echo $__env->yieldContent('content'); ?>
		</div>

		<footer>
			
		</footer>

		<script type="text/javascript" src="<?php echo e(URL::asset('js/fancyAlert.min.js')); ?>"></script>
		<script type="text/javascript" src="<?php echo e(URL::asset('js/easyModal.js')); ?>"></script>
		<script type="text/javascript" src="<?php echo e(URL::asset('js/main.js')); ?>"></script>
	</body>
</html>