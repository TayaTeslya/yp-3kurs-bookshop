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
	<title>Новый состав продажи</title>
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
					<strong class="header_check">Новый состав продажи</strong>
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
		if (isset($_POST['new_buy_content'])) {
			if (!empty($_POST['id_book']) AND !empty($_POST['quantity'])) {
				$query = "SELECT * FROM `Books` WHERE `ID_Book`='$_POST[id_book]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
				if ($_POST['id_book'] == $row['ID_Book']) {
					if ($_POST['quantity'] <= $row['Quantity']) {
						$query = "SELECT * FROM `Buys_Contents` WHERE `ID_Buy`='$_SESSION[idnewbuy]' AND `ID_Book`='$_POST[id_book]'";
						$res = mysqli_query($link, $query) or die(mysqli_error($link));
						$row = mysqli_fetch_assoc($res);
						if ($_POST['id_book'] != $row['ID_Book']) {
							$query = "INSERT INTO `Buys_Contents` (`ID_Buy`, `ID_Book`, `Quantity`) VALUES ('$_SESSION[idnewbuy]', '$_POST[id_book]', '$_POST[quantity]')";
							$res = mysqli_query($link, $query) or die(mysqli_error($link));
							$message = '<span style="color: green;">Книга успешно добавлена в состав заказа.</span>';
							echo '<meta http-equiv=Refresh content="1.5; buys_contents_new_or_not.php">';
						}
						else {
							$message = '<span style="color: red;">Эта книга уже добавлена в заказ.</span>';
						}
					}
					else {
						$message = '<span style="color: red;">На складе есть только '.$row['Quantity'].' книг(и).</span>';
					}
				}
				else {
					$message = '<span style="color: red;">Книга с таким кодом не зарегистрирована.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните все поля.</span>';
			}
		}
		if (isset($_POST['go_to_buy'])) {
			echo '<meta http-equiv=Refresh content="0; buys.php">';
		}
	?>
	<main style="height: 40vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 200px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Код книги:</strong>
					<input class="input_pers_acc" type="text" name="id_book" value="<?php echo $_POST['id_book'];?>" autocomplete="off" pattern="^[0-9]+$">
				</div>
				<div class="pers_acc_row">
					<strong>Кол-во:</strong>
					<input class="input_pers_acc" type="text" name="quantity" value="<?php echo $_POST['quantity'];?>" autocomplete="off" pattern="^[0-9]+$">
				</div> 
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="go_to_buy" value="Отменить продажу">
					<input class="submit_pers_acc" type="submit" name="new_buy_content" value="Добавить в состав заказа">
				</div>
			</form>
		</div>
	</main>
	<?php 
		if (isset($_POST['go_to_buy'])) {
			$query = "SELECT * FROM `Buys`, `Clients` WHERE `Buys`.`ID_Client`=`Clients`.`ID_Client` AND `ID_Buy`='$_SESSION[idnewbuy]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			if ($row['ID_Buy'] != null) {
				$query = "DELETE FROM `Buys_Contents` WHERE `ID_Buy`='$row[ID_Buy]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$query = "DELETE FROM `Buys` WHERE `ID_Buy`='$row[ID_Buy]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
			}
			echo '<meta http-equiv=Refresh content="0; buys.php">';
		}
	?>
</body>
</html>