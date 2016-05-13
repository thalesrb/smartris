<?php
$name = $birthdate = $address = $id_card_number = $pagina = "";

// torna os campos em variaveis
extract($_GET);

$resultado_busca = $objBuscas->buscar_paciente($name, $birthdate, $address, $id_card_number, $pagina);
?>
<div class="col-xs-12">
    <div class="pull-left">
        <button type="button" class="btn btn-primary" style="padding: 15px; margin-top: 5px;">
            <span aria-hidden="true" class="glyphicon glyphicon-user" style="font-size: 20px;"></span> <br> <b></b>
        </button>
    </div>
    <div class="pull-left" style='margin-left: 20px;'>
        <h2>Montar guia</h2>
    </div>
    <?php if ( isset($_GET["mensagem"]) ) : ?>
    <p class="bg-success col-xs-12" style='padding: 10px; margin-top:20px;'>Guia cadastrada com sucesso.</p>
    <?php endif; ?>
    <div class="featurette busca_paciente col-xs-12" style="">
        <h4>Buscar Paciente</h4>
        <form class="form-inline" method="GET">
            <div class="">
                <label for="exampleInputEmail1" class="col-sm-1 control-label">Nome</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="name" size="35" value="<?php print $name;?>">
                </div>

                <label for="exampleInputEmail1" class="col-sm-3 control-label">Data de nascimento</label>
                <div class="col-sm-3">
                    <input type="datetime" class="form-control" name="birthdate" value="<?php print $birthdate;?>">
                </div>

                <br style="clear: both"> <label for="exampleInputEmail1" class="col-sm-1 control-label">Endereço</label>
                <div class="col-sm-4">
                    <input type="datetime" class="form-control" name="address" size="35" value="<?php print $address;?>">
                </div>

                <label for="exampleInputEmail1" class="col-sm-3 control-label">Carteirinha do paciente</label>
                <div class="col-sm-3">
                    <input type="datetime" class="form-control" name="id_card_number" value="<?php print $id_card_number;?>">
                </div>
            </div>
            <div class="horizontal col-sm-12" style="text-align: center;">
                <button type="submit" class="btn btn-default">Buscar</button>
            </div>
        </form>
    </div>

    <?php if ( !empty($resultado_busca) ) : ?>
    <div class="featurette busca_paciente col-xs-12">
        <h4>Pacientes Encontrados</h4>
        <p class="text-muted">Escolha um paciente e clique em avançar</p>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Selecionar</th>
                    <th>Nome</th>
                    <th>Dt. Nascimento</th>
                    <th>Endereço</th>
                    <th>Carteirinha</th>
                </tr>
            </thead>
            <tbody>
                <form action="/smart_ris/guia_exames/" method="get">
                    <?php print $resultado_busca; ?>
                </form>
            </tbody>
        </table>


    </div>
    <?php endif; ?>

</div>

