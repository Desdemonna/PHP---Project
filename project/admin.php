<?php
session_start();
include_once 'connection.php';

if (isset($_SESSION['rank']) && isset($_SESSION['id'])) {
    if ($_SESSION['rank'] == 2) {
        echo '<h2>Добре дошли!</h2>';
    } else {
		header("Location: no_access.php");
        exit();  // За предотвратяване изпълнението на останалата част от кода
    }
}
?>
<form action="filter_orders.php" method="POST">
    <input type="submit" name="go_to_filter_orders" value="Филтрирай поръчките">
</form>

<form action="add_pizza_type.php" method="POST">
    <input type="submit" name="go_to_add_pizza" value="Добави нов вид пица">
</form>

<form action="index.php" method="POST">
		<input type="submit" name="go_to_index" value="Върни се в началото">
</form>