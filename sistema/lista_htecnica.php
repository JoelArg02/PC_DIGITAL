<?php
session_start();

include('../conexion.php');
if (!isset($_SESSION['permisos']['permiso_ver_hojas_tecnicas']) || $_SESSION['permisos']['permiso_ver_hojas_tecnicas'] != 1) {
    header("location: index.php");
    exit();
}

$totals = [];

$query = mysqli_query($conection, "SELECT * FROM producto");
$totals['products'] = mysqli_num_rows($query);
$products = [];

while ($row = $query->fetch_assoc()) {
    $products[] = $row;
}

$query = mysqli_query($conection, "SELECT * FROM recipe");
$totals['recipes'] = mysqli_num_rows($query);
$recipes = [];

while ($row = $query->fetch_assoc()) {
    $subquery = mysqli_query(
        $conection,
        "SELECT DISTINCT p.codproducto, p.descripcion, rp.cantidad, p.precio FROM rule_recipe as rp INNER JOIN producto as p ON rp.id_product_rule = p.codproducto WHERE rp.id_recipe =  {$row['id']};"
    );
    $row['ingredients'] = [];
    $manufacturingCost = 0;

    while ($subrow = $subquery->fetch_assoc()) {
        $row['ingredients'][] = $subrow;
        $manufacturingCost += $subrow['cantidad'] * $subrow['precio'];
    }

    $row['manufacturingCost'] = $manufacturingCost;

    $recipes[] = $row;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Hoja Tecnica</title>
    <link rel="icon" type="image/jpg" href="img/favicon.png" />

    <link rel="stylesheet" type="text/css" href="css/style_tables.css">
</head>

<body>
    <?php include(__DIR__ . '/includes/header.php'); ?>
    <main id="container" class="ui-container">
        <div class="ui-box recipes-container">
            <h3 class="ui-box-title">Hoja Tecnica</h3>
            <div class="ui-box-content">
                <div class="ui-table">
                    <div class="row header">
                        <div class="column" style="background: rgb(192, 27, 96);">Nombre</div>
                        <div class="column" style="background: rgb(192, 27, 96);">Precio</div>
                        <div class="column" style="background: rgb(192, 27, 96);">Articulos</div>
                        <div class="column min-width" style="background: rgb(192, 27, 96);">Acciones</div>
                    </div>
                    <?php foreach ($recipes as $recipe): ?>
                        <div class="row">

                            <div class="column">
                                <?php echo $recipe['name']; ?>
                            </div>
                            <div class="column">
                                <?php echo number_format($recipe['manufacturingCost'], 2, '.', ''); ?>
                            </div>
                            <div class="column">
                                <div class="ingredients-list">
                                    <?php foreach ($recipe['ingredients'] as $ingredient): ?>
                                        <div class="ingredient">
                                            <div class="name">
                                                <?php echo $ingredient['descripcion']; ?>
                                            </div>
                                            <div class="quantity">
                                                <?php echo $ingredient['cantidad']; ?>
                                            </div>

                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                            <div class="column min-width buttons">
                                <a href="eliminar_htecnica.php?id=<?php echo $recipe['id']; ?>"
                                    class="ui-button red">Eliminar</a>
                                <a href="registro_htecnica.php?id=<?php echo $recipe['id']; ?>"
                                    class="ui-button blue">Editar</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <div class="ui-box-footer">
                <div class="ui-table">
                    <div class="row">
                        <div class="column">Total de hojas tecnicas:
                            <?php echo $totals['recipes']; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <script>
    </script>
</body>

</html>