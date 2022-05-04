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
	<title>Новая скидка</title>
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
					<strong class="header_check">Новая скидка</strong>
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
		if (isset($_POST['new_discount'])) {
			if (!empty($_POST['id_book']) AND !empty($_POST['discount'])) {
				$query = "SELECT * FROM `Books` WHERE `ID_Book`='$_POST[id_book]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
				if ($_POST['id_book'] == $row['ID_Book']) {
					$query = "SELECT * FROM `Discounts` WHERE `ID_Book`='$_POST[id_book]'";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$row = mysqli_fetch_assoc($res);
					if ($_POST['id_book'] != $row['ID_Book']) {
						$query = "INSERT INTO `Discounts` (`ID_Book`, `Discount`) VALUES ('$_POST[id_book]', '$_POST[discount]')";
						$res = mysqli_query($link, $query) or die(mysqli_error($link));
						$message = '<span style="color: green;">Скидка успешно добавлена.</span>';
						echo '<meta http-equiv=Refresh content="1.5; discounts.php">';
					}
					else {
						$message = '<span style="color: red;">Скидка на эту книгу уже существует.</span>';
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
	?>
	<main style="height: 40vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 200px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Код книги:</strong>
					<input class="input_pers_acc" type="text" name="id_book" value="<?php echo $_POST['id_book'];?>" autocomplete="off" pattern="^[0-9]+$">
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
				<div class="pers_acc_submit">
					<input class="submit_pers_acc" type="submit" name="new_discount" value="Добавить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>