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
	<title>Редактирование клиента</title>
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
					<strong class="header_check">Редактирование клиента</strong>
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
		if (isset($_POST['edit_client'])) {
			if (!empty($_POST['name']) AND !empty($_POST['surname']) AND !empty($_POST['lastname']) AND !empty($_POST['phone_number'])) {
				$query = "SELECT * FROM `Clients` WHERE `Phone_Number`='$_POST[phone_number]' AND `ID_Client`<>'$_SESSION[idclient]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
				if ($_POST['phone_number'] != $row['Phone_Number']) {
					$FIO = $_POST['surname'].' '.$_POST['name'].' '.$_POST['lastname'];
					$query = "UPDATE `Clients` SET `FIO`='$FIO', `Phone_Number`='$_POST[phone_number]' WHERE `ID_Client`='$_SESSION[idclient]'";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$message = '<span style="color: green;">Клиент успешно изменен.</span>';
					echo '<meta http-equiv=Refresh content="1.5; clients.php">';
				}
				else {
					$message = '<span style="color: red;">Этот номер уже занят.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните все поля.</span>';
			}
		}
		else {
			$query = "SELECT * FROM `Clients` WHERE `ID_Client`='$_SESSION[idclient]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			$name = explode(" ", $row['FIO']); 
			$_POST['name'] = $name[1];
			$_POST['surname'] = $name[0];
			$_POST['lastname'] = $name[2];
			$_POST['phone_number'] = $row['Phone_Number'];
		}
		if (isset($_POST['del_client'])) {
			$query = "UPDATE `Buys` SET `ID_Client`='1' WHERE `ID_Client`='$_SESSION[idclient]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$query = "DELETE FROM `Clients` WHERE `ID_Client`='$_SESSION[idclient]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$message = '<span style="color: green;">Клиент успешно удален.</span>';
			echo '<meta http-equiv=Refresh content="1.5; clients.php">';
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
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="del_client" value="Удалить">
					<input class="submit_pers_acc" type="submit" name="edit_client" value="Изменить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>