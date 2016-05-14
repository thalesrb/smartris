<?php

namespace src;

class Buscas {

    /**
     * Faz a busca dos pacientes, baseado nos filtros passados
     *
     * @param unknown $name
     * @param unknown $birthdate
     * @param unknown $address
     * @param unknown $id_card_number
     * @param number $pagina
     * @return string
     */
    public function buscar_paciente($name, $birthdate, $address, $id_card_number, $pagina)
    {
        $filtro_query = $filtros = "";

        $pagina = ( empty($pagina) ) ? 1 : $pagina;

        $query_string = "&name={$name}&birthdate={$birthdate}&address={$address}&id_card_number={$id_card_number}";

        $campos = array ();
        $campos["name"] = $name;
        $campos["address"] = $address;
        $campos["birthdate"] = $birthdate;
        $campos["id_card_number"] = $id_card_number;

        $filtro_query = $this->monta_query_busca($campos);

        $url_curl = URL_API . "pacientes?pagina={$pagina}&{$filtro_query}";

        $result = $this->executa_curl($url_curl);

        $html = "";

        if ( empty($result["total"]) ) {
            $html .= "<tr><td colspan='4' class='text-center'>Não foi encontrado nenhum resultados com a busca feita.</td></tr>";
            return $html;
        }

        foreach ( $result["itens"] as $dados ) {

            list($ano, $mes, $dia) = explode("-", $dados["birthdate"]);
            $birthdate = "{$dia}/{$mes}/{$ano}";

            $html .= "<tr>";
            $html .= "  <td><input type='radio' name='paciente' value='{$dados["id"]}'></td>";
            $html .= "  <td>{$dados["name"]}</td>";
            $html .= "  <td>{$birthdate}</td>";
            $html .= "  <td>{$dados["address"]}</td>";
            $html .= "  <td>{$dados["id_card_number"]}</td>";
            $html .= "</tr>";
        }

        $html .= "<tr>";
        $html .= "  <td colspan='5'>";
        $html .= "  <input type='submit' value='Avançar' class='btn btn-success pull-right' style='margin:20px;'>";
        $html .= "  </td>";
        $html .= "</tr>";
        $html .= $this->montaPaginacao(URL_SITE . "guia/?pagina=", $result["total"], $pagina, $query_string);

        return $html;
    }

    /**
     * Retorna as categorias tuss existentes
     * @return string
     */
    public function buscar_categorias()
    {
        $url_curl = URL_API . "tuss_tipos?limite=50";
        $result = $this->executa_curl($url_curl);

        $options = "";

        foreach ( $result["itens"] as $dados ) {
            $options .= "  <option value='{$dados["id"]}'>{$dados["tipo"]}</option>";
        }

        return $options;
    }

    /**
     * Retorna os dados do paciente passado
     *
     * @param unknown $id
     * @return string
     */
    public function buscar_dados_paciente($id)
    {
        $url_curl = URL_API . "pacientes/{$id}";
        $result = $this->executa_curl($url_curl);

        list($ano, $mes, $dia) = explode("-", $result["birthdate"]);
        $birthdate = "{$dia}/{$mes}/{$ano}";

        $html_paciente  = "";
        $html_paciente .= "<b>Nome:</b> {$result["name"]} <b>Endereço:</b> {$result["address"]}";
        $html_paciente .= "<b>Dt Nasc:</b> {$birthdate} <b>Carteirinha:</b> {$result["id_card_number"]}";

        return $html_paciente;
    }

    /**
     * Retorna os exames, baseado na busca passada
     *
     * @param unknown $busca
     * @return array
     */
    public function buscar_exames($busca, $pagina)
    {
        $campos = array();
        $campos["termo"] = $busca;
        $filtro_query = $this->monta_query_busca($campos);

        $url_curl = URL_API . "tuss_exames?pagina={$pagina}&{$filtro_query}";

        $result = $this->executa_curl($url_curl);

        foreach ($result["itens"] as $dados) {
            $dados_exames[] = array(
                    "id"     => "{$dados["id_tipo"]}_{$dados["id"]}",
                    "text"   => $dados["termo"],
            );
        }

        $arr_dados = array("total" => $result["total"], "itens" => $dados_exames );

        return $arr_dados;
    }

