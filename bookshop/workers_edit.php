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
	<title>Редактирование сотрудника</title>
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
					<a href="workers.php" class="username" title="Скидки" style="font-size: 40px;">&emsp;&emsp;← Назад&emsp;&emsp;</a>
				</div>
				<div>
					<strong class="header_check">Редактирование сотрудника</strong>
				</div>
				<div class="username">
					<a href="workers_edit_lp.php" class="username" title="Сменить логин/пароль" style="font-size: 40px;">Сменить логин/пароль</a>
				</div>
			</div>
		</div>
	</header>
	<?php
		if ($_SESSION['post'] != 'admin') {
			echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
		}
		if (isset($_POST['edit_worker'])) {
			if (!empty($_POST['name']) AND !empty($_POST['surname']) AND !empty($_POST['lastname'])) { //проверка на введение фио
				$query = "SELECT * FROM `Workers` WHERE `Passport`='$_POST[passport]' AND `ID_Worker`<>'$_SESSION[idworker]'"; //запрос на поиск такого же паспорта у сотрудников
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
				if ($_POST['passport'] != $row['Passport']) {
					if (!empty($_POST['date_birth']) AND !empty($_POST['date_accept'])) {
						if (!empty($_POST['phone_number'])) {
							$query = "SELECT * FROM `Workers` WHERE `Phone_Number`='$_POST[phone_number]' AND `ID_Worker`<>'$_SESSION[idworker]'"; //запрос на поиск такого же номера телефона у сотрудников
							$res = mysqli_query($link, $query) or die(mysqli_error($link));
							$row = mysqli_fetch_assoc($res);
							if ($_POST['phone_number'] != $row['Phone_Number']) {
								if (!empty($_POST['post'])) {
									if (!empty($_POST['salary'])) {
										$FIO = $_POST['name'].' '.$_POST['surname'].' '.$_POST['lastname'];
										$query = "UPDATE `Workers` SET `FIO`='$FIO', `Passport`='$_POST[passport]', `Date_Birth`='$_POST[date_birth]', `Phone_Number`='$_POST[phone_number]',
									`Date_Accept`='$_POST[date_accept]', `Post`='$_POST[post]', `Salary`='$_POST[salary]' WHERE `ID_Worker`='$_SESSION[idworker]'";
										$res = mysqli_query($link, $query) or die(mysqli_error($link));
										$message = '<span style="color: green">Сотрудник успешно изменен.</span>';
										echo '<meta http-equiv=Refresh content="1.5; workers.php">';
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
						$message = '<span style="color: red;">Заполните дату рождения сотрудника и дату принятия.</span>';
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
			$query = "SELECT * FROM `Workers` WHERE `ID_Worker`='$_SESSION[idworker]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$row = mysqli_fetch_assoc($res);
			$name = explode(" ", $row['FIO']); 
			$_POST['name'] = $name[1];
			$_POST['surname'] = $name[0];
			$_POST['lastname'] = $name[2];
			$_POST['passport'] = $row['Passport'];
			$_POST['date_birth'] = $row['Date_Birth'];
			$_POST['phone_number'] = $row['Phone_Number'];
			$_POST['date_accept'] = $row['Date_Accept'];
			$_POST['salary'] = $row['Salary'];
			$_POST['post'] = $row['Post'];
		}
		if (isset($_POST['del_worker'])) {
			$query = "DELETE FROM `Users` WHERE `ID_Worker`='$_SESSION[idworker]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$query = "UPDATE `Buys` SET `ID_Worker`='1' WHERE `ID_Worker`='$_SESSION[idworker]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$query = "DELETE FROM `Workers` WHERE `ID_Worker`='$_SESSION[idworker]'"; 
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$message = '<span style="color: green;">Сотрудник успешно удален.</span>';
			echo '<meta http-equiv=Refresh content="1.5; workers.php">';
		}
	?>
	<main style="height: 85vh; display: flex; align-items: center; justify-content: center;">
		<div class="div_pers_acc"  style="height: 580px;">
			<form class="pers_acc_column" method="POST">
				<div class="pers_acc_row">
					<strong>Фамилия:</strong>
					<input class="input_pers_acc" type="text" name="name" value="<?php echo $_POST['name'];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off">
				</div>
				<div class="pers_acc_row">
					<strong>Имя:</strong>
					<input class="input_pers_acc" type="text" name="surname" value="<?php echo $_POST['surname'];?>" pattern="^[А-Яа-яЁё]{2,80}$" autocomplete="off">
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
					<strong>Должность:</strong>
					<input class="input_pers_acc" type="text" name="post" value="<?php echo $_POST['post'];?>" autocomplete="off" pattern="^[А-Яа-яЁё]{2,20}$">
				</div>
				<div class="pers_acc_row">
					<strong>Заработная плата (₽):</strong>
					<input class="input_pers_acc" type="text" name="salary" value="<?php echo $_POST['salary'];?>" placeholder="0000.00" pattern="^[0-9]{4,15}.[0-9]{2}$" autocomplete="off">
				</div>
				<div class="pers_message">
					<?php
						echo '<span>&#160;'.$message.'</span>';
					?>	
				</div>
				<div class="pers_acc_submit" style="display: flex; justify-content: space-around;">
					<input class="submit_red_hover" type="submit" name="del_worker" value="Удалить">
					<input class="submit_pers_acc" type="submit" name="edit_worker" value="Изменить">
				</div>
			</form>
		</div>
	</main>
</body>
</html>