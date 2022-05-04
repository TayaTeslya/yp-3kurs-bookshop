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
	<title>Редактировать книгу</title>
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
					<a href="books.php" class="username" title="Книги" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<strong class="header_check">Редактировать книгу</strong>
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
		if (isset($_POST['edit_book'])) {
			if (!empty($_POST['name_book']) AND !empty($_POST['author']) AND !empty($_POST['description'])) {
				if (!empty($_POST['year']) AND !empty($_POST['pages']) AND !empty($_POST['quantity']) AND !empty($_POST['price'])) {
					$query = "SELECT * FROM `Publishers` WHERE `ID_Publisher`='$_POST[id_publisher]'";
					$res = mysqli_query($link, $query) or die(mysqli_error($link));
					$row = mysqli_fetch_assoc($res);
					if ($_POST['id_publisher'] == $row['ID_Publisher']) {
						$query = "SELECT * FROM `Books` WHERE `ID_Publisher`='$_POST[select_publisher]' AND `Name_Book`='$_POST[name_book]' AND `Author`='$_POST[author]' AND `Year`='$_POST[year]'";
						$res = mysqli_query($link, $query) or die(mysqli_error($link));
						$row = mysqli_fetch_assoc($res);
						if ($row['ID_Book'] == null) {
							if (!empty($_POST['discount'])) {
								$query = "UPDATE `Books` SET `Name_Book`='$_POST[name_book]', `Author`='$_POST[author]', `Description`='$_POST[description]', `Year`='$_POST[year]', `Pages`='$_POST[pages]', `Price`='$_POST[price]', `Quantity`='$_POST[quantity]', `ID_Publisher`='$_POST[id_publisher]' WHERE `ID_Book`='$_SESSION[idbook]'";
								$res = mysqli_query($link, $query) or die(mysqli_error($link));
								$query = "SELECT * FROM `Discounts` WHERE `ID_Book`='$_SESSION[idbook]'";
								$res = mysqli_query($link, $query) or die(mysqli_error($link));
								$row = mysqli_fetch_assoc($res);
								if ($_POST['discount']  != '0' AND $_POST['discount']  != '00') {
									if ($row['ID_Discount'] != null) {
										$query = "UPDATE `Discounts` SET `Discount`='$_POST[discount]' WHERE `ID_Book`='$_SESSION[idbook]'";
									}
									else {
										$query = "INSERT INTO `Discounts` (`ID_Book`, `Discount`) VALUES ('$_SESSION[idbook]', '$_POST[discount]')";
									}
								}
								else {
									if ($row['ID_Discount'] != null) {
										$query = "DELETE FROM `Discounts` WHERE `ID_Book`='$_SESSION[idbook]'";
									}
								}
								$res = mysqli_query($link, $query) or die(mysqli_error($link));
								$message = '<span style="color: green;">Книга успешно изменена.</span>';
								echo '<meta http-equiv=Refresh content="1.5; books.php">';
							}
							else {
								$message = '<span style="color: red;">Заполните скидку (если скидки нет, введите 00).</span>';
							}
						}
						else {
							$message = '<span style="color: red;">Такая книга уже зарегистрирована.</span>';
						}
					}
					else {
						$message = '<span style="color: red;">Издательство с таким кодом не зарегистрировано.</span>';
					}
				}
				else {
					$message = '<span style="color: red;">Заполните год, кол-во страниц, цену и кол-во экземпляров.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните название, автора и описание.</span>';
			}
		}
		else {
			$query = "SELECT * FROM `Books` WHERE `ID_Book`='$_SESSION[idbook]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			$_POST['name_book'] = $row['Name_Book'];
			$_POST['author'] = $row['Author'];
			$_POST['description'] = $row['Description'];
			$_POST['pages'] = $row['Pages'];
			$_POST['price'] = $row['Price'];
			$_POST['quantity'] = $row['Quantity'];
			$_POST['id_publisher'] = $row['ID_Publisher'];
			$_POST['year'] = $row['Year'];
			$query = "SELECT * FROM `Discounts` WHERE `ID_Book`='$_SESSION[idbook]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			$_POST['discount'] = $row['Discount'];
		}
		if (isset($_POST['del_book'])) {
			$query = "UPDATE `Buys_Contents` SET `ID_Book`='1' WHERE `ID_Book`='$_SESSION[idbook]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$query = "DELETE FROM `Discounts` WHERE `ID_Book`='$_SESSION[idbook]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$query = "DELETE FROM `Books` WHERE `ID_Book`='$_SESSION[idbook]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$message = '<span style="color: green;">Книга успешно удалена.</span>';
			echo '<meta http-equiv=Refresh content="1.5; books.php">';
		}
	?>
	<main style="height: 83vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc" style="height: 600px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Название книги:</strong>
					<input class="input_pers_acc" type="text" name="name_book" value="<?php echo $_POST['name_book'];?>" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Автор:</strong>
					<input class="input_pers_acc" type="text" name="author" value="<?php echo $_POST['author'];?>" autocomplete="off">
				</div> 
				<div class="pers_acc_row">
					<strong>Описание:</strong>
					<textarea class="input_pers_acc" name="description" autocomplete="off"><?php echo $_POST['description'];?></textarea>
				</div>
				<div class="pers_acc_row">
					<strong>Год:</strong>
					<input class="input_pers_acc" type="text" name="year" value="<?php echo $_POST['year'];?>" pattern="^[0-9]{3,4}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Кол-во страниц:</strong>
					<input class="input_pers_acc" type="text" name="pages" pattern="^[0-9]{1,5}$" value="<?php echo $_POST['pages'];?>" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Цена:</strong>
					<input class="input_pers_acc" type="text" placeholder="00.00" name="price" value="<?php echo $_POST['price'];?>" pattern="^[0-9]{2,6}.[0-9]{2}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Кол-во:</strong>
					<input class="input_pers_acc" type="text" name="quantity" pattern="^[0-9]{1,5}$" value="<?php echo $_POST['quantity'];?>" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Код издательства:</strong>
					<input class="input_pers_acc" type="text" name="id_publisher" pattern="^[0-9]{1,5}$" value="<?php echo $_POST['id_publisher'];?>" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Скидка (%):</strong>
					<input class="input_pers_acc" type="text" name="discount" pattern="^[0-9]{1,2}$" value="<?php echo $_POST['discount'];?>" autocomplete="off">
				</div>
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="del_book" value="Удалить">
					<input class="submit_pers_acc" type="submit" name="edit_book" value="Изменить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>