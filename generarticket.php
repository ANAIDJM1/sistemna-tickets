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
              //APTO------------------------------------------------------PARA SACAR TICKET-------------------------------------------------------------------------
                $sql0 ="SELECT hora_fecha_atencion,id_ofi FROM ticket_e WHERE ID=(SELECT MAX(ID) FROM ticket_e WHERE id_ofi=$id_ofi)";
                 $ultima_atencion_query = $conn->query($sql0);

                  if ($ultima_atencion_query->num_rows > 0) 
                  {
                    while($fila = $ultima_atencion_query->fetch_assoc())
                      {
                        $ultima_atencion = $fila["hora_fecha_atencion"];                                        
                      }  
                  }
                  else
                  {
                    //fecha inicial predet- desde el dia q se ponga en funcionamiento.
                    $ultima_atencion = '2020-12-13 09:00:00';
                  }

                  function retornafecha($fecha_inicial)
                  {
                        $startTime = $fecha_inicial;
                      echo "<script>console.log(". $startTime.");</script>";   

                      //CREAR FUNCION PARA aumentar cada 15 min   
                      $hora_fecha_anterior = explode(" ",$startTime);
                      

                      if($hora_fecha_anterior[1]>="12:45:00")
                      {
                      $endTime0 = strtotime('+1 day', strtotime($startTime));                  
                      $endTime1 = explode(" ",$endTime0);
                      $endTime2 = date('Y-m-d',$endTime1[0]);                 
                      $hora = " 09:00:00";
                      $horacita = $endTime2.$hora; 
                      echo "<script>console.log('". $horacita."');</script>"; 
                      return $horacita;             

                      }
                      else
                      {
                        $tiempo0 = strtotime('+15 minutes', strtotime($startTime)); 
                        $horacita = date('Y-m-d H:i:s', $tiempo0);                                              
                        echo "<script>console.log('". $horacita."');</script>";
                        return $horacita;   
                      }

                  }

                  $fecha_cita=retornafecha($ultima_atencion);

                         
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
                  <meta http-equiv='Content-Type' content='text/html; charset=utf-8'>    

                  <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
                  <meta name='description' content='Ticket de atencion de 9 am a 1 pm'>
                  <meta name='Keywords' content='ticket de atencion UGEL - Cusco, UGEL'/>
                  <meta name='author' content='UGEL CUSCO - area de Informatica /Aniad J.M.'>
              
                  <title>Ticket Generado- UGEL CUSCO</title>    
                  <link rel='icon' href='favicon.ico'> 
                  <link href='estilos.css' rel='stylesheet'>              
                   <script src='funciones.js'></script>
                   <script src='qr.js'></script>                  
                  

                  <head>
                  <body>
                  <center>
                  <div id='eltikett'>
                    <h1>Ticket Electronico - UGEL CUSCO 2020 </h1></center>  
                    <div class='impresion'>
                      <p>Nro ticket:" .$ID_ticket."</p>
                      <p>para el Sr. /Sra. : ".$nombrec." </p>
                      <p>Area de Atención : ".$oficinaa."</p>
                      <p>Hora de atención : ".$fecha_cita."</p>
                      <center><h3>Autorizado por UGEL</h3></center>
                      <p>";                   
       
                   echo "<div> 
                    <input id='qrtext' type='hidden' value='".$ID_ticket."F6B59C44B3E79DB40DA02455B3A54A5CB82D47102E3CCD8394C4E92D944770EA"."$id_ofi"."' />
                      <center><div id='qrcode'></div></center>
                        </div></div>";

                   echo "</br>";

//-------------------------Enviar a mail
$to = $mail;

$subject = 'Ticket - UGEL';

$headers =  'MIME-Version: 1.0' . "\r\n"; 
$headers .= 'From: UGEL CUSCO <ticket@ugelcusco.gob.pe>' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

$message = '<html><body>';

$message .= '<h1>Ticket Electronico - UGEL CUSCO 2020 </h1>';
$message .= "<p>Nro ticket:" .strip_tags($ID_ticket)."</p>";
$message .= "<p>para el Sr. /Sra. : ".strip_tags($nombrec)." </p>";
$message .= "<p>Area de Atención : ".strip_tags($oficinaa)."</p>";
$message .= "<p>Hora de atención : ".strip_tags($fecha_cita)."</p>";
$message .= '<center><h3>Autorizado por UGEL</h3></center>';
$message .= "<p> Codigo de autenticación: ".strip_tags($ID_ticket)."F6B59C44B3E79DB40DA02455B3A54A5CB82D47102E3CCD8394C4E92D944770EA".strip_tags($id_ofi)."<p/>";
$message .= "<center><div id='qrcode'></div></center>";



if(mail($to, $subject, $message, $headers)){
  echo 'Su mail se envio correctamente';
} else{
  echo 'No se pudo enviar el mail..';
}




//------------------------------------------------------------------------

                  
                   echo "     
                  <button onclick='window.print()' class='btn-success'>Imprimir ticket</button>
                  </div>


                  <script>
        var qrcode = new QRCode('qrcode');
            function makeCode () {		
              var elText = document.getElementById('qrtext');
              
              if (!elText.value) {
                
                elText.value='UGEL 2020 AJM';
                return;
              }
              
              qrcode.makeCode(elText.value);
            }

            makeCode();
              $('#qrtext').
                on('blur', function () {
                  makeCode();
                }).
                on('keydown', function (e) {
                  if (e.keyCode == 13) {
                    makeCode();
                  }
                });
                  </script>
                  </body>";               


              } 
              else {
                echo "Error - no se inserto: " . $sql . "<br>" . $conn->error;
              }

            }
                


        $conn->close();
      
       
?>