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
	<title>Оформление продажи</title>
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
					<strong class="header_check">Приступить к оформлению продажи?</strong>
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
		if (isset($_POST['go_to_new_buy'])) {
			$_SESSION['idbuy'] = $_SESSION['idnewbuy'];
			$query = "SELECT * FROM `Buys_Contents` WHERE `ID_Buy`='$_SESSION[idbuy]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row);
			$cost = 0;
			foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
				$query = "SELECT * FROM `Books` WHERE `ID_Book`='$value[ID_Book]'";
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($result);
				$query = "SELECT * FROM `Discounts` WHERE `ID_Book`='$row[ID_Book]'";
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$rows =  mysqli_fetch_assoc($result);
				if ($rows['Discount'] != null) {
					$cost = $cost + ($value['Quantity'] * ($row['Price'] - ($row['Price'] * $rows['Discount'] / 100)));
				}
				else {
					$cost = $cost + ($row['Price'] * $value['Quantity']);
				}
				$updq = $row['Quantity'] - $value['Quantity'];
				$query = "UPDATE `Books` SET `Quantity`='$updq' WHERE `ID_Book`='$row[ID_Book]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
			}
			$query = "SELECT * FROM `Buys` WHERE `ID_Buy`='$_SESSION[idbuy]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			if ($row['ID_Client'] != '1') {
				$cost = $cost - ($cost * 5 / 100);
			}
			$query = "UPDATE `Buys` SET `Cost`='$cost' WHERE `ID_Buy`='$_SESSION[idbuy]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$message = '<span style="color: green;">Заказ успешно оформлен.</span>';
			echo '<meta http-equiv=Refresh content="1.5; cheque.php">';
		}
		if (isset($_POST['go_to_new_buy_content'])) {
			echo '<meta http-equiv=Refresh content="0; buys_contents_new.php">';
		}
		if (isset($_POST['go_to_buy'])) {
			echo '<meta http-equiv=Refresh content="0; buys.php">';
		}
	?>
	<main style="height: 60vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 150px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<div class="pers_acc_submit">
						<input class="submit_pers_acc" type="submit" name="go_to_new_buy" value="Оформить">
					</div>
					<div class="pers_acc_submit">
						<input class="submit_pers_acc" type="submit" name="go_to_new_buy_content" value="Добавить еще книгу">
					</div>
				</div>
				<br>
				<div class="pers_acc_row">
					<div class="pers_acc_submit">
						<input class="submit_red_hover" type="submit" name="go_to_buy" value="Отменить продажу">
					</div>
				</div> 
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
			</form>
		</div>
	</main>
</body>
</html>