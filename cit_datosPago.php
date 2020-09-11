<!-- Modal para edicion de datos -->
<div class="modal fade" id="modalPago" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-sistema">
                <h5 class="modal-title" id="myModalLabel">Datos de Pago</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            
            
            <form name="">
                
                <div class="modal-body">
                    <div class="form-row">
                        <div class="form-group col-12 col-md-6">
                            <label class="col-form-label">Número de boleta</label>
                            <input type="text" class="form-control css-input" name="num_boleta" placeholder="Ingrese el número de la boleta" maxlength="40" required>
                            <div class="invalid-tooltip">Dato obligatorio.</div>
                        </div>
                         
                        <div class="form-group col-12 col-md-6">
                            <label class="col-form-label">Fecha</label>
                            <input type="date" class="form-control css-input" name="fecha_boleta" required>
                            <div class="invalid-tooltip">Dato obligatorio.</div>
                        </div>
                        
                        <div class="form-group col-12 col-md-6">
                            <label class="col-form-label">Detalles</label>
                            <textarea type="date" class="form-control css-input" name="det_boleta" placeholder="Ingrese los detalles de la boleta" required></textarea>
                            <div class="invalid-tooltip">Dato obligatorio.</div>
                        </div>
                        
                        
                        
                        
                        
                        <!----------------------- select forma de pago ------------------------------->  
                        <div class="col-auto pr-1">
                            <label class="col-form-label col-form-label-sm">Forma de Pago</label>
                        </div>
                        
                         <div class="col-12 col-sm-3 col-md-2">
                             <select name="formap" class="custom-select custom-select-sm">
                                 <?php
                                 $qpago = "SELECT c.id, c.id_pag_mod , pag_mod.descripcion, pag_mod.id idp 
                                             FROM citas c, pag_modalidad pag_mod 
                                            WHERE status = 'T' 
                                              AND c.id = '$id_c' 
                                              AND c.id_pag_mod = pag_mod.id";
                                 $formap = mysqli_query($conexion,$qpago);  
                                 while($formapago = mysqli_fetch_array($formap)){
                                     $idp = $formapago['idp'];    
                                 ?>
                                 <option value="<?php echo $idp; ?>"><?php echo $formapago['descripcion'];?></option>
                               
                                 <?php 
                                 } 
                                 $mostrar = "SELECT id, descripcion FROM pag_modalidad WHERE status = 'T' and id != '$idp'";
                                 $traer   = mysqli_query($conexion,$mostrar);

                                 while($respuesta = mysqli_fetch_array($traer)){
                                 ?>
                                 <option value="<?php echo $respuesta['id']; ?>"><?php echo $respuesta['descripcion'];?></option>
                                 <?php
                                 } 
                                 ?>
                             </select>
                             <!-- valor para historial--> 
                             <input type="hidden" name="forpago_ant" value="<?php echo $idp;?>">
                        </div>
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        
                        <div class="form-group col-12 col-md-6">
                            <label class="col-form-label">Teléfono</label>
                            <input type="text" class="form-control css-input" name="telefono" id="telefono" placeholder="Ingrese el número de teléfono"  minlength="5" maxlength="9" pattern="[0-9]+" title="Solo números" required>
                            <div class="invalid-tooltip">Dato obligatorio (Solo números).</div>
                        </div>
                                
                        <div class="form-group col-12 col-md-6">
                            <label class="col-form-label">Correo</label>
                            <input type="email" class="form-control css-input" name="correo" id="correo" placeholder="Ingrese el correo electrónico" maxlength="40" autocomplete="off" required>
                            <div class="invalid-tooltip">Dato obligatorio.</div>
                        </div>
                          
                        <div class="form-group col-12 col-md-6">
                            <label class="col-form-label">Dirección</label>
                            <select class="custom-select css-input" name="direccion" id="direccion">
                                <?php 
                                $query    = "SELECT * FROM distritos WHERE status = 'T' ORDER BY 2 ASC";
                                $resquery = mysqli_query($conexion,$query);
                                while($ver= mysqli_fetch_array($resquery)){
                                ?>
                                <option value="<?php echo $ver['id'];?>"> <?php echo $ver['descripcion'] ;?></option>
                                <?php 
                                } 
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group col-12 col-md-6">
                            <label class="col-form-label col-12">Status</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status1" name="status" class="custom-control-input" value="A">
                                <label class="custom-control-label label_radio" for="status1">Activo</label>
                            </div>
                            
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status2" name="status" class="custom-control-input" value="I">
                                <label class="custom-control-label label_radio" for="status2">Inactivo</label>
                            </div>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status3" name="status" class="custom-control-input" value="F">
                                <label class="custom-control-label label_radio" for="status3">Suspendido</label>
                            </div>
                        </div>
                    </div>
                </div>
        
                <div class="modal-footer">
                    <!--<button type="submit" class="btn btn-primary" id="actualizadatos" ><i class="fas fa-check"></i> Actualizar</button>-->
                    <button type="button" class="btn btn-primary" data-dismiss="modal" id="actualizadatos" onclick="actualizarcli(this.form)"><i class="fas fa-check"></i> Actualizar</button>
                </div>
            </form>
            
            
            
            
            
            
        </div>
    </div>
</div>