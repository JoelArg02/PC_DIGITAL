<?php

session_start();
if (!isset($_SESSION['permisos']['permiso_crear_hoja_tecnica']) || $_SESSION['permisos']['permiso_crear_hoja_tecnica'] != 1) {
	header("location: index.php");
	exit();
}
include(dirname(__DIR__) . '/conexion.php');
global $conection;

$id = isset($_POST['id']) ? $_POST['id'] : (isset($_GET['id']) ? $_GET['id'] : null);

if (empty($_POST['name'])) {
    $error = 'no se ha recibido el nombre';
    include(__DIR__ . '/registro_htecnica.php');
    exit;
}

if (empty($_POST['ingredients']) || !is_array($_POST['ingredients'])) {
    $error = 'No se han ingresado los ingredientes';
    include(__DIR__ . '/registro_htecnica.php');
    exit;
}

$name = htmlentities($_POST['name'], ENT_QUOTES, 'UTF-8');
$ingredients = $_POST['ingredients'];
$user_id = intval($_SESSION['idUser']);
$now = date('Y-m-d H:i:s');

$ingredientsIds = [];
$ingredientsRel = [];

foreach ($ingredients as $ingredient) {
    $ingredientsIds[] = $ingredient['id'];
    $ingredientsRel[$ingredient['id']] = $ingredient;
}

$stmt2 = mysqli_query($conection, "SELECT codproducto, precio FROM producto WHERE codproducto IN (" . implode(',', $ingredientsIds) . ")");
;

if (!$stmt2) {
    die(mysqli_error($conection));
}

$result = $stmt2->fetch_all(MYSQLI_ASSOC);
$price = 0.0;

foreach ($result as $row) {
    $ingredient = $ingredientsRel[$row['codproducto']];
    $unitPrice = floatval($row['precio']);
    $quantity = floatval($ingredient['quantity']);
    $price += ($unitPrice * $quantity);
}

$product = [
    'descripcion' => $name,
    'precio' => $price,
    'ingredientes' => $ingredients
];



if ($id == null) {
    $stmt = mysqli_prepare($conection, "INSERT INTO recipe(user_id, name, price, created_at, updated_at) VALUES(?, ?, ?, ?, ?)");

    if (!$stmt) {
        $error = mysqli_error($conection);
        echo $error;
        exit;
    }

    $stmt->bind_param('isdss', $user_id, $name, $price, $now, $now);
    $stmt->execute();
    $stmt->close();

    $recipe_id = mysqli_insert_id($conection);

    foreach ($ingredients as $ingredient) {
        $stmt = mysqli_prepare($conection, "INSERT INTO rule_recipe(id_recipe, id_product_rule, cantidad) VALUES(?, ?, ?)");
        $quantity = floatval($ingredient['quantity']);
        $stmt->bind_param('iid', $recipe_id, $ingredient['id'], $quantity);
        $stmt->execute();
        $stmt->close();
    }

} else {

    foreach ($ingredients as $ingredient) {
        $stmt = mysqli_prepare($conection, "SELECT id_recipe FROM rule_recipe WHERE id_recipe = ? AND id_product_rule = ?");
        $stmt->bind_param('ii', $id, $ingredient['id']);
        $stmt->execute();
        $stmt->store_result();
        
        if ($stmt->num_rows > 0) {
            // Si el registro ya existe, actualiza la cantidad en lugar de insertar
            $stmt = mysqli_prepare($conection, "UPDATE rule_recipe SET cantidad = ? WHERE id_recipe = ? AND id_product_rule = ?");
            $quantity = floatval($ingredient['quantity']);
            $stmt->bind_param('dii', $quantity, $id, $ingredient['id']);
            $stmt->execute();
            $stmt->close();
        } else {
            // Si el registro no existe, inserta un nuevo registro
            $stmt = mysqli_prepare($conection, "INSERT INTO rule_recipe(id_recipe, id_product_rule, cantidad) VALUES(?, ?, ?)");
            $quantity = floatval($ingredient['quantity']);
            $stmt->bind_param('iid', $id, $ingredient['id'], $quantity);
            $stmt->execute();
            $stmt->close();
        }
    }
    
}

header('Location: lista_htecnica.php');