    /**
     * Retorna as guias cadastradas
     */
    public function buscar_guias()
    {
        $url_curl = URL_API . "guias";
        $result = $this->executa_curl($url_curl);

        $html = "";

        if ( empty($result["total"]) ) {
            $html .= "<tr><td colspan='4' class='text-center'>Ainda não foi cadastrado nenhuma guia.</td></tr>";
            return $html;
        }

        // monta um array com as guias de cada paciente
        foreach ($result["itens"] as $chave => $dados) {
            $paciente = $dados["paciente"];

            $arr_guias[$paciente]["name"] = $dados["name"];
            $arr_guias[$paciente]["id_card_number"] = $dados["id_card_number"];
            $arr_guias[$paciente]["termos"][] = $dados["termo"];
        }

        foreach ($arr_guias as $paciente => $dados) {

            $termos = implode("<br>", $dados["termos"]);

            $html .= "<tr>";
            $html .= "  <td><input type='checkbox' name='guias[]' value='{$paciente}'></td>";
            $html .= "  <td>{$dados["name"]}</td>";
            $html .= "  <td>{$dados["id_card_number"]}</td>";
            $html .= "  <td>{$termos}</td>";
            $html .= "</tr>";
        }

        $html .= "<tr>";
        $html .= "  <td colspan='5'>";
        $html .= "  <input type='submit' value='Avançar' class='btn btn-success pull-right' style='margin:20px;'>";
        $html .= "  </td>";
        $html .= "</tr>";

        return $html;
    }

    /**
     * Retorna as guias baseado no filtro dos pacientes
     * @param unknown $pacientes
     */
    public function busca_guias_lote($pacientes)
    {
        if ( !empty($pacientes) ) {
            $campos["paciente"] = $pacientes;
            $filtro_query = $this->monta_query_busca($campos);
        }

        $url_curl = URL_API . "guias?{$filtro_query}";
        $result = $this->executa_curl($url_curl);

        return $result["itens"];
    }

    /**
     * Monta a query com a busca a ser feita
     *
     * @param unknown $campos
     * @return string
     */
    private function monta_query_busca($campos)
    {
        $filtro_query = "";

        foreach ( $campos as $campo => $valor ) {
            if (empty($valor)) {
                continue;
            }

            // define o operador padrao '='
            $operador = "=";

            if ( $campo == "name" || $campo == "address" || $campo == "termo" ) {
                $operador = "LIKE";
                $valor = "%{$valor}%";
            }

            if ( $campo == "paciente" ) {
                $operador = "IN";
            }

            if ( $campo == "birthdate" ) {
                list($dia, $mes, $ano) = explode("/", $valor);
                $valor = "{$ano}-{$mes}-{$dia}";
            }

            $filtros["filtro"][$campo]["operador"] = $operador;
            $filtros["filtro"][$campo]["valor"] = $valor;
        }

        if ( !empty($filtros) ) {
            $filtro_query = http_build_query($filtros);
        }

        return $filtro_query;
    }

