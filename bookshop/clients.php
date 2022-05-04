<?php 
	require('./connection.php'); //соединение с БД
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
	<title>Клиенты</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<strong><a href="index.php" class="username" title="Главная страница" style="font-size: 50px;">BookShop</a></strong>
				</div>
				<div class="username">
					<strong><a href="clients.php" class="header_check" title="Обновить страницу">Клиенты</a></strong>
				</div>
				<div>
					<div class="username">
						<strong><a href="clients_new.php" class="username" style="font-size: 40px;" title="Добавить нового клиента">Добавить клиента</a></strong>
					</div>
				</div>
			</div> 
			<form class="header_div_row_search" method="POST">
				<input class="search" type="text" name="search_id" value="<?php echo $_POST['search_id']; ?>" placeholder="поиск по коду клиента" style="margin-right: 15px" autocomplete="off"> <!-- поиск -->
				<input class="search" type="text" name="search" value="<?php echo $_POST['search']; ?>" placeholder="поиск по ФИО/номеру" style="margin-right: 15px" autocomplete="off"> <!-- поиск -->
				<input class="header_search_submit" type="submit" name="search_submit" value="🔍">
			</form>
		</div>
	</header>
	<main class="main_book">
		<?php 
			if ($_SESSION['auth'] != 'true') {
				echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
			}
			if (isset($_POST['search_submit']) AND empty($_POST['search']) AND empty($_POST['search_id']) OR !isset($_POST['search_submit'])) { //если не нажата кнопка поиска или поиск пустой
				$query = "SELECT * FROM `Clients` ORDER BY RAND()"; //ORDER BY RAND() - рандомная сортировка
			}
			else { //если нажата кнопка поиска
				if (!empty($_POST['search_id'])) {
					$query = "SELECT * FROM `Clients` WHERE `ID_Client` LIKE '%$_POST[search_id]%' ORDER BY RAND()";
				}
				else {
					$query = "SELECT * FROM `Clients` WHERE `FIO` LIKE '%$_POST[search]%' OR `Phone_Number` LIKE '%$_POST[search]%' ORDER BY RAND()";
				}
			}
			$res=mysqli_query($link, $query) or die(mysqli_error($link));
			for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row); //row-из переменной res все данные в массив; book - создали ПУСТОЙ массив; book2 из массива row в массив book
			$res = ''; // обнуляем для дальнейшего использования
			$num = 0; //обнуляем переменную для подсчета кол-ва выведенных строк
			foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
				if ($value['FIO'] != 'УДАЛЕНО' AND $value['FIO'] != 'ДОПОЛНИТЕЛЬНО') {
					$num = $num+1;
					$res .= '<div class="book_section" style="width: 500px;">';
						$res .= '<div class="book_row">';
							$res .= '<div class="book_column" style="width: 250px;">';
								$res .= '<strong>Код клиента: </strong>';
								$res .= '<strong>ФИО: </strong>';
								$res .= '<strong>Номер телефона: </strong>';
							$res .= '</div>';
							$res .= '<div class="book_column" style="width: 250px;">';
								$res .= '<span>'.$value['ID_Client'].'</span>';
								$res .= '<span>'.$value['FIO'].'</span>';
								$res .= '<span>'.$value['Phone_Number'].'</span>';
							$res .= '</div>';
						$res .= '</div>';
						$res .= '<form method="POST" class="book_row" style="justify-content: center; margin-top: 15px;">';
							$res .='<input class="hidden" type="text" name="idclient_edit" value="'.$value['ID_Client'].'">'; //скрытое поле для перехода на страницу редактирования
							$res .= '<input type="submit" name="edit" value="Изменить" class="submit_pers_acc" onclick="radiobox'.$value['ID_Client'].'checked=true">'; 
							$res .='<input class="hidden" name="id_row" type="radio" id="radiobox'.$value['ID_Client'].'" value="'.$value['ID_Client'].'">'; //единичный чекбокс, в котором хранится айди книги при активировании 
						$res .= '</form>';
					$res .= '</div>';
				}
			}
			if ($num == 0) {
				echo '<div class="index_wrap">';
					echo '<div class="index_div_row" style="margin-top: 100px;">';
						echo '<strong style="color: red;">Нет результатов.</strong>';
					echo '</div>';	
				echo '</div>';
			}
			if (isset($_POST['edit'])) {
				$_SESSION['idclient'] = $_POST['idclient_edit'];
				echo '<meta http-equiv=Refresh content="0; clients_edit.php">'; //переход на страницу редактирования книги
			}	
			echo $res;
		?>
	</main>
</body>
</html>