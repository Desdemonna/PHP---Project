<?php
session_start();

include_once 'connection.php';

if(isset($_POST['submit']))
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	$query = "SELECT id, rank FROM users WHERE username='$username' AND password='$password'";
	$result = mysqli_query($conn,$query);
	$rows = mysqli_num_rows($result);
	if($rows == 1)
	{
		$row = mysqli_fetch_assoc($result);
		$id = $row['id'];
		$rank = $row['rank'];
		$_SESSION['id'] = $id;
		$_SESSION['rank'] = $rank;
		if($rank == 2)
		{
			//header("Location: admin.php");
			echo '<script> location.replace("admin.php")</script>';
		}
		else
		{
			//header("Location: usercp.php");
			echo '<script> location.replace("usercp.php")</script>';
		}
		
	}
	else
	{
		echo "Няма такъв потребител - Сори!";
	}
}
?>

<form name="send" method="POST" action="">
Име:
<input type="text" name="username" />
<br />
Парола:
<input type="password" name="password" />
<br />
<input type="submit" name="submit" value="Влез" />
<br/>
<br/>
<a href="registration.php"><button type="button">Регистрация</button></a>
</form>



