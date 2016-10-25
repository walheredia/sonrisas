<?php $__env->startSection('content'); ?>

    <div class="container">
        
        <div class="row text-center">

            <h1>Iniciar Sesión</h1>

            <div class="col-md-4 col-md-offset-4 text-left">

                
                <form action="login" method="post" class="form-vertical" role="form">
                    
                    <div class="form-group">
                        
                        <div class="col-sm-12">
                            <p class="help-block margin-bottom-cero"><small>Usuario:</small></p>
                            <input type="text" name="username" placeholder="Nombre de usuario" class="form-control">
                        </div>

                    </div>

                    <div class="form-group">
                        
                        <div class="col-sm-12">
                            <p class="help-block margin-bottom-cero"><small>Contraseña:</small></p>
                            <input type="password" name="password" placeholder="Contraseña" class="form-control">
                        </div>
                        
                    </div>
                     <div class="form-group">
                     <div class="checkbox col-sm-12">
                            <label>
                              <input type="checkbox" ('remember', true)> Recordarme
                            </label>
                        </div>
                    <div class="form-group">
                    </div>
                        
                        <div class="col-sm-12">
                            <input type="submit" value="Iniciar Sesión" class="btn btn-success form-control">
                        </div>

                    </div>

                </form>

            </div>

        </div>

    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', array_except(get_defined_vars(), array('__data', '__path')))->render(); ?>