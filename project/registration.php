<?php
include_once 'connection.php';

if (isset($_POST['register'])) {
    // Обработка на регистрацията
    $username = $_POST['username'];
    $password = $_POST['password'];
    $rank = ($_POST['rank'] == 'admin') ? 2 : 1; // Присвояване на стойности 1 и 2 в зависимост от избора

    $query = "INSERT INTO users (username, password, rank) VALUES ('$username', '$password', '$rank')";
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "Регистрацията е успешна!";
    } else {
        echo "Възникна проблем при регистрацията.";
    }
}
?>

<!-- Форма за регистрация -->
<form name="register" method="POST" action="">
    Име:
    <input type="text" name="username" required />
    <br />
    Парола:
    <input type="password" name="password" required />
    <br />
    Ранг:
    <select name="rank" required>
        <option value="user">User</option>
        <option value="admin">Admin</option>
    </select>
    <br />
    <input type="submit" name="register" value="Регистрация" />
</form>
<form action="index.php" method="POST">
		<input type="submit" name="go_to_index" value="Върни се в началото">
</form>