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
	<title>Редактировать издательство</title>
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
					<a href="publishers.php" class="username" title="Издательства" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<strong class="header_check">Редактировать издательство</strong>
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
		if (isset($_POST['edit_publisher'])) {
			if (!empty($_POST['name_publisher'])) {
				$query = "SELECT * FROM `Publishers` WHERE `ID_Publisher`<>'$_SESSION[idpublisher]' AND `Name_Publisher`='$_POST[name_publisher]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
				if ($_POST['name_publisher'] != $row['Name_Publisher']) {
					$query = "UPDATE `Publishers` SET `Name_Publisher`='$_POST[name_publisher]' WHERE `ID_Publisher`='$_SESSION[idpublisher]'";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$message = '<span style="color: green;">Издательство успешно изменено.</span>';
					echo '<meta http-equiv=Refresh content="1.5; publishers.php">';
				}
				else {
					$message = '<span style="color: red;">Это издательство уже зарегистрировано.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните название издательства.</span>';
			}
		}
		else {
			$query = "SELECT * FROM `Publishers` WHERE `ID_Publisher`='$_SESSION[idpublisher]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			$_POST['name_publisher'] = $row['Name_Publisher'];
		}
		if (isset($_POST['del_publisher'])) {
			$query = "UPDATE `Books` SET `ID_Publisher`='1' WHERE `ID_Publisher`='$_SESSION[idpublisher]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$query = "DELETE FROM `Publishers` WHERE `ID_Publisher`='$_SESSION[idpublisher]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$message = '<span style="color: green;">Издательство успешно удалено.</span>';
			echo '<meta http-equiv=Refresh content="1.5; publishers.php">';
		}
	?>
	<main style="height: 40vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 120px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong style="width: 200px;">Название издательства:</strong>
					<input class="input_pers_acc" type="text" name="name_publisher" value="<?php echo $_POST['name_publisher'];?>" autocomplete="off" pattern="^[А-Яа-яЁёA-Za-z0-9]{2,50}$">
				</div>
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="del_publisher" value="Удалить">
					<input class="submit_pers_acc" type="submit" name="edit_publisher" value="Изменить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>