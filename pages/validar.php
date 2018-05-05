<?php
	session_start(); 
?>
<!DOCTYPE html>
<html>
<head>
<head>
	<title>Validando...</title>
	<meta charset="utf-8">
</head>
</head>
<body>
		<?php
		$user='root';
		$pass='';
		$db='proyectofinal';
		$conn= new mysqli('localhost',$user, $pass, $db);
			if(isset($_POST['login'])){
				$usuario = $_POST['user'];
				$pw = $_POST['pw'];
				$log = $conn->query("SELECT * FROM admin WHERE user='$usuario' AND pw='$pw' ");
				if (mysqli_num_rows($log) > 0) {
					$row = $log->fetch_array();
					$_SESSION["user"] = $row['user']; 
					echo '<script> window.location="Pagetheme/dashboard.html"; </script>';
				}
				else{
					echo '<script> alert("Usuario o contraseña incorrectos.");</script>';
					echo '<script> window.location="index.html"; </script>';
				}
			}
		?>	
</body>
</html>