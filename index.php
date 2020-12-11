<!DOCTYPE html>
<html lang="es">

<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">    
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
    <meta name="description" content="Ticket de atencion de 9 am a 1 pm">
    <meta name="Keywords" content="ticket de atencion UGEL - Cusco, UGEL"/>
    <meta name="author" content="UGEL CUSCO - area de Informatica /Aniad J.M.">

    <title>MiTicket - UGEL CUSCO</title>
    <link rel="icon" href="favicon.ico">    
  
    <link href="estilos.css" rel="stylesheet">
     <!-- Js -->
     <script src="funciones.js"></script>
    

</head>

<body>

    <div class="container">
                <div class="form-style-5">
                    <form   action="generarticket.php" method="post" >
                    <fieldset>
                    <legend><span class="number">
                    <?php 
                    //Sacar numero de ticket del sistema
                    require_once("conexion.php");
                    
                    // coonsulta nro ticket
                    $sql = "SELECT ID FROM ticket_e WHERE ID=(SELECT MAX(ID) FROM ticket_e)";
                    $result = $conn->query($sql);

                if ($result->num_rows > 0) 
                {
                    while($row = $result->fetch_assoc()) {
                        echo "" . $row["ID"];
                      }
                
                } else {
                echo "UG";
                }
                $conn->close();

                    ?>
                    </span> Ticket de Atención - UGEL Cusco</legend>
                    <label for="nombre">Nombre:</label>
                    <input type="text" name="nombre" placeholder="Ingrese su nombre" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" placeholder="Ingrese su Email *" required>                 
                    <label for="dni">DNI:</label>
                    <input type="text"  onkeypress='validate(event)' name="dni" placeholder="Ingrese su DNI *" required>   
                    <label for="telf">Telefono:</label>
                    <input type="text" onkeypress='validate(event)' name="telf" placeholder="Ingrese su nro telefonico *" required> 
                    <label for="area_atencion">Area de atención:</label>

                    
                    <select id="area_atencion" name="area_atencion">                    
                        <optgroup   label="Areas Disponibles">                                        
                    <option value="1">SIAGI</option>
                    <option value="2">Mesa de partes</option>
                    <option value="3">Secretaria General</option>
                    <option value="4">Tesoreria</option>
                    <option value="5">Boletas</option>
                    <option value="6">Personal</option>
                    <option value="7">Remuneración</option>
                    <option value="8">Asesoria Juridica</option>                   
                    <option value="9">Abastecimientos</option>
                    <option value="10">Escalafón</option>
                    <option value="11">Gestión institucional</option>
                    <option value="12">OCI</option>
                    <option value="13">Contabilidad</option>                                      
                    </optgroup>                             
                    </select>                 

                    </fieldset> 

                    <button type="submit" class="btn-success">Generar Ticket de atención</button>                 
                    </form>
            </div>
    </div>


</body>

</html>

<?php echo file_get_contents("footer.html"); ?>