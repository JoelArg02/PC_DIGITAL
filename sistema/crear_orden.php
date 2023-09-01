<?php
session_start();

include('../conexion.php');
if (!isset($_SESSION['permisos']['permiso_crear_ordenes']) || $_SESSION['permisos']['permiso_crear_ordenes'] != 1) {
    header("location: index.php");
    exit();
}

global $conection;

$recipes = [];

$query = mysqli_query($conection, "SELECT * FROM recipe");

while ($row = $query->fetch_assoc()) {
    $row['name'] = html_entity_decode($row['name']);
    $recipes[] = $row;
}


$customerNames = [];

$query = mysqli_query($conection, "SELECT nombre FROM cliente");

while ($row = $query->fetch_assoc()) {
    $customerNames[] = $row['nombre'];
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <?php include "includes/scripts.php"; ?>
    <title>Crear orden</title>
    <link rel="stylesheet" href="./css/crear_orden.css">
    <link rel="icon" type="image/jpg" href="img/favicon.png" />
    <script src="js/popup.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
</head>

<body>
    <?php include(__DIR__ . '/includes/header.php'); ?>
    <main id="container" class="ui-container">
        <form class="ui-box ui-form new-order-container" method="POST" action="guardar_orden.php">
            <h3 class="ui-box-title">Crear nueva orden</h3>
            <div class="ui-box-content">
                <?php if (isset($error)): ?>
                    <div class="ui-alert error">
                        <p>
                            <?php echo $error; ?>
                        </p>
                    </div>
                <?php endif; ?>

                <div class="ui-form-group">
                    <label for="order-date">Nombre cliente</label>
                    <select id="order-customer-name" name="customerName" class="ui-form-control">
                        <option value="" disabled selected>Selecciona un cliente</option>
                        <?php foreach ($customerNames as $name): ?>
                            <option value="<?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?>"><?php echo htmlspecialchars($name, ENT_QUOTES, 'UTF-8'); ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="ui-form-group">
                    <label for="thumbnail">Hoja Tecnica</label>
                    <div class="ui-form-group compound">
                        <div class="ui-form-group">
                            <label for="recipe-item">Receta</label>



                            <select class="form-control" name="recipes" id="recipe-item">
                                <?php foreach ($recipes as $recipe): ?>
                                    <option value="<?php echo $recipe['id']; ?>">
                                        <?php echo $recipe['name']; ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>



                            
                        </div>
                        <div class="ui-form-group">
                            <label for="quantity">Cantidad</label>
                            <input type="number" name="quantity" id="recipe-quantity" placeholder="Cantidad" min="1" step="1"
                                value="1">
                        </div>
                        <div class="ui-form-group button">
                            <button type="button" class="ui-button ui-button blue" id="add-recipe"
                                style="background: rgb(107, 2, 46);">Agregar</button>
                        </div>
                        <div id="recipes-fields"></div>
                    </div>
                    <div class="form-group">
                        <label>Recetas agregadas: </label>
                        <div class="ui-table recipes-added minimal small">
                            <div class="row header">
                                <div class="column">Receta</div>
                                <div class="column">Cantidad</div>
                                <div class="column min-width">Acciones</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui-box-footer right-aligned">
                <button type="submit" class="ui-button ui-button green"
                    style="background: rgb(107, 2, 46);">Guardar</button>
            </div>
        </form>
    </main>
    <template id="added-recipes-table-template">
        <div class="row">
            <div class="column name"></div>
            <div class="column quantity"></div>
            <div class="column">
                <a class="delete" href="javascript:void(0)">
                    <i class="fas fa-trash-alt"></i>
                </a>
            </div>
        </div>
    </template>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>
    <script
        src="https://cdn.jsdelivr.net/gh/xcash/bootstrap-autocomplete@v2.3.7/dist/latest/bootstrap-autocomplete.min.js"></script>
    <script>
        const recipes = <?php echo json_encode($recipes); ?>;
        const addedRecipes = [];
        let currentRecipe = null;

        $('#recipe-item').autoComplete({
            resolver: 'custom',
            events: {
                search: function (qry, callback) {
                    const filtered = recipes
                        .filter(recipe => recipe.name.toLowerCase().includes(qry.toLowerCase()))
                        .map(recipe => ({
                            value: recipe.id,
                            text: recipe.name
                        }));
                    callback(filtered);
                }
            }
        });

        $('#recipe-item').on('autocomplete.select', function (evt, item) {
            currentRecipe = item;
            return false;
        });

        const addRecipeButton = document.querySelector('#add-recipe');

        addRecipeButton.addEventListener('click', () => {
            if (currentRecipe === null) {
                return;
            }

            const quantity = parseInt(document.querySelector('#recipe-quantity').value);

            if (quantity <= 0) {
                return;
            }

            const recipe = addedRecipes.find(recipe => recipe.id === currentRecipe.value);

            if (recipe) {
                const index = addedRecipes.findIndex(addedRecipe => addedRecipe.id === recipe.id);
                addedRecipes[index].quantity += quantity;
            } else {
                const materialsAvailable = checkMaterialAvailability(currentRecipe.value, quantity);

                if (materialsAvailable) {
                    addedRecipes.push({
                        id: currentRecipe.value,
                        name: currentRecipe.text,
                        quantity: quantity
                    });
                } else {
                    const errorDiv = document.querySelector('.ui-alert.error');
                    errorDiv.textContent = 'No hay suficientes materiales disponibles para esta receta.';
                    return;
                }
            }

            updateRecipesFields();
            updateRecipeItems();
        });

        function checkMaterialAvailability(recipeId, quantity) {
            return true;
        }

        function updateRecipesFields() {
            const recipesFields = document.querySelector('#recipes-fields');
            let index = 0;

            recipesFields.innerHTML = '';

            addedRecipes.forEach(recipe => {
                const inputId = document.createElement('input');
                const inputQuantity = document.createElement('input');

                inputId.type = 'hidden';
                inputId.name = `recipes[${index}][id]`;
                inputId.value = recipe.id;

                inputQuantity.type = 'hidden';
                inputQuantity.name = `recipes[${index}][quantity]`;
                inputQuantity.value = recipe.quantity;

                recipesFields.appendChild(inputId);
                recipesFields.appendChild(inputQuantity);
                index++;
            });
        }

        function updateRecipeItems() {
            const table = document.querySelector('.ui-table.recipes-added');

            const rows = table.querySelectorAll('.row:not(.header)');
            rows.forEach(row => row.remove());

            for (const recipe of addedRecipes) {
                const template = document.querySelector('#added-recipes-table-template');
                const clone = template.content.cloneNode(true);

                clone.querySelector('.name').textContent = recipe.name;
                clone.querySelector('.quantity').textContent = recipe.quantity;
                table.appendChild(clone);

                const deleteButton = table.querySelector('.delete:last-child');
                deleteButton.addEventListener('click', () => {
                    const index = addedRecipes.findIndex(addedRecipe => addedRecipe.id === recipe.id);
                    addedRecipes.splice(index, 1);
                    updateRecipesFields();
                    updateRecipeItems();
                });
            }
        }
    </script>

</body>

</html>