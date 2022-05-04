<?php
	require("connection.php"); //соединение с БД
	session_start(); //подключение сессионных переменных
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bad+Script&display=swap" rel="stylesheet"> <!-- подключение шрифта из google fonts -->
	<meta charset="utf-8"> <!-- кодировка -->	
	<link rel="stylesheet" type="text/css" href="css/style.css"> <!-- подключение css -->	
	<title>Авторизация</title>
</head>
<?php 
	if (isset($_POST['auth'])) { //нажата ли кнопка "Войти"
		if (!empty($_POST['login']) AND !empty($_POST['password'])) {
			$_POST['password']=md5($_POST['password']); //шифрование пароля
			$query = "SELECT * FROM `Users` WHERE (`Login`='$_POST[login]' AND `Password`='$_POST[password]')"; //поиск строки с введенными логином и паролями
			$res = mysqli_query($link, $query) or die(mysqli_error($link)); //отправление в БД
			$row = mysqli_fetch_assoc($res); //получение результата в массив
			if ($row['ID_User'] != '') { //если есть айдишник, значит логин и пароль верные
				if ($row['Login'] != 'УДАЛЕНО') {
					$message = '<span style="color: green;">Вы успешно вошли в аккаунт.</span>';
					$_SESSION['auth'] = true; //факт авторизации
					$_SESSION['iduser'] = $row['ID_User']; //айди авторизованного
					$query = "SELECT `Post`, `FIO` FROM `Workers`, `Users` WHERE (`ID_User`=$row[ID_User] AND `Workers`.`ID_Worker`=`Users`.`ID_Worker`)"; //какой пост у работника
					$res = mysqli_query($link, $query) or die(mysqli_error($link)); //отправление в БД
					$row = mysqli_fetch_assoc($res); //получение результата в массив
					if ($row['Post'] == 'Администратор') {
						$_SESSION['post'] = 'admin';
					}
					else {
						if ($row['Post'] != 'УДАЛЕНО') {
							$_SESSION['post'] = 'worker';
						}
					}
					$_SESSION['username'] = $row['FIO']; //ФИО авторизованного
					echo '<meta http-equiv=Refresh content="1.5; index.php">'; // переход на главную страницу
				}
				else {
					$message = '<span style="color: red;">Аккаунт недействительный.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Логин и/или пароль неверный.</span>';
			}
		}
		else {
			$message = '<span style="color: red;">Введите логин и пароль.</span>';
		}
	}
?>
<body background="img/auth_background.jpg" style="display: flex; align-items: center; justify-content: center; width: 100vw; height: 100vh;">
	<main>
		<div class="div_auth_wrap">
			<form method="POST" class="form_auth_column">
				<strong style="font-size: 50px;">Авторизация</strong>
				<input type="text" name="login" class="input_text_auth" autocomplete="off" placeholder="Логин" value="<?php echo $_POST['login'] ?>">
				<input type="password" name="password" class="input_text_auth" style="margin-top:25px;" autocomplete="off" placeholder="Пароль">
				<?php
					echo '<span>&#160;'.$message.'</span>';
				?>	
				<input type="submit" name="auth" class="submit_auth" value="Войти" style="margin-top: 10px;">
			</form>
		</div>
	</main>
</body>
</html>