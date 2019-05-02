<?php
	namespace ComparaBuscadoresWeb;
	class Buscador{
        var $cc;
        var $url;
        var $patron;
        var $nombre;

        public function __construct( ){
		}

        public function search( $TextoBuscar ){     
            //Configurar la URL
            $url = $this->url . $TextoBuscar;
            
            //Invocar la URL
            $this->cc = new \cURL\cURL();
            $page = $this->cc->send( $url );

            if( $this->cc->getHttpStatus()==200 ){
                // Si hay una correcta conexión, se obtuvo la página
                
				preg_match_all($this->patron, $page, $matches, PREG_SET_ORDER);
                
                $result = "";
                if( count($matches) > 0 ){
					$result = strip_tags(str_replace(array(" ",",","."),"",$matches[0][1]));
                }else{echo 'patron no match';}
                

                if( strlen ($result) > 0 ){ //Si hay resultados
                    $response = array(
                        'estado' 	=> 	'1',
                        'msg' 	    => 	'Busqueda OK',
                        'valor' => 	$result,
                        'nombre'       => $this->nombre
                    );
                }else{
                    $response = array(
                        'estado' 	=> 	'0',
                        'msg'   	=> 	'Busqueda Fallida',
                        'valor' => 	$result,
                        'nombre'       => $this->nombre
                    );
                }
                return $response;
            }
            else{
                $response = array(
                    'estado' 	=> 	'0',
                    'msg'   	=> 	'No se pudo conectar al Buscador',
                    'valor' =>  '',
                    'nombre'       => $this->nombre
                );
                return $response;
            }
        }	
    }

    class _Google extends Buscador{
        public function __construct() {
            $this->url = "https://www.google.com/search?q=";
            $this->patron = '/<div id="resultStats">Cerca de (.*)resultados<nobr>/';
            $this->nombre = 'Google';
        }
        public function buscar( $TextoBuscar ){ 
            return $this->search($TextoBuscar);
         }
    }
    class _Bing extends Buscador{
        public function __construct() {
            $this->url = "https://www.bing.com/search?q=";
            $this->patron = '/<div id="b_tween"><span class="sb_count">(.*) resultados<\/span><span class="ftrB">/';
            $this->nombre = 'Bing';
        }
        public function buscar( $TextoBuscar ){ 
            return $this->search($TextoBuscar);
         }
    }
    class _Yahoo extends Buscador{
        public function __construct() {
            $this->url = "https://pe.search.yahoo.com/search?p=";
            $this->patron = '/referrerpolicy="unsafe-url">Siguiente<\/a><span>(.*) resultados<\/span><\/div><\/div><\/li><\/ol><\/div><\/div><div id="right">/';
            $this->nombre = 'Yahoo';
        }
        public function buscar( $TextoBuscar ){ 
            return $this->search($TextoBuscar);
         }
    }


    class CompadorResultadosBuscadores {
        private $buscadores = array();
        private $Ganador_valor = 0;
        private $arr_resultado_buscadores = array();
        private $Ganadores = array();

        public function __construct() {}

        public function addBuscador(Buscador $buscador) {
            array_push($this->buscadores, $buscador);
        }

        public function CompararBusquedas($TextoBuscar) {
            foreach($this->buscadores as &$Buscador){
                $resultadoBusqueda = $Buscador->buscar($TextoBuscar);
                array_push($this->arr_resultado_buscadores, $resultadoBusqueda);

                if ($resultadoBusqueda["estado"]=='1'){
                    //Buscando el ganador
                    if ($resultadoBusqueda["valor"] > $this->Ganador_valor){
                        $this->Ganador_valor = $resultadoBusqueda["valor"];
                        $this->Ganadores = array($Buscador->nombre);
                    }elseif ($resultadoBusqueda["valor"] == $this->Ganador_valor){
                        //Registrando todos los empates
                        array_push($this->Ganadores,$Buscador->nombre);
                    }
                }
            }
            return array(
                'ganadores'       => $this->Ganadores,
                'resultado_buscadores' => $this->arr_resultado_buscadores,
                'valor_ganador'      => $this->Ganador_valor
            );
        }

    }
?>
