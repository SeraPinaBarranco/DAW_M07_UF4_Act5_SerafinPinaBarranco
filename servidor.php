<?php
    require_once "lib/nusoap.php";
    
    
    $namespace = "http://localhost/DAW_M07_UF4_Act5_SerafinPinaBarranco/servidor.php";
    $server = new soap_server();
    $server->configureWSDL("MyWebService", $namespace);
    $server->schemaTargetNamespace = $namespace;
    $server->soap_defencoding = 'UTF-8';
    
    //Costruir los metodos que devolveran los resultados
    function holaMundo()
    {
        return "hola mundo";
    }
    
    function miscategorias()
    {
        require_once "conexion.php";
        $las_categorias = array();
        $conn = mysqli_connect($server, $user, $pass, $db);

        $query = "SELECT * FROM categoria";
        $cat = mysqli_query($conn,$query);
        
        while ($categoria = mysqli_fetch_assoc($cat)) {
            $las_categorias[] = $categoria;
        }

        mysqli_close($conn);
        return $las_categorias;
    }

    function misproductos($id)
    {
        require_once "conexion.php";
        $los_productos = array();
        $conn = mysqli_connect($server, $user, $pass, $db);

        $query = "SELECT * FROM productos WHERE categoria = $id";
        $prod = mysqli_query($conn,$query);
        
        while ($producto = mysqli_fetch_assoc($prod)) {
            $los_productos[] = $producto;
        }

        mysqli_close($conn);
        return $los_productos;
    }

    $server->register(
        'holaMundo',
        array(),
        array('return'=>'xsd:string'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'Mi primer metodo Hola Mundo'
    );

    // Definicion de CATEGORIAS
    $server->wsdl->addComplexType(
        'Categorias',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'identificador' =>  array('name'=>'identificador','type'=>'xsd:int'),
            'nombre' => array('name'=>'nombre','type'=>'xsd:string')
        )
    );

    $server->wsdl->addComplexType(
        'ArrayCategorias',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
            array('ref'=>'SOAP-ENC:arrayType',
            'wsdl:arrayType'=>'tns:Categorias[]')),
        'tns:Categorias'
    );

    $server->register(
        'miscategorias',
        array(),
        array('return'=>'tns:ArrayCategorias'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'Metodo que devuelve las categorias de productos'
    );

    //Definicion de PRODUCTOS
    $server->wsdl->addComplexType(
        'Productos',
        'complexType',
        'struct',
        'all',
        '',
        array(
            'id_producto' =>  array('name'=>'id_producto','type'=>'xsd:int'),
            'nombre_p' => array('name'=>'nombre_p','type'=>'xsd:string'),
            'categoria' =>  array('name'=>'categoria','type'=>'xsd:int')
        )
    );

    $server->wsdl->addComplexType(
        'ArrayProductos',
        'complexType',
        'array',
        '',
        'SOAP-ENC:Array',
        array(),
        array(
            array('ref'=>'SOAP-ENC:arrayType',
            'wsdl:arrayType'=>'tns:Productos[]')),
        'tns:Productos'
    );

    $server->register(
        'misproductos',
        array(),
        array('return'=>'tns:ArrayProductos'),
        $namespace,
        false,
        'rpc',
        'encoded',
        'Metodo que devuelve los productos filtrados por su ID'
    );

    $server->service(file_get_contents("php://input"));
?>
<!-- 
array(
		array('ref'=>'SOAP-ENC:arrayType', 'wsdl:arrayType'=>'tns:Serie2[]')),
	'tns:Serie2' -->