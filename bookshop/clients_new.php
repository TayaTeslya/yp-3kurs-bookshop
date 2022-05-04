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
	<title>Новый клиент</title>
</head>
<body>
	<?php
		if ($_SESSION['auth'] != 'true') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
		}
	?>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<a href="clients.php" class="username" title="Клиенты" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<strong class="header_check">Новый клиент</strong>
				</div>
				<div>
					<div class="username">
						<strong>&emsp;	&emsp;	&emsp;	&emsp; 	&emsp; 	&emsp;</strong>
					</div>
				</div>
			</div>
		</div>
	</header>
	<?php 
		if (isset($_POST['new_client'])) {
			if (!empty($_POST['name']) AND !empty($_POST['surname']) AND !empty($_POST['lastname']) AND !empty($_POST['phone_number'])) {
				$query = "SELECT * FROM `Clients` WHERE `Phone_Number`='$_POST[phone_number]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
				if ($_POST['phone_number'] != $row['Phone_Number']) {
					$FIO = $_POST['surname'].' '.$_POST['name'].' '.$_POST['lastname'];
					$query = "INSERT INTO `Clients` (`FIO`, `Phone_Number`) VALUES ('$FIO', '$_POST[phone_number]')";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$message = '<span style="color: green;">Клиент успешно добавлен.</span>';
					echo '<meta http-equiv=Refresh content="1.5; clients.php">';
				}
				else {
					$message = '<span style="color: red;">Клиент с этим номером уже зарегистрирован.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните все поля.</span>';
			}
		}
	?>
	<main style="height: 55vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 300px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Имя:</strong>
					<input class="input_pers_acc" type="text" name="name" value="<?php echo $_POST['name'];?>" autocomplete="off" pattern="^[А-Яа-яЁё]{2,80}$">
				</div>
				<div class="pers_acc_row">
					<strong>Фамилия:</strong>
					<input class="input_pers_acc" type="text" name="surname" value="<?php echo $_POST['surname'];?>" autocomplete="off" pattern="^[А-Яа-яЁё]{2,80}$">
				</div>
				<div class="pers_acc_row">
					<strong>Отчество:</strong>
					<input class="input_pers_acc" type="text" name="lastname" value="<?php echo $_POST['lastname'];?>" autocomplete="off" pattern="^[А-Яа-яЁё]{2,80}$">
				</div>
				<div class="pers_acc_row">
					<strong>Номер телефона:</strong>
					<input class="input_pers_acc" type="text" name="phone_number" value="<?php echo $_POST['phone_number'];?>" autocomplete="off" pattern="^8[0-9]{10}$">
				</div> 
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit">
					<input class="submit_pers_acc" type="submit" name="new_client" value="Добавить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>