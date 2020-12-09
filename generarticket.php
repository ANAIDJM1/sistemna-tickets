<?php
date_default_timezone_set('UTC');
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

            //si existe, envia alerta que ya no puede sacar ticket hoy
            if ($result_search ->num_rows > 0) {              
              echo"<script>
              alert('Usted ya no puede sacar más tickets el dia de hoy!Intente mañana, por favor!');
              location=history.back();
              </script>";                              
            }
            else
            {
             
            //sino existe registro de la persona en el dia, agrega a la persona para sacar ticket en un nuevo dia

              //obtener fecha y hora de atencion del ultimo registro              
              //

                         
              $sql = "INSERT INTO ticket_e (DNI_person, nombre_persona, mail, telf,fecha,id_ofi)
              VALUES ( $DNI,'$nombrec','$mail',$telf,CURDATE(),$id_ofi);";

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
               

                  echo "<head>
                  <h1>Ticket Electronico - UGEL CUSCO 2020 </h1>                  
                  <head>
                  <body>
                  <p>Nro ticket:" .$ID_ticket."</p>
                  <p>para el Sr. /Sra. : </p>
                  <p>Area de Atención : </p>
                  <p>Hora de atención : </p>

                  <p>Aturoizado por UGEL</p>
                  <p>AQUI VA EL CODIGO QR</p>

                  <p>Se envio el ticket a su correo con exito!</p>
                  
                  boton: imprimir
                  </body>";               


              } else {
                echo "Error - no se inserto: " . $sql . "<br>" . $conn->error;
              }

            }
                


        $conn->close();
?>