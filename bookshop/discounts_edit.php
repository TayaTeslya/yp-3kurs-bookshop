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
	<title>Редактировать скидку</title>
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
					<a href="discounts.php" class="username" title="Скидки" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<strong class="header_check">Редактировать скидку</strong>
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
		$query = "SELECT * FROM `Discounts` WHERE `ID_Discount`='$_SESSION[iddiscount]'";
		$res = mysqli_query($link, $query) or die(mysqli_error($link));
		$row = mysqli_fetch_assoc($res);
		if (isset($_POST['edit_discount'])) {
			if (!empty($_POST['discount'])) {
				if ($_POST['discount']  != '0') {
					if ($_POST['discount']  != '00') {
						if ($row['ID_Discount'] != null) {
							$query = "UPDATE `Discounts` SET `Discount`='$_POST[discount]' WHERE `ID_Discount`='$_SESSION[iddiscount]'";
						}
						else {
							$query = "INSERT INTO `Discounts` (`ID_Book`, `Discount`) VALUES ('$_SESSION[idbook]', '$_POST[discount]')";
						}
					}
					else {
						$query = "DELETE FROM `Discounts` WHERE `ID_Discount`='$_SESSION[iddiscount]'";
					}
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$message = '<span style="color: green;">Скидка успешно изменена.</span>';
					echo '<meta http-equiv=Refresh content="1.5; discounts.php">';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните скидку (если ее нет, введите 00).</span>';
			}
		}
		else {
			$_POST['id_book'] = $row['ID_Book'];
			$_POST['discount'] = $row['Discount'];
		}
		if (isset($_POST['del_discount'])) {
			$query = "DELETE FROM `Discounts` WHERE `ID_Discount`='$_SESSION[iddiscount]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$message = '<span style="color: green;">Скидка успешно удалена.</span>';
			echo '<meta http-equiv=Refresh content="1.5; discounts.php">';
		}
	?>
	<main style="height: 40vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 200px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Код книги:</strong>
					<input class="input_pers_acc" type="text" name="id_book" value="<?php echo $_POST['id_book'];?>" autocomplete="off" pattern="^[0-9]+$" readonly>
				</div>
				<div class="pers_acc_row">
					<strong>Скидка (%):</strong>
					<input class="input_pers_acc" type="text" name="discount" value="<?php echo $_POST['discount'];?>" autocomplete="off" pattern="^[0-9]{1,2}$">
				</div> 
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="del_discount" value="Удалить">
					<input class="submit_pers_acc" type="submit" name="edit_discount" value="Изменить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>