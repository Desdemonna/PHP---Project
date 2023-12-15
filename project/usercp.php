<?php
session_start();
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
</head>
<body>

<?php

include_once 'connection.php';

if (isset($_SESSION['rank']) && isset($_SESSION['id'])) {
    if ($_SESSION['rank'] == 1) {
        echo '<h2>Добре дошли!</h2>';
    } else {
        header("Location: no_access.php");
        exit(); 
    }
}

if (isset($_POST['submit_order'])) {
    $pizza_type = isset($_POST['pizza_type']) ? $_POST['pizza_type'] : '';
    $pizza_size = isset($_POST['pizza_size']) ? $_POST['pizza_size'] : '';

    if ($pizza_type !== '' && $pizza_size !== '') {
        $checkPizzaQuery = "SELECT id FROM pizza_categories WHERE name = '$pizza_type'";
        $checkSizeQuery = "SELECT id FROM pizza_size WHERE size = '$pizza_size'";
        $checkPizzaResult = mysqli_query($conn, $checkPizzaQuery);
        $checkSizeResult = mysqli_query($conn, $checkSizeQuery);

        if (mysqli_num_rows($checkPizzaResult) > 0 && mysqli_num_rows($checkSizeResult) > 0) {
            $pizza_type_row = mysqli_fetch_assoc($checkPizzaResult);
            $pizza_size_row = mysqli_fetch_assoc($checkSizeResult);

            $pizza_type_id = $pizza_type_row['id'];
            $pizza_size_id = $pizza_size_row['id'];

            $name = mysqli_real_escape_string($conn, $_POST['name']);
            $address = mysqli_real_escape_string($conn, $_POST['address']);
            $phone = mysqli_real_escape_string($conn, $_POST['phone']);

            $insertOrderQuery = "INSERT INTO orders (name, address, phone, pizza_type_id, pizza_size_id) VALUES ('$name', '$address', '$phone', '$pizza_type_id', '$pizza_size_id')";
            $insertOrderResult = mysqli_query($conn, $insertOrderQuery);

            if ($insertOrderResult) {
                echo "Поръчката е добавена успешно!";
            } else {
                echo "Възникна проблем при добавянето на поръчката. Грешка: " . mysqli_error($conn);
            }
        } else {
            echo "Избраните вид пица или размер не съществуват.";
        }
    } else {
        echo "Моля, изберете вид пица и размер.";
    }
}
?>

<form id="orderForm" action="usercp.php" method="POST">
    Име:
    <input type="text" name="name" required>
    <br />
    Адрес:
    <textarea name="address" required></textarea>
    <br />
    Телефонен номер:
    <input type="text" name="phone" required>
    <br />
    Вид пица:
    <select name="pizza_type" required>
        <?php
        $pizzaTypesQuery = "SELECT name FROM pizza_categories";
        $pizzaTypesResult = mysqli_query($conn, $pizzaTypesQuery);

        while ($row = mysqli_fetch_assoc($pizzaTypesResult)) {
            echo "<option value='{$row['name']}'>{$row['name']}</option>";
        }
        ?>
    </select>
    <br />
    Големина на пицата:
    <select name="pizza_size" required>
        <?php
        $pizzaSizesQuery = "SELECT size FROM pizza_size";
        $pizzaSizesResult = mysqli_query($conn, $pizzaSizesQuery);

        while ($row = mysqli_fetch_assoc($pizzaSizesResult)) {
            echo "<option value='{$row['size']}'>{$row['size']}</option>";
        }
        ?>
    </select>
    <br />
    <input type="submit" name="submit_order" value="Поръчай">
    <br />
</form>
<form action="index.php" method="POST">
    <input type="submit" name="go_to_index" value="Върни се в началото">
</form>

<script>
$(document).ready(function(){
    $("#orderForm").submit(function(e){
        e.preventDefault(); // Предотвратява стандартното предаване на формата

        $.ajax({
            type: "POST",
            url: "usercp.php",
            data: {
                name: $("input[name='name']").val(),
                address: $("textarea[name='address']").val(),
                phone: $("input[name='phone']").val(),
                pizza_type: $("select[name='pizza_type']").val(),
                pizza_size: $("select[name='pizza_size']").val(),
                submit_order: 1
            },
            success: function(response){
                alert('Успешно направена поръчка!');
                // Изчистваме полетата след успешна поръчка
                $("#orderForm")[0].reset();
            }
        });
    });
});
</script>

</body>
</html>