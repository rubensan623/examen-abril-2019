
<!DOCTYPE html>
<html>
<head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta property="og:image" content="https://drive.google.com/uc?id=0BxTe_c1GIOkoaFpkZlNrR0tta0E&export=view" />
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta/css/bootstrap.min.css" integrity="sha384-/Y6pD6FV/Vv2HJnA6t+vslU6fwYXjCFtcEpHbNJ0lyAFsXTsjBbfaDjzALeQsN6M" crossorigin="anonymous">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
        <style type="text/css">
            h1{
                margin:0px;
                padding:0px;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <h1 class="text-center p-3">Comparativa de Búsquedas</h1>
            <form class="form-horizontal" role="form" method="POST" name="procesar" id="procesar" action="index.php" onsubmit="return Procesar(this);">
                <div class="card border-info">
                    <div class="card-header bg-info text-center text-light">
                        Texto de la Búsqueda:
                    </div>
                    <div class="card-body">
                        <input type="text" class="form-control" name="textobuscar" id="textobuscar" value="" placeholder="Ingrese el texto de su búsqueda" autofocus>
                    </div>
                    <div class="card-header bg-info text-center text-light">
                        Seleccione los buscadores
                    </div>
                    <div class="card-body" style="text-align:center">
                        <input type="checkbox" value="Yahoo"  name="Yahoo" id="Yahoo">&nbsp;Yahoo
                        <input type="checkbox" value="Google"  name="Google" id="Google">&nbsp;Google
                        <input type="checkbox" value="Bing"  name="Bing" id="Bing">&nbsp;Bing
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-success" name="btn-submit" id="btn-submit" >
                            <i class="fa fa-search"></i> Procesar
                        </button>
                        <script language='JavaScript'>
                        function Procesar (Boton){
                            if (document.all.textobuscar.value == ""){
                                alert("Debe ingresar el texto a buscar");
                                return false;
                            }else{
                                if (document.all.Yahoo.checked || document.all.Google.checked || document.all.Bing.checked){
                                    Boton.hidden=true;
                                    Boton.disabled=true;
                                    return true;
                                }else{
                                    alert("Debe seleccionar al menos un búscador");
                                    return false;
                                }
                            }                           
                        }

                        </script>
                    </div>
                </div>
            </form>
        </div>
<?php 
require_once("./clases/autoload.php");

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
    $arr_textobuscar = explode(",",str_replace(" ",",",trim($_POST["textobuscar"])));
 
    foreach ($arr_textobuscar as $x=> $textobuscar){

        $compador = new \ComparaBuscadoresWeb\CompadorResultadosBuscadores();

        if (isset($_POST['Yahoo']) && !empty($_POST['Yahoo'])) {
            $_yahoo = new \ComparaBuscadoresWeb\_Yahoo();
            $compador->addBuscador($_yahoo);
        }
        if (isset($_POST['Google']) && !empty($_POST['Google'])) {
            $_Google = new \ComparaBuscadoresWeb\_Google();
            $compador->addBuscador($_Google);
        }
        if (isset($_POST['Bing']) && !empty($_POST['Bing'])) {
            $_Bing = new \ComparaBuscadoresWeb\_Bing();
            $compador->addBuscador($_Bing);
        }

        $Resultado = $compador->CompararBusquedas($textobuscar);

        ?>
        <div class="container">
            <h1 class="text-center p-3">Resultados para <?php echo $textobuscar ?></h1>
                <div class="card border-info">
                    <table width=80% cellspacing="0">
                        <tr>
                            <td><div class="card-header bg-info text-center text-light">Ganador(es):</div></td>
                            <td><div class="card-body"><?php echo implode(",", $Resultado["ganadores"]);?></div></td>
                        </tr>
                        <tr>
                            <td><div class="card-header bg-info text-center text-light">Mayor Cantidda de resultados:</div></td>
                            <td><div class="card-body"><?php echo $Resultado["valor_ganador"];?></div></td>
                        </tr>
                        <?php foreach ($Resultado["resultado_buscadores"] as $i=> $buscador){ ?>
                        <tr>
                            <td><div class="card-header bg-info text-center text-light">Resultado Buscador:</div></td>
                            <td><div class="card-body">Buscador: <?php echo $buscador["nombre"] ?><br>
                                                        Estado Búsqueda: <?php echo $buscador["estado"] ?><br>
                                                        Resultados Obtenidos: <?php echo $buscador["valor"] ?>
                                </div>
                            </td>
                        </tr>
                        <?php }?>
                    </table>
                </div>
                <p>&nbsp;</p><p>&nbsp;</p><p>&nbsp;</p>
        </div>
        <?php 
    }
}
?>
    </body>
</html>





