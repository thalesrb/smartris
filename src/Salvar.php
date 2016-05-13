<?php

namespace src;

class Salvar {

    /**
     * Recebe os dados do POST e envia para a API
     *
     * @param unknown $post
     */
    function cadastra_guia($post)
    {
        $paciente = $post["paciente"];

        foreach ($post["exames"] as $ids) {
            if ( strpos($ids, "_") === false) {
                continue;
            }

            list($exame_tipo, $exame_id) = explode("_", $ids);

            $fields = array(
                    "paciente"   => $paciente,
                    "exame_tipo" => $exame_tipo,
                    "exame_id"   => $exame_id
            );

            // salva os dados passados
            $this->curl_post("guias", $fields);
        }
    }

    /**
     * Executa o CURL com o POST dos dados
     *
     * @param unknown $rota
     * @param unknown $fields
     * @return mixed
     */
    private function curl_post($rota, $fields)
    {
        $url_curl = URL_API . $rota;

        $fields_string = http_build_query($fields);

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url_curl);
        curl_setopt($curl,CURLOPT_POST, count($fields));
        curl_setopt($curl,CURLOPT_POSTFIELDS, $fields_string);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $curl_result = curl_exec($curl);
        curl_close($curl);

        return $curl_result;
    }

}
