<?php
if (isset($_GET["id"])) {
    $xml = simplexml_load_file("xmlgeneral.xml");
    $administrativo = $xml->xpath(expression: "/facultad/posgrado/maestria/administrativos/administrativo[id_empleado=".$_GET["id"]."]");
    $area_dada = array();
    foreach ($administrativo[0]->areas->area as $area) {
        array_push($area_dada,strval($area->attributes()));
    }
}
?>
<!DOCTYPE html>
<html lang="es" dir="ltr">
<head>
    <meta charset="utf-8">
    <title>Personal Administrativo</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"/>
    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="css/jquery-ui.min.css"/>
    <link rel="stylesheet" href="css/estilos.css"/>
    <script src="js/external/jquery/jquery.js"></script>
    <script src="js/jquery-ui.js"></script>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css"/>
</head>
<script type="text/javascript">
function guardar() {
    $.ajax({
        // Guardar/Editar Registro
        url: "include/funciones.php",
        type: "post",
        data: $("#formulario").serialize(),
        success: function (response) {
            if (response == "0") {
                // Error: ID de empleado ya existe
                $("<div>El ID ya ha sido establecido en otro empleado.</div>").dialog({
                    title: "Error",
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Entendido": function() {
                            $(this).dialog("close");
                        }
                    }
                });
            } else {
                $("<div>Acción Completada.</div>").dialog({
                    title: "Acción Completada",
                    resizable: false,
                    height: "auto",
                    width: 400,
                    modal: true,
                    buttons: {
                        "Entendido": function() {
                            $(this).dialog("close");
                            document.location = 'xmlgeneral.xml';
                        }
                    }
                });
            }
        },
        error: function (xhr, ajaxOptions, thrownError) {
            alert(xhr.status);
        }
    });
}
</script>
<body class="fondo_main">
<br><br>
<h2 align="center" class="titulo">Registrar Personal Administrativo</h2>
<form role="form" id="formulario" name="formulario" action="javascript:guardar();">
    <div class="container">
        <br>
        <div id="accordion">
            <div class="card">
                <div class="card-header" id="headingOne">
                    <h5 class="mb-0">
                        <button type="button" class="btn btn-link" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Datos Personales
                        </button>
                    </h5>
                </div>

                <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                    <div class="card-body">
                        <div class="container">
                            <div class="form-row">
                                <div class="form-group col-md-4">
                                    <label>ID Empleado:</label>
                                    <input type="number" name="id_empleado" class="form-control" required
                                    <?php if (isset($_GET["id"])) { echo "value='".$administrativo[0]->id_empleado."' disabled"; } ?>>
                                    <input type="hidden" name="id" id="id" value="<?php echo (isset($_GET["id"]) ? $_GET["id"] : "") ?>" />
                                    <input type="hidden" name="acc" id="acc" value="<?php echo (isset($_GET["id"]) ? "2" : "1") ?>" />
                                    <input type="hidden" name="tipo" id="tipo" value="4" />
                                </div>
                                <div class="form-group col-md-8">
                                    <label>Nombre completo:</label>
                                    <input type="text" name="nombre" class="form-control" required
                                    <?php if (isset($_GET["id"])) { echo "value='" . $administrativo[0]->nombre . "'"; } ?>>
                                </div>
                            </div>
                            <div class="form-row">
                            <div class="form-group col-md-4">
                                <label>Cargo:</label>
                                <select class="custom-select" name="area">
                                    <option value="SA" <?php if (isset($_GET["area"]) && $_GET["area"]=="SA") {echo "selected";}?>>Secretario Académico</option>
                                    <option value="DI" <?php if (isset($_GET["area"]) && $_GET["area"]=="DI") {echo "selected";}?>>Director</option>
                                    <option value="CO" <?php if (isset($_GET["area"]) && $_GET["area"]=="CO") {echo "selected";}?>>Coordinador de carrera</option>
                                    <option value="SP" <?php if (isset($_GET["area"]) && $_GET["area"]=="SP") {echo "selected";}?>>Secretario de Posgrado</option>
                                    <option value="SAD" <?php if (isset($_GET["area"]) && $_GET["area"]=="SAD") {echo "selected";}?>>Secretario Administrativo</option>
                                    <option value="CON" <?php if (isset($_GET["area"]) && $_GET["area"]=="CON") {echo "selected";}?>>Contador</option>
                                </select>
                            </div>
                                <div class="form-group col-md-8">
                                    <label>Telefono:</label>
                                    <input type="telephone" name="telefono" class="form-control" required
                                    <?php if (isset($_GET["id"])) {echo "value='".$administrativo[0]->telefono."'";}?>>
                                </div>
                                <div class="form-group col-md-8">
                                    <div class="input-group">
                                        <span class="input-group-text">Fecha de ingreso:</span>
                                        <input type="date" class="form-control" placeholder="YYYY-MM-DD" id="fecha" name="fecha_ing" required
                                        <?php if (isset($_GET["id"])) {echo "value='".$administrativo[0]->fecha_ing."'";}?>>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <?php if (!isset($_GET["id"])): ?>
            <div align="center" style="margin-bottom: 20px;">
                <button type="submit" class="btn btn-primary boton_guardar" style="width:350px;">Guardar</button>
            </div>
        <?php else: ?>
            <div align="center" style="margin-bottom: 20px;">
                <button type="submit" class="btn btn-primary boton_editar" style="width:350px;">Editar</button>
            </div>
        <?php endif; ?>
    </div>
</form>
</body>
<script>

</html>
