<?php
session_start();
include_once 'connection.php';

if (isset($_SESSION['rank']) && isset($_SESSION['id'])) {
    if ($_SESSION['rank'] == 2) {
        echo '<h2> Добавяне на пица</h2>';
    } else {
		header("Location: no_access.php");
        exit();  // За предотвратяване на изпълнението на останалата част от кода
    }
}
if (isset($_POST['submit_add_pizza'])) {
    $newPizzaType = isset($_POST['new_pizza_type']) ? $_POST['new_pizza_type'] : '';

    if ($newPizzaType !== '') {
        // Проверка за съществуване на вид пица със същото име
        $checkQuery = "SELECT id FROM pizza_categories WHERE name = '$newPizzaType'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (mysqli_num_rows($checkResult) == 0) {
            // Ако видът пица НЕ съществува, добавете го в базата данни
            $insertQuery = "INSERT INTO pizza_categories (name) VALUES ('$newPizzaType')";
            $insertResult = mysqli_query($conn, $insertQuery);

            if ($insertResult) {
                echo "Добавихте успешно нов вид пица: $newPizzaType";
            } else {
                echo "Възникна проблем при добавянето на нов вид пица.";
            }
        } else {
            echo '<h4>Вече съществува вид пица с това име. Опитай отново!</h4>';
        }
    } else {
        echo "Моля, въведете име на новата пица.";
    }
}
?>

<!-- Форма за добавяне на нов вид пица -->
<form action="add_pizza_type.php" method="POST">
    <label for="new_pizza_type">Въведете нов вид пица:</label>
    <input type="text" name="new_pizza_type" required>
    <input type="submit" name="submit_add_pizza" value="Добави">
</form>

<!-- Таблица за извеждане на текущите видове пици -->
<h2>Текущи видове пици:</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Вид на пицата</th>
    </tr>
    <?php
    $currentPizzaTypesQuery = "SELECT id, name FROM pizza_categories";
    $currentPizzaTypesResult = mysqli_query($conn, $currentPizzaTypesQuery);

    while ($row = mysqli_fetch_assoc($currentPizzaTypesResult)) {
        echo "<tr><td>{$row['id']}</td><td>{$row['name']}</td></tr>";
    }
    ?>
</table>
</br>
<form action="admin.php" method="POST">
		<input type="submit" name="go_to_admin" value="Върни се назад">
</form>
