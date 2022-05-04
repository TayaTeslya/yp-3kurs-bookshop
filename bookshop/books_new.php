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
	<title>Новая книга</title>
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
					<strong class="header_check">Новая книга</strong>
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
		if (isset($_POST['new_book'])) {
			if (!empty($_POST['name_book']) AND !empty($_POST['author']) AND !empty($_POST['description'])) {
				if (!empty($_POST['year']) AND !empty($_POST['pages']) AND !empty($_POST['quantity']) AND !empty($_POST['price'])) {
					if ($_POST['select_publisher'] != 'null') {
						$query = "SELECT * FROM `Books` WHERE `ID_Publisher`='$_POST[select_publisher]' AND `Name_Book`='$_POST[name_book]' AND `Author`='$_POST[author]' AND `Year`='$_POST[year]'";
						$res = mysqli_query($link, $query) or die(mysqli_error($link));
						$row = mysqli_fetch_assoc($res);
						if ($row['ID_Book'] == null) {
							$message = '<span style="color: green;">Книга успешно добавлена.</span>';
							$query = "INSERT INTO `Books` (`Name_Book`, `Author`, `Description`, `Year`, `Pages`, `Price`, `Quantity`, `ID_Publisher`) VALUES ('$_POST[name_book]', '$_POST[author]', '$_POST[description]', '$_POST[year]', '$_POST[pages]', '$_POST[price]', '$_POST[quantity]', $_POST[select_publisher])";
							$res = mysqli_query($link, $query) or die(mysqli_error($link));
							echo '<meta http-equiv=Refresh content="1.5; books.php">';
						}
						else {
							$message = '<span style="color: red;">Такая книга уже зарегистрирована.</span>';
						}
					}
					else {
						$message = '<span style="color: red;">Выберите издательство.</span>';
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
	?>
	<main style="height: 80vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc">
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
					<strong>Издательство:</strong>
					<select class="input_pers_acc" name="select_publisher" style="width: 424px;">
						<?php
							$query = "SELECT * FROM `Publishers`";
							$res = mysqli_query($link, $query) or die(mysqli_error($link));
							for ($publisher = []; $row = mysqli_fetch_assoc($res); $publisher[] = $row);
							?> <option value="null">Выберите издательство</option>  <?php
							foreach ($publisher as $value) { 
								if ($value['Name_Publisher'] != 'УДАЛЕНО') {
									if (isset($_POST['new_book']) AND $_POST['select_publisher'] != 'null' AND $value['ID_Publisher'] == $_POST['select_publisher']) {
										echo '<option value="'.$value['ID_Publisher'].'" selected ">'.$value['Name_Publisher'].'</option>';
									}
									else {
										echo '<option value="'.$value['ID_Publisher'].'">'.$value['Name_Publisher'].'</option>';
									}
								}
							}
						?>
					</select>
				</div>
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit">
					<input class="submit_pers_acc" type="submit" name="new_book" value="Добавить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>