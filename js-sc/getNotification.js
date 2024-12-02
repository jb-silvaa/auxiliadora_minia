function getNotifications(id, perfil){   
    var xmlhttp = new XMLHttpRequest();   
    xmlhttp.onreadystatechange = function() {    
        if ( xmlhttp.readyState === 4 && xmlhttp.status === 200 ) {     
            var response = xmlhttp.responseText;     
            var notifications=JSON.parse(response); // create JSON object from response 
            console.log(notifications);   
            var ids_mensajes = [];
            var ids_planis_a = [];
            var ids_planis_r = [];  
            var ids_eval = [];    
            //ids.push(notifications[0].type); //este ya no lo necesito, tengo que pushear dentro de los if el tipo  
            ids_mensajes.push(id);
            var leido=1;
            var leido1=1;
            var leido2=1;
            var leido3=1;
            var planis_no_leidos_a=0;
            var planis_no_leidos_r=0;
            var mensajes_no_leidos=0;
            var eval_no_leidos=0;
            var plani_a = 20;
            var plani_r = 20;
            var eval = 20;
            var mensaje = 20;
            for( var i = 0 ; i < notifications.length ; i++ ){
                //COMUN
                var entityID = notifications[ i ].entityID;
                var read = notifications[ i ].read;
                var fecha = notifications[ i ].fecha;

                if(notifications[i].type == 'mensaje' && mensaje>0){
                    mensaje--;
                    //MENSAJE
                    ids_mensajes.push(entityID); 
                    var text = notifications[ i ].text;      
                    var autor = notifications[ i ].autor;
                    var archivo = notifications[ i ].archivo;                   
                    var tabla = document.getElementById("tabla-notis");
                    var fila = tabla.insertRow(tabla.length);

                    var cel1 = fila.insertCell(0);
                    var cel2 = fila.insertCell(1);
                    var cel3 = fila.insertCell(2);
                    var cel4 = fila.insertCell(3);
                    var cel5 = fila.insertCell(4);


                    cel1.innerHTML = autor; //Remitente
                    cel2.innerHTML = fecha; //Fecha
                    cel3.innerHTML = text; //Contenido
                    cel4.innerHTML = archivo; //Archivo
                    cel5.innerHTML=  '<a href="mensaje_responder.php?id='+entityID+'">Ir</a>';

                    if(read == 0){
                        leido = 0;
                        mensajes_no_leidos++;
                    }
                }else if(notifications[i].type == 'planificacion' && plani_r>0 && plani_a>0){
                    //PLANIFICACION
                    var curso = notifications[ i ].carga_curso;
                    var aprobacion = notifications[ i ].carga_aprobacion;
                    var asignatura = notifications[ i ].carga_asignatura;
                    var unidad = notifications[ i ].carga_unidad;
                    var curso_asignatura_id = notifications[ i ].carga_curso_asignatura_id;
                    if(aprobacion == '1' && perfil == 2){
                        plani_a--;
                        ids_planis_a.push(entityID); 
                        var tabla = document.getElementById("tabla-planis-a");
                        if(read == 0){
                            leido2 = 0;
                            planis_no_leidos_a++;
                        }
                    }else if(aprobacion == '-1' || aprobacion == '0'){
                        plani_r--;
                        ids_planis_r.push(entityID); 
                        var tabla = document.getElementById("tabla-planis-r");
                        if(read == 0){
                            leido1 = 0;
                            planis_no_leidos_r++;
                        }
                    }else if(aprobacion == '1' && perfil == 1){
                        continue;
                    }
                    
                    var fila = tabla.insertRow(tabla.length);

                    var cel1 = fila.insertCell(0);
                    var cel2 = fila.insertCell(1);
                    var cel3 = fila.insertCell(2);
                    var cel4 = fila.insertCell(3);
                    var cel5 = fila.insertCell(4);


                    cel1.innerHTML = curso; //Curso
                    cel2.innerHTML = asignatura; //Asignatura
                    cel3.innerHTML = unidad; //Unidad
                    cel4.innerHTML = fecha; //Fecha       
                    cel5.innerHTML=  '<a href="carga.php?id='+curso_asignatura_id+'">Ir</a>'; //carga.php?id=1027
                }else if(notifications[i].type == 'evaluacion' && eval>0){
                    eval--;
                    //EVALUACION
                    var curso = notifications[ i ].evaluacion_curso;
                    var aprobacion = notifications[ i ].evaluacion_aprobacion;
                    var asignatura = notifications[ i ].evaluacion_asignatura;
                    var curso_asignatura_id = notifications[ i ].evaluacion_curso_asignatura_id;
                    var fecha_prueba = notifications[ i ].evaluacion_fecha;
                    var nombre = notifications[ i ].evaluacion_nombre;

                    ids_eval.push(entityID); 
                    var tabla = document.getElementById("tabla-eval");

                    var fila = tabla.insertRow(tabla.length);

                    var cel1 = fila.insertCell(0);
                    var cel2 = fila.insertCell(1);
                    var cel3 = fila.insertCell(2);
                    var cel4 = fila.insertCell(3);
                    var cel5 = fila.insertCell(4);


                    cel1.innerHTML = curso; //Curso
                    cel2.innerHTML = asignatura; //Asignatura
                    cel3.innerHTML = nombre; //Unidad
                    cel4.innerHTML = fecha_prueba; //Fecha   
                    if(perfil == 1){
                        cel5.innerHTML=  '<a href="evaluacion.php?id='+curso_asignatura_id+'">Ir</a>';
                    }else{
                        cel5.innerHTML=  '<a href="evaluaciones.php?id='+curso_asignatura_id+'">Ir</a>';
                    } 
                    if(perfil == 2){
                        var cel6 = fila.insertCell(5);
                        if (aprobacion == -1){
                            cel6.innerHTML = 'Rechazado';
                        }else{
                            cel6.innerHTML = 'Aprobado';
                        }
                    }

                    if(read == 0){
                        leido3 = 0;
                        eval_no_leidos++;
                    }
                }
            }
            
            if(leido1==0 && ids_planis_r.length>0){
                document.getElementById("noti_3").style.visibility = "visible";
                document.getElementById("noti_3").innerHTML = planis_no_leidos_r;
            }
            if(leido2==0 && ids_planis_a.length>0){
                document.getElementById("noti_2").style.visibility = "visible";
                document.getElementById("noti_2").innerHTML = planis_no_leidos_a;
            }
            if(leido3==0 && ids_eval.length>0){
                document.getElementById("noti_4").style.visibility = "visible";
                document.getElementById("noti_4").innerHTML = eval_no_leidos;
            }
            if(leido==0){
                //document.getElementById("noti_1").style.visibility = "visible";
                //document.getElementById("noti_1").innerHTML = mensajes_no_leidos;
                displayNotis(ids_mensajes);
            } 
            
        }   
    }   
    xmlhttp.open("GET", "../funciones-sc/getNotificacion.php?id="+ id +"&perfil="+perfil, true);   
    xmlhttp.send();
  } 