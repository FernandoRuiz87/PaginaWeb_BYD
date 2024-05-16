<?php
    function Conectar(){
        $Servidor = "127.0.0.1";
        $Usuario = "root";
        $Pwd = "";
        $DataBase = "byd";
        $Con = mysqli_connect($Servidor,$Usuario,$Pwd,$DataBase);
        return $Con;
    }
    function Ejecutar($Con,$SQL){
        $Result = mysqli_query($Con,$SQL);
        return $Result;
    }
    function Procesar(){

    }
    function Desconectar($Con){
        $Valor = mysqli_close($Con);
        return $Valor;
    }
?>