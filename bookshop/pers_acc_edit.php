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
	<title>Личный кабинет</title>
</head>
<body>
	<?php
		if ($_SESSION['auth'] != 'true') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
		}
		$query = "SELECT * FROM `Users` WHERE `ID_User`='$_SESSION[iduser]'";
		$res = mysqli_query($link, $query) or die(mysqli_error($link)); //отправление в БД
		$row = mysqli_fetch_assoc($res); //получение результата в массив
		if (isset($_POST['edit_pers_acc'])) { //если кнопка изменить нажата
			if (!empty($_POST['login']) AND !empty($_POST['password'])) { //проверка введения логина и пароля
				$query = "SELECT * FROM `Users` WHERE `ID_User`<>'$_SESSION[iduser]' AND `Login`='$_POST[login]'";
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$rows = mysqli_fetch_assoc($result);
				if ($_POST['login'] != $rows['Login']) { //проверка, чтобы логин был уникальным в БД
					$password = md5($_POST['password']); //шифрование пароля
					$query = "UPDATE `Users` SET `Login`='$_POST[login]', `Password`='$password' WHERE `ID_User`='$_SESSION[iduser]'";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$message = '<span style="color: green;">Изменения успешно внесены.</span>';
					
					echo '<meta http-equiv=Refresh content="1.5; pers_account.php">'; //редирект обратно в личный кабинет
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
			$_POST['login'] = $row['Login'];
		}
	?>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<a href="pers_account.php" class="username" title="Личный кабинет" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<div class="username">
						<a href="logout.php" class="username" title="Выйти из аккаунта" style="font-size: 40px;">Выйти</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<main style="height: 80vh; display: flex; justify-content: center;">
		<div class="div_pers_acc_edit">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Логин:</strong>
					<input class="input_pers_acc" type="text" name="login" value="<?php echo $_POST['login'];?>" pattern="^[0-9A-Za-z]{5,25}$" autocomplete="off" placeholder="5-25 символов">
				</div>
				<div class="pers_acc_row">
					<strong>Пароль:</strong>
					<input class="input_pers_acc" type="password" name="password" pattern="^[0-9A-Za-z!.!?*#]{5,20}$" autocomplete="off" placeholder="5-20 символов, цифры, латиница, символы !?*#">
				</div> 
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit">
					<input class="submit_pers_acc" type="submit" name="edit_pers_acc" value="Изменить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>