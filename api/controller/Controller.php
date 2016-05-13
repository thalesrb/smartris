<?php

namespace controller;

use Silex\Application;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller
{
	public $model;
	public $methods_permitted;

    public function __construct($model)
    {
    	$this->model = $model;
    }

    /**
     * Executa o verbo HTTP correspondente
     *
     * @param Request $request
     * @param Application $app
     * @param unknown $codigo
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function run(Request $request, Application $app, $codigo)
    {
        $getRequest = (string) $request->getRealMethod();
        $getRequest = strtolower($getRequest);

        $codigo = $this->espace_chars($codigo);

        if ( isset($this->methods_permitted['get']) && $getRequest == 'get' ) {
            return $this->get($request, $app, $codigo);
        }

        if ( isset($this->methods_permitted['post']) && $getRequest == 'post' ) {
            return $this->post($request, $app);
        }

        if ( isset($this->methods_permitted['put']) && $getRequest == 'put' ) {
            return $this->put($request, $app, $codigo);
        }

        if ( isset($this->methods_permitted['delete']) && $getRequest == 'delete' ) {
            return $this->delete($request, $app, $codigo);
        }

        return $app->json("Method Not Allowed", 405);
    }

    /**
     * Define quais os verbos HTTP são permitidos nessa API
     *
     * @param unknown $methods
     */
    public function set_methods_permitted($methods)
    {
    	$this->methods_permitted = array_combine($methods, $methods);
    }

    /**
     * Executa o GET
     *
     * @param Request $request
     * @param Application $app
     * @param unknown $codigo
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function get(Request $request, Application $app, $codigo)
    {
    	// retorna um lote de registros
        if ( empty($codigo) ) {
            $pagina = $request->query->get('pagina');
            $limite = $request->query->get('limite');

            $url_params = $request->query->get('filtro');

            $this->setFiltrosGenerico($url_params);

            if ( empty($limite) ) {
            	$limite = MAX_ITENS;
            }

            if ( empty($pagina) ) {
            	$pagina = 1;
            }

            $toJson = $this->getList($limite, $pagina);
            return $app->json($toJson, 200);
        }


        // retorna apenas um registro
        $toJson = $this->getData($codigo);

        $status_code = 200;
        if ( $toJson === false ) {
            $status_code = 404;
            $toJson = array();
        }

        return $app->json($toJson, $status_code);
    }

    /**
     * Executa o POST
     *
     * @param Request $request
     * @param Application $app
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function post(Request $request, Application $app)
    {
        $post = $request->request->all();

        $toJson = $this->model->insert($post);

        return $app->json($toJson, 200);
    }

    /**
     * Executa o PUT
     *
     * @param Request $request
     * @param Application $app
     * @param unknown $codigo
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function put(Request $request, Application $app, $codigo)
    {
        $put = $request->request->all();

        $result = $this->model->update($put, $codigo);

        $status_code = ( $result === false ) ? 404 : 200;

        if ( $result !== false ) {
            $toJson = $this->getData($codigo);;
        }

        return $app->json($toJson, $status_code);
    }

    /**
     * Executa o DELETE
     *
     * @param Request $request
     * @param Application $app
     * @param unknown $codigo
     */
    public function delete(Request $request, Application $app, $codigo)
    {
        $this->model->delete($codigo);
        return $app->json(array(), 200);
    }

    /**
     * Faz o fetch dos dados
     *
     * @param unknown $codigo
     * @return boolean|unknown
     */
    private function getData($codigo)
    {
        $res = $this->model->getData($codigo);

        $total_itens = $this->model->dao->num_rows($res);

        if ( $total_itens < 1 ) {
            return false;
        }

        $row = $this->model->dao->fetch($res);

        // cria um array com os campos que foram definidos
        foreach ($this->model->_campos as $campo_bd => $campo) {
            $dados[$campo] = $row[$campo_bd];
        }

        return $dados;
    }

    /**
     * Faz o fetch dos dados
     *
     * @param number $limite
     * @param number $pagina
     * @return number[]|string[]|unknown
     */
    private function getList($limite = 15, $pagina = 1)
    {
        $res = $this->model->getList($limite, $pagina);

        if ( $res === false || $this->model->dao->num_rows == 0) {
            $dados = array("total" => 0, "itens" => "");
            return $dados;
        }

        $primary_key = $this->model->primary_key;

        $dados["total"] = $this->model->dao->total_itens;

        while ($row = $this->model->dao->fetch($res)) {

            $chave = ( !is_array($primary_key) ) ? $row[$primary_key] : $this->primary_key_multipla($primary_key, $row);

            // cria um array com os campos que foram definidos
            foreach ($this->model->_campos as $campo_bd => $campo) {

                $dados["itens"][$chave][$campo] = $row[$campo_bd];
            }
        }

        return $dados;
    }

    /**
     * Monta um array com os filtros que foram passados
     * Verifica se o campo passado faz parte da tabela
     *
     * @param unknown $filtros
     * @return boolean
     */
    private function setFiltrosGenerico($filtros)
    {
        if ( !isset($filtros) ) {
            return false;
        }

        foreach ($filtros as $campo => $dados) {

            if ( !isset($this->model->_campos[$campo]) || empty($dados["valor"]) ) {
                continue;
            }

            $operador = $this->espace_chars($dados["operador"]);

            if ( $operador == "IN" ) {
                $valor = "('" . implode("','", $dados["valor"]) . "')";
            } else {
                $valor = "'" . $this->espace_chars($dados["valor"]) . "'";
            }

            $this->model->filtros[] = "{$campo} {$operador} {$valor}";

        }

        return true;
    }

    /**
     * Escapa caracteres especiais
     *
     * @param unknown $texto
     * @return unknown|string
     */
    private function espace_chars($texto)
    {
        if ( is_array($texto) ) {
            return $texto;
        }

        return addslashes(strip_tags($texto));
    }

    /**
     * Monta um array quando a tabela tem multiplas chaves primarias
     *
     * @param unknown $chaves
     * @param unknown $dados
     */
    private function primary_key_multipla($chaves, $dados)
    {
    	foreach ($chaves as $chave) {
    		$valores[] = $dados[$chave];
    	}

    	$chave = implode("_", $valores);

    	return $chave;
    }

}