    /**
     * Executa o CURL da URL passada
     *
     * @param unknown $url_curl
     * @return mixed
     */
    private function executa_curl($url_curl)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url_curl);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $curl_result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($curl_result, true);

        return $result;
    }

    /**
     * Monta a paginação, baseado na quantidade de registros
     *
     * @param unknown $link
     * @param unknown $total_registros
     * @param unknown $pagina
     * @param string $param_depois
     * @return string
     */
    private function montaPaginacao($link, $total_registros, $pagina, $param_depois = "")
    {
        $numero_registros = "";
        $n_paginas = MAX_ITENS;
        $pagina = intval($pagina);

        if (! $pagina) {
            $inicio = 0;
            $pagina = 1;
        } else {
            $inicio = ($pagina - 1) * $n_paginas;
        }

        $total_paginas = ceil($total_registros / $n_paginas);

        if ($n_paginas > $numero_registros) {
            $n_paginas = $numero_registros;
        }

        $tot_ini = (($pagina * MAX_ITENS) - MAX_ITENS) + 1;
        $tot_atual = $pagina * MAX_ITENS;
        $tot_fim = ($total_registros < MAX_ITENS) ? $total_registros : $tot_atual;

        // se for a ultima pagina muda o total final
        if ($total_registros < $tot_atual) {
            $tot_fim = $total_registros;
        }

        // inicia html paginacao
        $paginacao = "\n";
        $paginacao .= " <span class='paginacao_total'>Exibindo os resultados:";
        $paginacao .= " {$tot_ini} a {$tot_fim}";
        $paginacao .= " de {$total_registros}</span>\n";

        // se a quantidade de registros passa de uma pagina
        if ($total_paginas == 1) {
            return $paginacao;
        }

        $filtro = (! empty($param_depois)) ? "{$param_depois}" : "";

        $paginacao .= "<ul class='pagination'>\n";
        $disable_first = ($pagina > 2 && $total_paginas >= 2) ? "" : "disabled";
        $disable_ant = ($pagina > 1) ? "" : "disabled";

        $href = (empty($disable_first)) ? "href='{$link}1{$filtro}'" : "";
        $paginacao .= "<li class='$disable_first'>\n";
        $paginacao .= "<a {$href}><i class='glyphicon glyphicon-chevron-left'></i><i class='glyphicon glyphicon-chevron-left'></i></a>\n";
        $paginacao .= "</li>\n";

        $href = (empty($disable_ant)) ? "href='{$link}" . ($pagina - 1) . "{$filtro}'" : "";
        $paginacao .= "<li class='$disable_ant'>\n";
        $paginacao .= "<a {$href}><i class='glyphicon glyphicon-chevron-left'></i></a>\n";
        $paginacao .= "</li>\n";

        if (($pagina - 2) < 1)
            $anterior = 1;
        elseif ($pagina == $total_paginas) {
            // na ultima pagina
            $anterior = $pagina - 4;
        } elseif ($pagina == ($total_paginas - 1)) {
            // na penultima pagina
            $anterior = $pagina - 3;
        } else
            $anterior = $pagina - 2;

        if (($pagina + 2) > $total_paginas) {
            $posterior = $total_paginas;
        } elseif ($pagina < 3) {
            $posterior = 5;
        } else {
            $posterior = $pagina + 2;
        }

        // não exibe páginas que não existe
        if ($anterior < 1)
            $anterior = 1;
        if ($posterior > $total_paginas)
            $posterior = $total_paginas;

        for($i = $anterior; $i <= $posterior; $i ++) {
            if ($i == $pagina) {
                $paginacao .= "<li class='active'><a>{$i}</a></li>\n";
            } else {
                $paginacao .= "<li class=''><a href='{$link}{$i}{$filtro}'>{$i}</a></li>\n";
            }
        }

        $disabled_last = ($pagina < $total_paginas) ? "" : "disabled";
        $disabled_prox = ($pagina != $total_paginas && $total_paginas > 2) ? "" : "disabled";

        $paginacao .= "<li class='{$disabled_last}'><a href='{$link}" . ($pagina + 1) . "{$filtro}'><i class='glyphicon glyphicon-chevron-right'></i></a></li>\n";

        $paginacao .= "<li class='{$disabled_prox}'>\n";
        $paginacao .= "  <a href='{$link}{$total_paginas}{$filtro}'>\n";
        $paginacao .= "    <i class='fa fa-chevron-right'></i><i class='glyphicon glyphicon-chevron-right'></i>\n";
        $paginacao .= "  </a>\n";
        $paginacao .= "</li>\n";

        $paginacao .= "</ul>\n";

        return $paginacao;
    }
}