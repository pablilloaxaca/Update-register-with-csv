<?php
	setlocale(LC_ALL, 'es_MX');
	require_once '../header.php';
?>
<div class="main-content" autoscroll="true" bs-affix-target="" init-ripples="">
	<section class="tables-data">
		<div class="page-header">
			<h1><i class="md md-list"></i>     Lista de Clientes   </h1>
			<?php
				echo $msupda;
			?>      
        </div>
        <div class="card">
	        <form action='<?php echo $_SERVER["PHP_SELF"];?>' method='post' enctype="multipart/form-data">
   Importar Archivo : <input type='file' name='sel_file' size='20'>
   <input type='submit' name='submit' value='submit'>
  </form>
        </div>
    </section>
    
</div>  
<?php
// set local variables
$connect = mysql_connect("localhost","dbuser","dbpass") or die('Could not connect: ' . mysql_error());

// connect to mysql and select database or exit 
mysql_select_db("table", $connect);


if(isset($_POST['submit'])){
        //Aquí es donde seleccionamos nuestro csv
         $fname = $_FILES['sel_file']['name'];

         $chk_ext = explode(".",$fname);
         
         if(strtolower(end($chk_ext)) == "csv")
         {
             //si es correcto, entonces damos permisos de lectura para subir
             $filename = $_FILES['sel_file']['tmp_name'];
             $handle = fopen($filename, "r");

			 // loop content of csv file, using comma as delimiter
			while (($data = fgetcsv($handle, 1000, ",")) !== false) {
				$address = $data[0];
				$cantidad = $data[7];
				$factura = $data[8];
				$fechap = $data[9];
				$pag = $data[10];
				$query = 'SELECT * FROM pagos';
				if (!$result = mysql_query($query)) {
					continue;
				}
			
				if ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
					// entry exists update
					$query = "UPDATE pagos SET cantidad ='$cantidad', factura='$factura', fecha_pago='$fechap', pagado='$pag'
					WHERE idmensual = '$address'";
					
					mysql_query($query);
					if (mysql_affected_rows() <= 0) {
					
					// no rows where affected by update query
					}
					} else {
					// entry doesn't exist continue or insert...
					}
				}
			}
             //cerramos la lectura del archivo "abrir archivo" con un "cerrar archivo"
             fclose($handle);
             echo "Importación exitosa!";
             
         }
         else
         {
            //si aparece esto es posible que el archivo no tenga el formato adecuado, inclusive cuando es cvs, revisarlo para             //ver si esta separado por " , "
             echo "Archivo invalido!";
         }   
?>

<?php require_once '../footer.php'; ?>   