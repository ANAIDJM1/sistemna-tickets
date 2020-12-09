<?php
require_once("conexion.php");


        //Obtiene datos de formulario
        $DNI=$nombrec=$mail=$telf=$id_ofi="";
        $DNI =$_POST["dni"]; 
        $nombrec=$_POST["nombre"]; 
        $mail=$_POST["email"]; 
        $telf=$_POST["telf"]; 
        $id_ofi=$_POST["area_atencion"]; 

        
            //1.- Comprueba si la persona ha sacado ticket en el dia de hoy

              //obtemos el registro de la persona y la fecha de hoy 
             $query_search = "SELECT DNI_person, fecha FROM ticket_e WHERE DNI_person = '".$DNI."' AND fecha=CURDATE()";
             $result_search = $conn->query($query_search);

            //A) si existe, envia alerta que ya no puede sacar ticket hoy
            if ($result_search ->num_rows > 0) {              
              echo"<script>
              alert('Usted ya no puede sacar más tickets el dia de hoy!Intente mañana, por favor!');
              location=history.back();
              </script>";                              
            }
            else
            {
              //335 tickets x dia - + informe por oficina de atencion
             
            //B) sino existe registro de la persona en el dia, agrega a la persona para sacar ticket en un nuevo dia

              //obtener fecha y hora de atencion del ultimo registro              
              //si la(hora_fecha >= 12:50), coge la fecha de atencion, agrega un dia
              //y pone 9:00 , sino a la ultima fecha_hora + 10 min.
                $sql0 ="SELECT hora_fecha_atencion FROM ticket_e WHERE ID=(SELECT MAX(ID) FROM ticket_e)";
                $ultima_atencion_query = $conn->query($sql0);
               
                  while($fila = $ultima_atencion_query->fetch_assoc())
                  {
                    $ultima_atencion = "'".$fila["hora_fecha_atencion"]."'";
                    
                  }
                  
                  echo "<script>console.log(".$ultima_atencion.");</script>";

                  $splitTimeStamp = explode(" ", $ultima_atencion);
                  $hora = $splitTimeStamp[1];
                 
                  echo $hora;

                  //CREAR FUNCION PARA aumentar cada 15 min
                                   
                  $endTime = strtotime('+15 minutes', strtotime($hora));
                  $horacita = date('h:i:s', $endTime);    
                  echo $horacita;                           

                  /*if($endTime>='12:500')
                  {
                    $splitTimeStamp2 = explode(" ", $ultima_atencion);
                  $fechal = $splitTimeStamp[0];                

                  $fecha2= strtotime( '+1 day', strtotime($fechal));
                  $fecha_cita = $fecha2." "."09:00:00";

                  }
                  else
                  {
                    $fecha_cita = $fechal." ".$endTime;
                  }*/



                  $fecha_cita=strtotime('2020-12-10 09:15:00');


                         
              $sql = "INSERT INTO ticket_e (DNI_person, nombre_persona, mail, telf,fecha,id_ofi,hora_fecha_atencion)
              VALUES ( $DNI,'$nombrec','$mail',$telf,CURDATE(),$id_ofi,'$fecha_cita');";

            if ($conn->query($sql) === TRUE) {
                echo "<script>console.log('Generacion de ticket con exito!');</script>";

                   //Generar TICKET

                   $sql_ID_ticket = "SELECT ID FROM ticket_e WHERE ID=(SELECT MAX(ID) FROM ticket_e)";
                   $resultid = $conn->query($sql_ID_ticket );

                   $ID_ticket="";

               if ($resultid->num_rows > 0) 
               {
                   while($row = $resultid->fetch_assoc()) {
                       $ID_ticket= $row["ID"];
                     }
               
               } else {
               echo "<script> console.log('Error de nro de ticket');</script>";
               }  
               
               $sql3 ="SELECT nombreofi FROM oficina WHERE Id_oficina=$id_ofi";
               $oficinas = $conn->query($sql3);
              
                 while($filao = $oficinas->fetch_assoc())
                 {
                   $oficinaa = "'".$filao["nombreofi"]."'";
                   
                 }


                  echo "<head>
                  
                  
                  <meta charset='utf-8'>
                  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
                  <meta name='description' content='Ticket de atencion de 9 am a 1 pm'>
                  <meta name='Keywords' content='ticket de atencion UGEL - Cusco, UGEL'/>
                  <meta name='author' content='UGEL CUSCO - area de Informatica /Aniad J.M.'>
              
                  <title>Ticket Generado- UGEL CUSCO</title>             
                  
                  <link href='http://www.ugelcusco.gob.pe/miboleta/vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet'>
                  <link href='estilos.css' rel='stylesheet'>              
                   <script src='funciones.js'></script>

                  <head>
                  <body>
                  <center>
                  <h1>Ticket Electronico - UGEL CUSCO 2020 </h1></center>  
                  <div class='impresion'>
                  <p>Nro ticket:" .$ID_ticket."</p>
                  <p>para el Sr. /Sra. : ".$nombrec." </p>

                 

                  <p>Area de Atención : ".$oficinaa."</p>
                  <p>Hora de atención : ".$fecha_cita."</p>

                  <center><h3>Aturoizado por UGEL</h3></center>
                  <p>AQUI VA EL CODIGO QR</p>

                  <p>Se envio el ticket a su correo con exito!</p>
                  <button onclick='window.print()' class='btn btn-success btn-lg btn-block'>Imprimir ticket</button>
                  </div>
                  </body>";               


              } else {
                echo "Error - no se inserto: " . $sql . "<br>" . $conn->error;
              }

            }
                


        $conn->close();
?>