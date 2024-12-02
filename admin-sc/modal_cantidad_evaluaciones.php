<?php
session_start();
include('../funciones-sc/conexion.php');

  $id_curso_asignatura = trim($_REQUEST['id_curso_asignatura']);
  
 
    $html = "";
    $html.= "
              <div class='modal-dialog' role='document'>
                <form action='listado_cursos_agregar_evaluacion.php' method='post' enctype='multipart/form-data'>
                  <div class='modal-content'>
                    <div class='modal-header'>
                      <h5 class='modal-title' id='exampleModalLabel'>Agregar Evaluaciones</h5>
                      <button type='button' class='close btn' data-dismiss='modal' aria-label='Close'>
                        <span aria-hidden='true'>&times;</span>
                      </button>
                    </div>
                    <div class='modal-body'>
                      <div class='row' >

                    

                        <div class='col'>
                          <div class='md-form'>
                          <label style='margin-left: 10px;'>Cantidad de evaluaciones</label>
                            <input type='text' id='cantidad_ev' name='cantidad_ev'  class='form-control validate' onkeyup='reemplazar(this)' placeholder='Ingrese cantidad de evaluaciones a agregar' required  >
                            <input type='hidden' name='id' value='$id_curso_asignatura'>
                          
                          </div>
                        </div>
                       
                      </div>
                     
                    
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-danger' data-dismiss='modal'>Cerrar</button>
                        <button type='submit' class='btn btn-primary'>Siguiente</button>
                    </div>
                  </div>
                </form>
              </div>
  
            ";// modal-header
 
            echo $html;
              ?>
