<?php
$paciente = $_GET["paciente"];

if ( empty($paciente) ) {
    die("Selecione um paciente: <a href='/smart_ris/guia/'>Montar guia</a>");
}

$html_paciente = $objBuscas->buscar_dados_paciente($paciente);
// $options = $objBuscas->buscar_categorias(); // desativado
?>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $("select").select2();
    });

    $(function() {
        $('#tipo').on('change', function() {
            $.post("/smart_ris/public/ajax_get_exames.php", {
            	tipo: $('#tipo').val()
            }, function(e) {
            	if (e){
            	       $("#exame option").remove();
            	       $("#exame").select2({
            	           data: e
            	        });
            	     }
            })
        });
    });

    $(document).ready(function(){
        $("#exame").select2({
            placeholder: "Busque pelo nome do exame",
            language: "pt-BR",
            tags: true,
            ajax: {
                url: "/smart_ris/public/ajax_get_exames.php",
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {
                        busca: params.term, // search term
                        pagina: params.page
                    };
                },
                processResults: function (data, params) {
                    // parse the results into the format expected by Select2.
                    // since we are using custom formatting functions we do not need to
                    // alter the remote JSON data
                    params.page = params.page || 1;

                    return {
                        results: data.itens,
                        pagination: {
                            more: (params.page * 15) < data.total
                        }
                    };
                },
                cache: true
            },
            escapeMarkup: function (markup) { return markup; },
            minimumInputLength: 2,
            templateResult: formataLayout,
        });

        $("#limpa_filtro").click(function() {
            $("#select2-cmpBuscaCliente-container").html("Busque cliente por: código, fantasia, email, CPF/CNPJ ou homepage");
            $("#cmpBuscaCliente").find('option:selected').val("");
        });
    });

    function formataLayout (dados) {
        return dados.text;
    }

    function formatRepoSelection (repo) {
        return "Carregando" ;
    }
</script>

<div class="col-xs-12">
    <div class="pull-left">
        <button type="button" class="btn btn-primary" style="padding: 15px; margin-top: 5px;">
            <span aria-hidden="true" class="glyphicon glyphicon-user" style="font-size: 20px;"></span> <br> <b></b>
        </button>
    </div>
    <div class="pull-left" style='margin-left: 20px;'>
        <h2>Montar guia</h2>
    </div>

    <div class="featurette busca_paciente col-xs-12" style="">

        <h4>Paciente</h4>
        <p><?php print $html_paciente; ?></p>

        <h4 style='margin-top: 20px;'>Adicionar Exames</h4>
        <form action='/smart_ris/guia_salvar/' method="POST">
            <input type="hidden" name="paciente" value="<?php print $paciente; ?>">
            <!--
            <label>Tipo:</label>
            <select id="tipo">
                <option>Selecione</option>
                <?php // print $options; ?>
            </select>
            <br>
            -->

            <label style='margin-right: 20px;'>Exame:</label>
            <br>
            <select id="exame" style='width: 700px;' multiple="multiple" name="exames[]">
                <option>Selecione um exame</option>
            </select>

            <input type='submit' value='Salvar' class='btn btn-success pull-right' style='margin:20px;'>
        </form>
    </div>

</div>




