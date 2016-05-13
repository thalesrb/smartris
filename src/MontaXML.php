<?php

namespace src;

class MontaXML {
    private $values;

    /**
     * Baseado nas guias passadas, retorna o XML no formato da TISS
     *
     * @param unknown $guias_pacientes
     * @param unknown $dados_guias
     */
    public function retorna_xml_tiss($guias_pacientes, $dados_guias) {

        // monta um array no formato desejado
        foreach ( $guias_pacientes as $pacientes ) {

            foreach ( $dados_guias as $dados ) {
                if ($dados["paciente"] != $pacientes) {
                    continue;
                }

                $arr_guias[$pacientes]["beneficiario"] = array (
                        "numeroCarteira" => $dados["id_card_number"],
                        "atendimentoRN" => "N",
                        "nomeBeneficiario" => $dados["name"]
                );

                $arr_guias[$pacientes]["procedimentos"][] = array (
                        "codigoTabela" => $dados["exame_tipo"],
                        "codigoProcedimento" => $dados["exame_id"],
                        "descricaoProcedimento" => $dados["termo"]
                );
            }
        }

        // processa as guias
        foreach ( $arr_guias as $paciente => $dados ) {

            foreach ( $dados["procedimentos"] as $procedimento ) {
                $xml_procedimentos[] = array (
                        "procedimentoExecutado" => array (
                                "dataExecucao" => date("Y-m-d"),
                                "horaInicial" => date("H:i:s"),
                                "horaFinal" => date("H:i:s"),
                                "procedimento" => array (
                                        "codigoTabela" => $procedimento["codigoTabela"],
                                        "codigoProcedimento" => $procedimento["codigoProcedimento"],
                                        "descricaoProcedimento" => $procedimento["descricaoProcedimento"]
                                ),
                                "quantidadeExecutada" => 1,
                                "viaAcesso" => 1,
                                "reducaoAcrescimo" => 1.00,
                                "valorUnitario" => 0,
                                "valorTotal" => 0
                        )
                );
            }

            $guias[] = array (
                    "guiaSP-SADT" => array (
                            "cabecalhoGuia" => array (
                                    "registroANS" => 312347,
                                    "numeroGuiaPrestador" => 1003391
                            ),
                            "dadosAutorizacao" => array (
                                    "numeroGuiaOperadora" => 6581418,
                                    "dataAutorizacao" => date("Y-m-d"),
                                    "senha" => 7213072,
                                    "dataValidadeSenha" => date("Y-m-d")
                            ),
                            "dadosBeneficiario" => array (
                                    "numeroCarteira" => $dados["beneficiario"]["numeroCarteira"],
                                    "atendimentoRN" => $dados["beneficiario"]["atendimentoRN"],
                                    "nomeBeneficiario" => $dados["beneficiario"]["nomeBeneficiario"]
                            ),
                            "dadosSolicitante" => array (
                                    "contratadoSolicitante" => array (
                                            "codigoPrestadorNaOperadora" => 99999999,
                                            "nomeContratado" => "MEDICO SOLICITANTE" . rand(1, 9)
                                    ),
                                    "profissionalSolicitante" => array (
                                            "nomeProfissional" => "MEDICO SOLICITANTE" . rand(1, 9),
                                            "conselhoProfissional" => rand(1, 9),
                                            "numeroConselhoProfissional" => rand(100, 10000),
                                            "UF" => "42",
                                            "CBOS" => 225270
                                    )
                            ),
                            "dadosSolicitacao" => array (
                                    "caraterAtendimento" => 1
                            ),
                            "dadosExecutante" => array (
                                    "contratadoExecutante" => array (
                                            "codigoPrestadorNaOperadora" => 99999999,
                                            "nomeContratado" => "PRORADIS DIAGNOSTICOS"
                                    ),
                                    "CNES" => 3333333
                            ),
                            "dadosAtendimento" => array (
                                    "tipoAtendimento" => "05",
                                    "indicacaoAcidente" => 9
                            ),
                            "procedimentosExecutados" => $xml_procedimentos,
                            "valorTotal" => array (
                                    "valorProcedimentos" => 0,
                                    "valorMateriais" => 0,
                                    "valorTotalGeral" => 0
                            )
                    )
            );
        }

        $arr_xml = array ();
        $arr_xml["cabecalho"] = array (
                "identificacaoTransacao" => array (
                        "tipoTransacao" => "ENVIO_LOTE_GUIAS",
                        "sequencialTransacao" => rand(100, 999),
                        "dataRegistroTransacao" => date("Y-m-d"),
                        "horaRegistroTransacao" => date("H:i:s")
                ),
                "origem" => array (
                        "identificacaoPrestador" => array (
                                "codigoPrestadorNaOperadora" => 99999999
                        )
                ),
                "destino" => array (
                        "registroANS" => 312347
                ),
                "versaoPadrao" => "3.02.00"
        );

        $arr_xml["prestadorParaOperadora"] = array (
                "loteGuias" => array (
                        "numeroLote" => rand(1000, 9999),
                        "guiasTISS" => $guias
                )
        );

        $arr_xml["epilogo"] = array (
                "hash" => ""
        );

        $xml = $this->monta_xml($arr_xml);
        return $xml;
    }

    /**
     * Dado o array no formato desejado monta o XML final
     * @param unknown $array
     */
    private function monta_xml($array_xml)
    {
        $xml = new \SimpleXMLElement("<?xml version=\"1.0\"?><ans:mensagemTISS ></ans:mensagemTISS>", LIBXML_NOERROR, false, "ans", true);
        $xml->addAttribute('xmlns:xmlns:ans', 'http://www.ans.gov.br/padroes/tiss/schemas');
        $xml->addAttribute('xmlns:xmlns', 'http://www.w3.org/2001/XMLSchema');

        // pequena POG 1 =P
        $node = $xml->addChild('request');

        $this->array_to_xml($array_xml, $node);
        $xml = $xml->asXML();

        // pequena POG 2 =P
        $arr_remove = array (
            "<request>",
            "</request>",
            " xmlns:ans=\"ans\""
        );
        $xml = str_replace($arr_remove, "", $xml);

        // adiciona o hash calculado
        $hash = md5($this->values);
        $xml = str_replace("<ans:hash/>", "<ans:hash>{$hash}</ans:hash>", $xml);

        return $xml;

    }

    /**
     * Converte o array multilevel em XML
     *
     * @param unknown $array
     * @param unknown $xml
     */
    function array_to_xml($array, &$xml) {
        foreach ( $array as $key => $value ) {
            if (is_array($value)) {
                if (! is_numeric($key)) {
                    $subnode = $xml->addChild("ans:{$key}", "", "ans");
                    $this->array_to_xml($value, $subnode);
                } else {
                    $this->array_to_xml($value, $xml);
                }
            } else {
                // concatena todos os valores do XML
                $this->values .= $value;
                $xml->addChild("ans:{$key}", "$value", "ans");
            }
        }
    }
}

