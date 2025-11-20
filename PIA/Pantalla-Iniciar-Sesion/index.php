<?php 

session_start();

if (!isset($_POST['correo'], $_POST['contra'])) {
    header("Location: index.html");
    exit();
}

$correo=$_POST['correo'];
$contra=$_POST['contra'];

$conexion=mysqli_connect("127.0.0.1","root","2707","mydb"); 

$consulta="SELECT*FROM usuario where correo='$correo' and contra='$contra'";
$resultado=mysqli_query($conexion,$consulta);

$filas=mysqli_num_rows($resultado);

if ($filas) {

    $usuario = mysqli_fetch_assoc($resultado);
    
    $_SESSION['IdUsuario'] = $usuario['IdUsuario'];
    $_SESSION['Nombre'] = $usuario['Nombre'];
    $_SESSION['Fecha_Nacimiento'] = $usuario['Fecha_Nacimiento'];
    $_SESSION['Correo'] = $usuario['Correo'];
    $_SESSION['Contra'] = $usuario['Contra'];
    $_SESSION['URL_Foto_perf'] = $usuario['URL_Foto_perf'];
    $_SESSION['IdTarjeta'] = $usuario['IdTarjeta'];
    $_SESSION['IdRol'] = $usuario['IdRol'];


    if ($usuario['IdRol'] == 1) {
        echo "<script>
            alert('Se inició sesión exitosamente');
            window.location.href = '/PAGINA-GIMNASIO/Pantalla-Id-Cliente/index.php';
        </script>";
    } elseif ($usuario['IdRol'] == 2) {
        echo "<script>
            alert('Se inició sesión exitosamente');
            window.location.href = '/PAGINA-GIMNASIO/ADMIN-avisos/avisos.html';
        </script>";
    }

} else {
    echo "<script>
            alert('Esta sesion no existe');
            window.location.href = 'index.html';
        </script>";
}

mysqli_free_result($resultado);
mysqli_close($conexion);

?>