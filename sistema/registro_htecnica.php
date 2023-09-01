<?php
session_start();

include('../conexion.php');
global $conection;
if (!isset($_SESSION['permisos']['permiso_crear_hoja_tecnica']) || $_SESSION['permisos']['permiso_crear_hoja_tecnica'] != 1) {
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
    $recipes[] = $row;
}

$update_id = isset($_GET['id']) ? intval($_GET['id']) : (isset($id) ? intval($id) : null);

if ($update_id) {
    $query = mysqli_query($conection, "SELECT * FROM recipe WHERE id = $update_id");
    $update_recipe = $query->fetch_assoc();
    $subquery = mysqli_query(
        $conection,
        "SELECT p.codproducto, p.descripcion, rp.cantidad, p.precio
                FROM rule_recipe as rp
                LEFT JOIN producto as p ON (p.codproducto = rp.id_product_rule)
                WHERE rp.id_recipe = {$update_recipe['id']}"
    );
    $update_recipe['ingredients'] = [];
    $update_recipe['manufacturingCost'] = 0;

    while ($subrow = $subquery->fetch_assoc()) {
        $update_recipe['ingredients'][] = $subrow;
        $update_recipe['manufacturingCost'] += $subrow['cantidad'] * $subrow['precio'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Hoja Tecnica - Registro
    </title>
    <link rel="stylesheet" href="./css/style.css">
    <link rel="icon" type="image/jpg" href="img/favicon.png" />
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

    <style>
        .ingredients-list {
            background: #c3e6ff;
            color: #196499;
            display: table;
            width: 100%;
        }

        .ingredients-list .row {
            display: table-row;
        }

        .ingredients-list .row .column {
            display: table-cell;
            padding: 10px;
        }

        .ingredients-list .row .column.quantity {
            background: #2480c2;
            color: #fff;
            border-radius: inherit;
            white-space: nowrap;
            width: 1px;
        }

        .ingredients-list .row .column.options {
            width: 1px;
            white-space: nowrap;
        }

        .ingredients-list .row .column.name {
            width: 90%;
        }

        .final-price {
            border-top: 2px dashed #2480c2;
            margin-top: 0px;
            padding: 9px 2px;
            background: #ccdce8;
        }
    </style>
</head>

<body>
    <?php include(__DIR__ . '/includes/header.php'); ?>
    <main id="container" class="ui-container">
        <form class="container-recipes ui-box ui-form recipe-form" method="POST" action="guardar_htecnica.php"
            enctype="multipart/form-data">
            <h2 class="ui-box-title">Generar Hoja Tecnica</h2>
            <div class="ui-box-content">
                <?php if (isset($error)): ?>
                    <div class="ui-alert error">
                        <p>
                            <?php echo $error; ?>
                        </p>
                    </div>
                <?php endif; ?>
                <div class="ui-form-group">
                    <label for="name">Nombre</label>
                    <input <?php echo (isset($update_recipe) ? 'value="' . htmlspecialchars($update_recipe['name'], ENT_QUOTES, 'UTF-8') . '"' : ''); ?> type="text" name="name" id="name"
                        placeholder="Nombre del Producto Final" required>

                </div>

                <div class="ui-form-group">
                    <label for="thumbnail">Productos</label>
                    <div class="ui-form-group compound">
                        <div class="ui-form-group">
                            <label for="ingredient-item">Producto</label>


                            
                            <select name="products" id="ingredient-item" class="select2">
                                <?php foreach ($products as $product): ?>
                                    <option value="<?php echo $product['codproducto']; ?>"
                                        data-name="<?php echo $product['descripcion']; ?>"
                                        data-precio="<?php echo $product['precio']; ?>">
                                        <?php echo $product['descripcion'] . " " . $product['medida']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>



                        </div>
                        <div class="ui-form-group">
                            <label for="quantity">Cantidad</label>
                            <input type="number" name="quantity" id="ingredient-quantity" placeholder="Cantidad"
                                 value="1">
                        </div>
                        <div class="ui-form-group button">
                            <button type="button" class="ui-button ui-button blue" id="add-ingredient"
                                style="background: rgb(107, 2, 46);">Agregar</button>
                        </div>
                        <div id="ingredients-fields">
                            <?php if (isset($update_recipe)): ?>
                                <?php
                                $index = 0;
                                ?>
                                <?php foreach ($update_recipe['ingredients'] as $ingredient): ?>
                                    <input type="hidden" name="ingredients[<?php echo $index; ?>][id]"
                                        value="<?php echo $ingredient['codproducto']; ?>">
                                    <input type="hidden" name="ingredients[<?php echo $index; ?>][quantity]"
                                        value="<?php echo $ingredient['cantidad']; ?>">
                                    <?php $index++; ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div id="ingredients-list" class="ingredients-list">
                        <?php if (isset($update_recipe)): ?>
                            <?php foreach ($update_recipe['ingredients'] as $ingredient): ?>
                                <div class="row">

                                    <div class="column quantity">
                                        <?php echo $ingredient['cantidad']; ?>
                                    </div>
                                    <div class="column name">
                                        <?php echo $ingredient['descripcion']; ?>
                                    </div>
                                    <div class="column quantity">
                                        <a href="javascript:void(0)"
                                            onclick="deleteIngredient(<?php echo $ingredient['codproducto']; ?>)"><i
                                                class="fa-solid fa-trash" style="color: white"></i></a>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <div class="final-price">
                        <strong>Precio final: </strong>
                        <span id="final-price">
                            <?php echo (isset($update_recipe) ? number_format($update_recipe['manufacturingCost'], 2, '.', '') : 0); ?>
                        </span>
                    </div>
                </div>
            </div>
            <div class="ui-box-footer right-aligned">
                <button type="submit" class="ui-button ui-button green"
                    style="background: rgb(107, 2, 46);">Guardar</button>
            </div>
            <?php if (isset($update_recipe)): ?>
                <input type="hidden" name="id" value="<?php echo $update_recipe['id']; ?>">
            <?php endif; ?>
        </form>
    </main>
    <script>
        $(document).ready(function () {
            $('.select2').select2();
        });

        const products = <?php echo json_encode($products); ?>;
        const addIngredientBtn = document.querySelector('#add-ingredient');
        const ingredients = <?php
        if (isset($update_recipe)) {
            $ingredients = [];

            foreach ($update_recipe['ingredients'] as $ingredient) {
                $ingredients[] = [
                    'id' => intval($ingredient['codproducto']),
                    'quantity' => $ingredient['cantidad'],
                    'name' => $ingredient['descripcion'],
                    'price' => $ingredient['precio']
                ];
            }

            echo json_encode($ingredients);
        } else {
            echo '[]';
        }
        ?>;

        addIngredientBtn.addEventListener('click', () => {
            const id = document.querySelector('#ingredient-item').value;
            const quantity = parseFloat(document.querySelector('#ingredient-quantity').value);

            if (!id) {
                alert('No se ha seleccionado un ingrediente');
                return;
            }

            if (isNaN(quantity) || quantity <= 0) {
                alert('La cantidad debe ser mayor a 0');
                return;
            }

            const name = document.querySelector('#ingredient-item')
                .options[document.querySelector('#ingredient-item').selectedIndex].dataset.name;

            const price = parseFloat(document.querySelector('#ingredient-item')
                .options[document.querySelector('#ingredient-item').selectedIndex].dataset.precio);

            const ingredient = {
                id,
                quantity,
                name,
                price
            };

            const exists = ingredients.find(ingredient => ingredient.id === id);

            if (exists) {
                const index = ingredients.indexOf(exists);
                ingredients[index].quantity += quantity;
                updateHiddenFields();
                updateIngredientsList();
                return;
            }

            ingredients.push(ingredient);
            updateHiddenFields();
            updateIngredientsList();
        });

        function updateIngredientsList() {
            const ingredientsList = document.querySelector('#ingredients-list');
            ingredientsList.innerHTML = '';

            let finalPrice = 0.0;

            ingredients.forEach(ingredient => {
                const row = document.createElement('div');
                row.classList.add('row');

                row.innerHTML = `
                <div class="column quantity">${ingredient.quantity}</div>
                <div class="column name">${ingredient.name}</div>
                <div class="column options">
                    <a href="javascript:void(0)" onclick="deleteIngredient(${ingredient.id})"><i class="fa-solid fa-trash"></i></a>
                </div>
            `;
                ingredientsList.appendChild(row);

                finalPrice += ingredient.quantity * ingredient.price;
            });

            document.querySelector('#final-price').innerHTML = finalPrice.toFixed(2);
        }

        function deleteIngredient(id) {
            const ingredient = ingredients.find(ingredient => ingredient.id === id);
            const index = ingredients.indexOf(ingredient);
            ingredients.splice(index, 1);
            updateHiddenFields();
            updateIngredientsList();
        }

        function updateHiddenFields() {
            const ingredientsFields = document.querySelector('#ingredients-fields');
            ingredientsFields.innerHTML = '';

            ingredients.forEach((ingredient, index) => {
                const idField = document.createElement('input');
                idField.type = 'hidden';
                idField.name = `ingredients[${index}][id]`;
                idField.value = ingredient.id;

                const quantityField = document.createElement('input');
                quantityField.type = 'hidden';
                quantityField.name = `ingredients[${index}][quantity]`;
                quantityField.value = ingredient.quantity;

                ingredientsFields.appendChild(idField);
                ingredientsFields.appendChild(quantityField);
            });
        }
    </script>

</body>

</html>