<?php
function Conectar()
{
    // $Servidor = "srv1440.hstgr.io";
    // $Usuario = "u772323640_administrador";
    // $Pwd = "AniaChiquita1";
    // $DataBase = "u772323640_byd";
    $Servidor = "localhost";
    $Usuario = "root";
    $Pwd = "";
    $DataBase = "byd";
    $Con = mysqli_connect($Servidor, $Usuario, $Pwd, $DataBase);
    return $Con;
}
function Ejecutar($Con, $SQL)
{
    $Result = mysqli_query($Con, $SQL);
    return $Result;
}
function Procesar()
{

}
function Desconectar($Con)
{
    $Valor = mysqli_close($Con);
    return $Valor;
}
?>