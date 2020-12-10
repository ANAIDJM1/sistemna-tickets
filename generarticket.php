<meta charset='utf-8'>
<?php
//date_default_timezone_set("UTC");//Zona horaria de Peru
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

                    //determinacion de la fecha de cita---------------------------------

                      //$startTime = date("Y-m-d H:i:s");
                        $startTime = '2020-12-10 09:00:00';

                        //display the starting time
                        echo '> '.$startTime . "<br>";

                        //adding 10 minutes
                        $convertedTime = date('Y-m-d H:i:s', strtotime('+1 day', strtotime($startTime)));

                        $convertedTime2 = date('Y-m-d H:i:s', strtotime('+10 minutes', strtotime($convertedTime)));

                        //display the converted time
                        echo '> '.$convertedTime.'<br>';
                        echo '> '.$convertedTime2;



                  }*/



                  //$fecha_cita=date('l jS \of F Y h:i:s A' ,'2020-12-10 09:15:00');
                  $fecha_cita=date('Y-m-d H:i:s' ,'2020-12-10 09:15:00');

                         
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
                  
                  <meta http-equiv='X-UA-Compatible' content='IE=edge'>
                  <meta name='viewport' content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no' />
                  <meta name='description' content='Ticket de atencion de 9 am a 1 pm'>
                  <meta name='Keywords' content='ticket de atencion UGEL - Cusco, UGEL'/>
                  <meta name='author' content='UGEL CUSCO - area de Informatica /Aniad J.M.'>
              
                  <title>Ticket Generado- UGEL CUSCO</title>             
                  
                  <link href='http://www.ugelcusco.gob.pe/miboleta/vendor/bootstrap/css/bootstrap.min.css' rel='stylesheet'>
                  <link href='estilos.css' rel='stylesheet'>              
                   <script src='funciones.js'></script>
                   <script src='qr.js'></script>
                   <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js'></script>

                  <head>
                  <body>
                  <center>
                  <h1>Ticket Electronico - UGEL CUSCO 2020 </h1></center>  
                  <div class='impresion'>
                  <p>Nro ticket:" .$ID_ticket."</p>
                  <p>para el Sr. /Sra. : ".$nombrec." </p>

                 

                  <p>Area de Atención : ".$oficinaa."</p>
                  <p>Hora de atención : ".$fecha_cita."</p>

                  <center><h3>Autorizado por UGEL</h3></center>
                  <p>";

                   
       
                   echo " <input id='qrtext' type='hidden' value='".$ID_ticket."F6B59C44B3E79DB40DA02455B3A54A5CB82D47102E3CCD8394C4E92D944770EA"."$id_ofi"."' />
                   <center><div id='qrcode'></div></center>";

                   

                   //Enviar a mail
/*
$to = .$mail.;
$subject = "UGEL CUSCO - Ticket de atencion";

$att = file_get_contents( 'generated.pdf' );
$att = base64_encode( $att );
$att = chunk_split( $att );

$BOUNDARY="anystring";

$headers =<<<END
From: Your Name <abc@gmail.com>
Content-Type: multipart/mixed; boundary=$BOUNDARY
END;

$body =<<<END
--$BOUNDARY
Content-Type: text/plain

See attached file!

--$BOUNDARY
Content-Type: application/pdf
Content-Transfer-Encoding: base64
Content-Disposition: attachment; filename="your-file.pdf"

$att
--$BOUNDARY--
END;

mail( $to, $subject, $body, $headers );

                  echo "<p>Se envio el ticket a su correo!</p>"; 

*/

//------------------------------------------------------------------------

                  
                   echo "     
                  <button onclick='window.print()' class='btn btn-success btn-lg btn-block'>Imprimir ticket</button>
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
        //https://disenowebakus.net/llevando-datos-de-la-base-mysql-a-las-paginas-php.php
       
?>