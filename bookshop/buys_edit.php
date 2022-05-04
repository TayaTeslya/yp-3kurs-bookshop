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
	<title>Редактировать продажу</title>
</head>
<body>
	<?php
		if ($_SESSION['post'] != 'admin') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
		}
	?>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<a href="buys.php" class="username" title="Продажи" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<strong class="header_check">Редактировать продажу</strong>
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
		$query = "SELECT * FROM `Buys` WHERE `ID_Buy`='$_SESSION[idbuy]'";
		$res = mysqli_query($link, $query) or die(mysqli_error($link));
		$row = mysqli_fetch_assoc($res);
		$_POST['id_client'] = $row['ID_Client'];
		$_POST['date_buy'] = $row['Date_Buy'];
		if (isset($_POST['del_buy'])) {
			$query = "SELECT * FROM `Buys` WHERE `ID_Buy`='$_SESSION[idbuy]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			if ($row['ID_Buy'] != null) {
				$query = "DELETE FROM `Buys_Contents` WHERE `ID_Buy`='$row[ID_Buy]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$query = "DELETE FROM `Buys` WHERE `ID_Buy`='$row[ID_Buy]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
			}
			$message = '<span style="color: red;">Продажа успешно удалена.</span>';
			echo '<meta http-equiv=Refresh content="1.5; buys.php">';
		}
	?>
	<main style="height: 60vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 200px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Код клиента:</strong>
					<input class="input_pers_acc" type="text" name="id_client" value="<?php echo $_POST['id_client'];?>" autocomplete="off" pattern="^[0-9]+$" readonly>
				</div>
				<div class="pers_acc_row">
					<strong>Дата продажи:</strong>
					<input class="input_pers_acc" type="date" name="date_buy" value="<?php echo $_POST['date_buy'];?>" autocomplete="off" readonly>
				</div> 
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="del_buy" value="Удалить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>