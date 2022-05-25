<?php
    require_once("lib/nusoap.php");
    $client = new soapclient("http://localhost/DAW_M07_UF4_Act5_SerafinPinaBarranco/servidor.php?wsdl");

    // $result = $client->holaMundo();
    // echo "$result<br/>";   
    $result = $client->miscategorias();
    //var_dump($result);
    $id = 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Productos</title>
</head>
<body>
    <?php
        echo "
            <style>
                table, th, td {
                    border: 1px solid black;
                    border-collapse: collapse;
                    }
            </style>        
        ";
        
    ?>
    <fieldset>
        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
            <label for="categoria"> Categorias de productos
                <select name="categoria" id="categoria">
                    <option value="0" default> --- </option>
                    <!-- Cargar los valores del WS -->
                    <?php
                    for ($i=0; $i < count($result); $i++) { 
                        echo "<option value='". $result[$i]->identificador ."'>" . $result[$i]->nombre . "</option>";
                        
                    }
            
                    ?>
                </select>
            </label>
            <input type="submit" value="Consultar" name="consultar">
        </form>


    </fieldset>

    

            <div>
                <?php
                        if(!empty($_POST['consultar'])){
                            $post =$_POST['consultar'];
                            $id = $_POST['categoria'];
                            $productos = $client->misproductos($id);
                            if(count($productos) == 0){
                                echo "<p> Esta categoria carece de productos</p>";
                            }else{
                                echo "
                                    <h3>Estos son los productos de la la categoria</h3>
                    
                                    <table style='border:1px solid black; '>
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                ";
                                    for ($i=0; $i < count($productos); $i++) { 
                                        echo "<tr>";
                                        echo "<td style='border:1px solid black;padding-bottom: 5px; padding-top: 5px; color: blue'><b>" . $productos[$i]->nombre_p . "</td>";
                                        echo "</tr>";
                                    }
                                    
                                }
                        }else{
                            echo "";
                        };
                    ?>
                    </tbody>
                </table>
                        
            </div>
      
</body>
</html>

