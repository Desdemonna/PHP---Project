<?php
session_start();
include_once 'connection.php';

if (isset($_SESSION['rank']) && isset($_SESSION['id'])) {
    if ($_SESSION['rank'] == 2) {
        echo '<h2> Филтриране на поръчки</h2>';
    } else {
		header("Location: no_access.php");
        exit();  // За предотвратяване изпълнението на останалата част от кода
    }
}

// Заявка за извличане на поръчки
$query = "SELECT orders.id, orders.name, orders.address, orders.phone, pizza_categories.name AS pizza_type, pizza_size.size AS pizza_size
          FROM orders
          INNER JOIN pizza_categories ON orders.pizza_type_id = pizza_categories.id
          INNER JOIN pizza_size ON orders.pizza_size_id = pizza_size.id";

$result = mysqli_query($conn, $query);

// Заявка за извличане на видовете пици
$queryPizzaTypes = "SELECT id, name FROM pizza_categories";
$resultPizzaTypes = mysqli_query($conn, $queryPizzaTypes);
$pizzaTypes = [];

while ($row = mysqli_fetch_assoc($resultPizzaTypes)) {
    $pizzaTypes[] = $row['name'];
}

// Заявка за извличане на големините на пиците
$queryPizzaSizes = "SELECT id, size FROM pizza_size";
$resultPizzaSizes = mysqli_query($conn, $queryPizzaSizes);
$pizzaSizes = [];

while ($row = mysqli_fetch_assoc($resultPizzaSizes)) {
    $pizzaSizes[] = $row['size'];
}

// Филтриране на поръчките според избора във формата
if (isset($_GET['submit'])) {
    $pizzaTypeFilter = isset($_GET['pizza_type_filter']) ? $_GET['pizza_type_filter'] : '';
    $pizzaSizeFilter = isset($_GET['pizza_size_filter']) ? $_GET['pizza_size_filter'] : '';

    if ($pizzaTypeFilter !== '') {
        $query .= " WHERE pizza_categories.name = '$pizzaTypeFilter'";
    }

    if ($pizzaSizeFilter !== '') {
        $query .= ($pizzaTypeFilter !== '') ? " AND" : " WHERE";
        $query .= " pizza_size.size = '$pizzaSizeFilter'";
    }

    $result = mysqli_query($conn, $query);
}
?>

<!-- Форма за филтриране -->
<form action="" method="GET">
    <label for="pizza_type_filter">Филтър по вид на пицата:</label>
    <select name="pizza_type_filter">
        <option value="">-- Избери --</option>
        <?php
        foreach ($pizzaTypes as $type) {
            echo "<option value='$type'>$type</option>";
        }
        ?>
    </select>

    <label for="pizza_size_filter">Филтър по големина на пицата:</label>
    <select name="pizza_size_filter">
        <option value="">-- Избери --</option>
        <?php
        foreach ($pizzaSizes as $size) {
            echo "<option value='$size'>$size</option>";
        }
        ?>
    </select>

    <input type="submit" name="submit" value="Филтрирай">
</form>


<!-- Форма за сортиране -->
<form action="" method="GET">
    <label for="sort_column">Сортиране по колона:</label>
    <select name="sort_column">
        <option value="id">ID</option>
        <option value="name">Име</option>
        <option value="address">Адрес</option>
        <option value="phone">Телефон</option>
        <option value="pizza_type">Вид пица</option>
        <option value="pizza_size">Големина на пицата</option>
    </select>

    <label for="sort_direction">Посока:</label>
    <select name="sort_direction">
        <option value="asc">Възходяща</option>
        <option value="desc">Низходяща</option>
    </select>

    <input type="submit" name="submit_sort" value="Сортирай">
</form>

<?php
// Проверка за сортиране
$sortColumn = isset($_GET['sort_column']) ? $_GET['sort_column'] : 'id';
$sortDirection = isset($_GET['sort_direction']) && $_GET['sort_direction'] === 'desc' ? 'DESC' : 'ASC';
$sortQuery = " ORDER BY $sortColumn $sortDirection";

// Приложение на сортирането в заявката
$query .= $sortQuery;
$result = mysqli_query($conn, $query);

// Таблица за извеждане на резултатите
?>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Име</th>
        <th>Адрес</th>
        <th>Телефон</th>
        <th>Вид пица</th>
        <th>Големина на пицата</th>
    </tr>
    <?php
    while ($row = mysqli_fetch_assoc($result)) {
        echo "<tr>";
        echo "<td>{$row['id']}</td>";
        echo "<td>{$row['name']}</td>";
        echo "<td>{$row['address']}</td>";
        echo "<td>{$row['phone']}</td>";
        echo "<td>{$row['pizza_type']}</td>";
        echo "<td>{$row['pizza_size']}</td>";
        echo "</tr>";
    }
    ?>
</table>
<br/>
<form action="admin.php" method="POST">
		<input type="submit" name="go_to_admin" value="Върни се назад">
</form>