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
	<title>Новый сотрудник</title>
</head>
<body>
	<?php
		if ($_SESSION['post'] != 'admin') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу не администратора, редирект на главную страницу
		}
	?>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<a href="workers.php" class="username" title="Скидки" style="font-size: 40px;">← Назад</a>
				</div>
				<div>
					<strong class="header_check">Новый сотрудник</strong>
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
		if ($_SESSION['post'] != 'admin') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
		}
		if (isset($_POST['new_worker'])) {
			if (!empty($_POST['name']) AND !empty($_POST['surname']) AND !empty($_POST['lastname'])) { //проверка на введение фио
				$query = "SELECT * FROM `Workers` WHERE `Passport`='$_POST[passport]'"; //запрос на поиск такого же паспорта у сотрудников
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$rows = mysqli_fetch_assoc($result);
				if ($_POST['passport'] != $rows['Passport']) {
					if (!empty($_POST['date_birth'])) {
						if (!empty($_POST['phone_number'])) {
							$query = "SELECT * FROM `Workers` WHERE `Phone_Number`='$_POST[phone_number]'"; //запрос на поиск такого же номера телефона у сотрудников
							$result = mysqli_query($link, $query) or die(mysqli_error($link));
							$rows = mysqli_fetch_assoc($result);
							if ($_POST['phone_number'] != $rows['Phone_Number']) {
								if (!empty($_POST['post'])) {
									if (!empty($_POST['salary'])) {
										if (!empty($_POST['login']) AND !empty($_POST['password'])) { //проверка введения логина и пароля
											$query = "SELECT * FROM `Users` WHERE `Login`='$_POST[login]'";
											$result = mysqli_query($link, $query) or die(mysqli_error($link));
											$rows = mysqli_fetch_assoc($result);
											if ($_POST['login'] != $rows['Login']) { //проверка, чтобы логин был уникальным в БД
												$message = '<span style="color: green;">Изменения успешно внесены.</span>';
												if (empty($_POST['date_accept'])) {
													$_POST['date_accept'] = date("Y-m-d");
												}
												$FIO = $_POST['surname'].' '.$_POST['name'].' '.$_POST['lastname'];
												$query = "INSERT INTO `Workers` (`FIO`, `Passport`, `Date_Birth`, `Phone_Number`, `Date_Accept`, `Post`, `Salary`) VALUES ('$FIO', '$_POST[passport]', '$_POST[date_birth]', '$_POST[phone_number]', '$_POST[date_accept]', '$_POST[post]', '$_POST[salary]')";
												$res = mysqli_query($link, $query) or die(mysqli_error($link));
												$password = md5($_POST['password']);
												$query = "SELECT * FROM `Workers` WHERE `Passport`='$_POST[passport]'";
												$res = mysqli_query($link, $query) or die(mysqli_error($link));
												$row = mysqli_fetch_assoc($res);
												$query = "INSERT INTO `Users` (`ID_Worker`, `Login`, `Password`) VALUES ('$row[ID_Worker]', '$_POST[login]', '$password')";
												$res = mysqli_query($link, $query) or die(mysqli_error($link));
												$message = '<span style="color: green">Сотрудник успешно добавлен.</span>';
												echo '<meta http-equiv=Refresh content="1.5; workers.php">';
											}
											else {
												$message = '<span style="color: red;">Выберите другой логин.</span>';
											}
										}
										else {
											$message = '<span style="color: red;">Введите логин и пароль.</span>';
										}
									}
									else {
										$message = '<span style="color: red;">Заполните заработную плату.</span>';
									}
								}
								else {
									$message = '<span style="color: red;">Заполните должность.</span>';
								}
							}
							else {
								$message = '<span style="color: red;">Этот номер телефона уже занят.</span>';
							}
						}
						else {
							$message = '<span style="color: red;">Введите номер телефона.</span>';
						}
					}
					else {
						$message = '<span style="color: red;">Заполните дату рождения сотрудника.</span>';
					}	
				}
				else {
					$message = '<span style="color: red;">Сотрудник с этим паспортом уже добавлен.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните ФИО.</span>';
			}
		}
	?>
	<main style="height: 100vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc"  style="height: 740px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_row">
					<strong>Фамилия:</strong>
					<input class="input_pers_acc" type="text" name="name" value="<?php echo $_POST['name'];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Имя:</strong>
					<input class="input_pers_acc" type="text" name="surname" value="<?php echo $_POST['surname'];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div> 
				<div class="pers_acc_row">
					<strong>Отчество:</strong>
					<input class="input_pers_acc" type="text" name="lastname" value="<?php echo $_POST['lastname'];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Паспорт:</strong>
					<input class="input_pers_acc" type="text" name="passport" value="<?php echo $_POST['passport'];?>" pattern="^[0-9]{10}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Дата рождения:</strong>
					<input class="input_pers_acc" type="date" name="date_birth" value="<?php echo $_POST['date_birth'];?>" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Номер телефона:</strong>
					<input class="input_pers_acc" type="text" placeholder="8 _ _ _ _ _ _ _ _ _" name="phone_number" value="<?php echo $_POST['phone_number'];?>" pattern="8[0-9]{10}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Дата принятия:</strong>
					<input class="input_pers_acc" type="date" name="date_accept" value="<?php echo $_POST['date_accept'];?>" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<span style="font-size: 20px; color: grey;">*Если дата сегодня, можете оставить поле пустым</span>
				</div> 
				<div class="pers_acc_row">
					<strong>Должность:</strong>
					<input class="input_pers_acc" type="text" name="post" value="<?php echo $_POST['post'];?>" autocomplete="off" pattern="^[А-Яа-яЁё]{2,20}$">
				</div>
				<div class="pers_acc_row">
					<strong>Заработная плата (₽):</strong>
					<input class="input_pers_acc" type="text" name="salary" value="<?php echo $_POST['salary'];?>" placeholder="0000.00" pattern="^[0-9]{4,15}.[0-9]{2}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Логин:</strong>
					<input class="input_pers_acc" type="text" name="login" value="<?php echo $_POST['login'];?>" pattern="^[0-9A-Za-z]{5,25}$" autocomplete="off" placeholder="5-25 символов">
				</div>
				<div class="pers_acc_row">
					<strong>Пароль:</strong>
					<input class="input_pers_acc" type="password" name="password" pattern="^[0-9A-Za-z!.!?*#]{5,20}$" autocomplete="off" placeholder="5-20 символов, цифры, латиница, символы !?*#">
				</div>
				<div class="pers_acc_submit">
					<input class="submit_pers_acc" type="submit" name="new_worker" value="Добавить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>