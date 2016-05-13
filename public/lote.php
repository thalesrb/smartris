<?php
$resultado_busca = $objBuscas->buscar_guias();
?>
<div class="col-xs-12">
    <div class="pull-left">
        <button type="button" class="btn btn-info" style="padding: 15px; margin-top: 5px;">
            <span aria-hidden="true" class="glyphicon glyphicon-briefcase" style="font-size: 20px;"></span> <br> <b></b>
        </button>
    </div>
    <div class="pull-left" style='margin-left: 20px;'>
        <h2>Montar lote</h2>
    </div>

    <?php if ( !empty($resultado_busca) ) : ?>
    <div class="featurette busca_paciente col-xs-12">
        <h4>Guias Encontrados</h4>
        <p class="text-muted">Escolha as guias para montar um lote</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Selecionar</th>
                    <th>Nome</th>
                    <th>Carteirinha</th>
                    <th>Exames</th>
                </tr>
            </thead>
            <tbody>
                <form action="/smart_ris/lote_gerar/" method="POST">
                    <?php print $resultado_busca; ?>
                </form>
            </tbody>
        </table>


    </div>
    <?php endif; ?>

</div>

