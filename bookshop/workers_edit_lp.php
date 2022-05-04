<?php 
	require("connection.php"); //соединение с БД
	session_start(); //подключение сессионных переменных
?>
<!DOCTYPE html>
<html>
<head>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bad+Script&display=swap" rel="stylesheet"> <!-- подключение шрифта из google fonts -->
	<meta charset="utf-8"> <!-- кодировка -->	
	<link rel="stylesheet" type="text/css" href="css/style.css"> <!-- подключение css -->	
	<title>Редактирование логина и пароля сотрудника</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<a href="workers_edit.php" class="username" title="Редактировать сотрудника" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<strong class="header_check">Редактирование логина и пароля сотрудника</strong>
				</div>
			</div>
		</div>
	</header>
	<?php
		if ($_SESSION['post'] != 'admin') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
		}
		if (isset($_POST['edit_worker'])) {
			if (!empty($_POST['login']) AND !empty($_POST['password'])) { //проверка введения логина и пароля
				$query = "SELECT * FROM `Users` WHERE `Login`='$_POST[login]' AND `ID_Worker`<>'$_SESSION[idworker]'";
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$rows = mysqli_fetch_assoc($result);
				if ($_POST['login'] != $rows['Login']) { //проверка, чтобы логин был уникальным в БД
					$message = '<span style="color: green;">Изменения успешно внесены.</span>';
					$password = md5($_POST['password']); //шифрование пароля
					$query = "UPDATE `Users` SET `Login`='$_POST[login]', `Password`='$password' WHERE `ID_Worker`='$_SESSION[idworker]'";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$message = '<span style="color: green">Логин и пароль успешно изменены.</span>';
					echo '<meta http-equiv=Refresh content="1.5; workers.php">';
				}
				else {
					$message = '<span style="color: red;">Выберите другой логин.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Введите логин и пароль.</span>';
			}
		}
		else {
			$query = "SELECT * FROM `Users` WHERE `ID_Worker`='$_SESSION[idworker]'"; //запрос на поиск такого же паспорта у сотрудников
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			$_POST['login'] = $row['Login'];
		}
	?>
	<main style="height: 50vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc"  style="height: 200px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Логин:</strong>
					<input class="input_pers_acc" type="text" name="login" value="<?php echo $_POST['login'];?>" pattern="^[0-9A-Za-z]{5,25}$" autocomplete="off" placeholder="5-25 символов">
				</div>
				<div class="pers_acc_row">
					<strong>Пароль:</strong>
					<input class="input_pers_acc" type="password" name="password" pattern="^[0-9A-Za-z!?*#]{5,20}$" autocomplete="off" placeholder="5-20 символов, цифры, латиница, символы !?*#">
				</div>
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit">
					<input class="submit_pers_acc" type="submit" name="edit_worker" value="Изменить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>