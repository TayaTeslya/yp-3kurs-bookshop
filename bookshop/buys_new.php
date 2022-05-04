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
	<title>Новая продажа</title>
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
					<strong>&emsp;	&emsp;	&emsp;	&emsp; 	&emsp; 	&emsp;</strong>
				</div>
				<div>
					<strong class="header_check">Новая продажа</strong>
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
		if (isset($_POST['new_buy'])) {
			if (!empty($_POST['id_client'])) {
				$query = "SELECT * FROM `Clients` WHERE `ID_Client`='$_POST[id_client]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
				if ($_POST['id_client'] == $row['ID_Client']) {
					if ($_POST['date_buy'] == null) {
						$_POST['date_buy'] = date("Y-m-d");
					}
					$query = "INSERT INTO `Buys` (`ID_Client`, `ID_Worker`, `Date_Buy`, `Cost`) VALUES ('$_POST[id_client]', '$_SESSION[iduser]', '$_POST[date_buy]', '0')";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$query = "SELECT * FROM `Buys` WHERE `ID_Client`='$_POST[id_client]' AND `ID_Worker`='$_SESSION[iduser]' AND `Date_Buy`='$_POST[date_buy]' AND `Cost`='0'";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$row = mysqli_fetch_assoc($res);
					$_SESSION['idnewbuy'] = $row['ID_Buy'];
					$message = '<span style="color: green;">Продажа успешно оформлена.</span>';
					echo '<meta http-equiv=Refresh content="1.5; buys_contents_new.php">';
				}
				else {
					$message = '<span style="color: red;">Клиент с таким кодом не зарегистрирован в системе.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните код клиента.</span>';
			}
		}
	?>
	<main style="height: 60vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 300px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Код клиента:</strong>
					<input class="input_pers_acc" type="text" name="id_client" value="<?php echo $_POST['id_client'];?>" autocomplete="off" pattern="^[0-9]+$">
				</div>
				<div class="pers_acc_row">
					<span style="font-size: 20px; color: grey;">*Если клиент не зарегистрирован в системе, используйте код 1</span>
				</div> 
				<div class="pers_acc_row">
					<strong>Дата продажи:</strong>
					<input class="input_pers_acc" type="date" name="date_buy" value="<?php echo $_POST['date_buy'];?>" autocomplete="off">
				</div> 
				<div class="pers_acc_row">
					<span style="font-size: 20px; color: grey;">*Если дата сегодня, можете оставить поле пустым</span>
				</div> 
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="go_to_buy" value="Отменить продажу">
					<input class="submit_pers_acc" type="submit" name="new_buy" value="Оформить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>