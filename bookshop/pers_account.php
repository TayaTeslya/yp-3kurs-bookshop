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
	<title>Личный кабинет</title>
</head>
<body>
	<?php
		if ($_SESSION['auth'] != 'true') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
		}
		$query = "SELECT * FROM `Users`, `Workers` WHERE (`ID_User`='$_SESSION[iduser]' AND `Workers`.`ID_Worker`=`Users`.`ID_Worker`)";
		$res = mysqli_query($link, $query) or die(mysqli_error($link)); //отправление в БД
		$row = mysqli_fetch_assoc($res); //получение результата в массив
		$name = explode(" ", $row['FIO']); //разбиение ФИО на ф, и, о по пробелу
		if (isset($_POST['edit_pers_acc'])) {
			$name[0] = $_POST['name']; //для отображения в полях ввода при нажатии на кнопку, т.е. при отправке формы (страница перезагружается)
			$name[1] = $_POST['surname'];
			$name[2] = $_POST['lastname'];
			if (!empty($name[0]) AND !empty($name[1]) AND !empty($name[2])) { //проверка на введение фио
				$query = "SELECT * FROM `Workers` WHERE `Passport`='$_POST[passport]' AND `ID_Worker`<>'$row[ID_Worker]'"; //запрос на поиск такого же паспорта у сотрудников
				$idwork = $row['ID_Worker'];
				$result = mysqli_query($link, $query) or die(mysqli_error($link));
				$rows = mysqli_fetch_assoc($result);
				if ($_POST['passport'] != $rows['Passport']) {
					if (!empty($_POST['phone_number'])) {
						$query = "SELECT * FROM `Workers` WHERE `Phone_Number`='$_POST[phone_number]' AND `ID_Worker`<>'$row[ID_Worker]'"; //запрос на поиск такого же номера телефона у сотрудников
						$result = mysqli_query($link, $query) or die(mysqli_error($link));
						$rows = mysqli_fetch_assoc($result);
						if ($_POST['phone_number'] != $rows['Phone_Number']) {
							if (!empty($_POST['post'])) {
								if (!empty($_POST['salary'])) {
									$_SESSION['username'] = $name[0].' '.$name[1].' '.$name[2];
									$query = "UPDATE `Workers` SET `FIO`='$_SESSION[username]', `Passport`='$_POST[passport]', `Date_Birth`='$_POST[date_birth]', `Phone_Number`='$_POST[phone_number]',
									`Date_Accept`='$_POST[date_accept]', `Post`='$_POST[post]', `Salary`='$_POST[salary]' WHERE `ID_Worker`='$idwork'";
									$res = mysqli_query($link, $query) or die(mysqli_error($link));
									$message = '<span style="color: green">Информация успешно изменена.</span>';
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
					$message = '<span style="color: red;">Сотрудник с этим паспортом уже добавлен.</span>';
				}
			}
			else {
				$message = '<span style="color: red;">Заполните ФИО.</span>';
			}
		}
		else {
			$_POST['passport'] = $row['Passport'];
			$_POST['date_birth'] = $row['Date_Birth'];
			$_POST['date_accept'] = $row['Date_Accept'];
			$_POST['phone_number'] = $row['Phone_Number'];
			$_POST['post'] = $row['Post'];
			$_POST['salary'] = $row['Salary'];
		}
	?>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<a href="index.php" class="username" title="Главная страница" style="font-size: 40px;">← Назад</a>
				</div>
				<div class="username">
					<a href="pers_acc_edit.php" class="username" title="Сменить логин/пароль" style="font-size: 40px;">Сменить логин/пароль</a>
				</div>
				<div>
					<div class="username">
						<a href="logout.php" class="username" title="Выйти из аккаунта" style="font-size: 40px;">Выйти</a>
					</div>
				</div>
			</div>
		</div>
	</header>
	<main style="height: 80vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Фамилия:</strong>
					<input class="input_pers_acc" type="text" name="name" value="<?php echo $name[0];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_acc_row">
					<strong>Имя:</strong>
					<input class="input_pers_acc" type="text" name="surname" value="<?php echo $name[1];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div> 
				<div class="pers_acc_row">
					<strong>Отчество:</strong>
					<input class="input_pers_acc" type="text" name="lastname" value="<?php echo $name[2];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_acc_row">
					<strong>Паспорт:</strong>
					<input class="input_pers_acc" type="text" name="passport" value="<?php echo $_POST['passport'];?>" pattern="^[0-9]{10}$" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_acc_row">
					<strong>Дата рождения:</strong>
					<input class="input_pers_acc" type="date" name="date_birth" value="<?php echo $_POST['date_birth'];?>" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_acc_row">
					<strong>Номер телефона:</strong>
					<input class="input_pers_acc" type="text" placeholder="8 _ _ _ _ _ _ _ _ _" name="phone_number" value="<?php echo $_POST['phone_number'];?>" pattern="8[0-9]{10}$" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_acc_row">
					<strong>Дата принятия:</strong>
					<input class="input_pers_acc" type="date" name="date_accept" value="<?php echo $_POST['date_accept'];?>" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_acc_row">
					<strong>Должность:</strong>
					<input class="input_pers_acc" type="text" name="post" value="<?php echo $_POST['post'];?>" autocomplete="off" pattern="^[А-Яа-яЁё]{2,20}$" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_acc_row">
					<strong>Заработная плата (₽):</strong>
					<input class="input_pers_acc" type="text" name="salary" value="<?php echo $_POST['salary'];?>" placeholder="0000.00" pattern="^[0-9]{4,15}.[0-9]{2}$" autocomplete="off" <?php if ($_SESSION['post'] != 'admin') { ?> readonly <?php } ?>>
				</div>
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<?php if ($_SESSION['post'] == 'admin') { ?>
					<div class="pers_acc_submit">
						<input class="submit_pers_acc" type="submit" name="edit_pers_acc" value="Изменить">
					</div>
				<?php } ?>
			</form>
		</div>
	</main>
</body>
</html>