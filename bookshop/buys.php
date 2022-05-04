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
	<title>Продажи</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<strong><a href="index.php" class="username" title="Главная страница" style="font-size: 50px;">BookShop</a></strong>
				</div>
				<div class="username">
					<strong><a href="buys.php" class="header_check" title="Обновить страницу">Продажи</a></strong>
				</div>
				<div>
					<div class="username">
						<strong><a href="buys_new.php" class="username" style="font-size: 40px;" title="Добавить новую продажу">Добавить продажу</a></strong>
					</div>
				</div>
			</div> 
			<form class="header_div_row_search" method="POST">
				<input class="search" type="date" name="search_date" value="<?php echo $_POST['search_date']; ?>" placeholder="поиск по дате" style="margin-right: 15px" autocomplete="off"> <!-- поиск по дате -->
				<input class="search" type="text" name="search" value="<?php echo $_POST['search']; ?>" placeholder="поиск по номеру заказа" style="margin-right: 15px" autocomplete="off"> <!-- поиск -->
				<input class="header_search_submit" type="submit" name="search_submit" value="🔍">
			</form>
		</div>
	</header>
	<main class="main_book">
		<?php 
			$_SESSION['idnewbuy'] = null;
			if ($_SESSION['auth'] != 'true') {
				echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
			}
			if (isset($_POST['search_submit']) AND empty($_POST['search']) AND empty($_POST['search_date']) OR !isset($_POST['search_submit'])) { //если не нажата кнопка поиска или поиск пустой
				$query = "SELECT * FROM `Buys` ORDER BY RAND()"; //ORDER BY RAND() - рандомная сортировка
			}
			else { //если нажата кнопка поиска
				if (!empty($_POST['search'])) {
					$query = "SELECT * FROM `Buys` WHERE `ID_Buy` LIKE '%$_POST[search]%' ORDER BY RAND()";
				}
				else {
					$query = "SELECT * FROM `Buys` WHERE `Date_Buy` LIKE '%$_POST[search_date]%' ORDER BY RAND()";
				}
			}
			$res=mysqli_query($link, $query) or die(mysqli_error($link));
			for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row); //row-из переменной res все данные в массив; book - создали ПУСТОЙ массив; book2 из массива row в массив book
			$res = ''; // обнуляем для дальнейшего использования
			$num = 0; //обнуляем переменную для подсчета кол-ва выведенных строк
			foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
				if ($value['FIO']!='УДАЛЕНО') {
					$num = $num + 1;
					$res .= '<div class="book_section" style="width: 300px;">';
						$res .= '<div class="book_row">';
							$res .= '<div class="book_column" style="width: 250px;">';
								$res .= '<strong>Код продажи: </strong>';
								$res .= '<strong>Код клиента: </strong>';
								$res .= '<strong>Код продавца: </strong>';
								$res .= '<strong>Дата продажи: </strong>';
								$res .= '<strong>Стоимость: </strong>';
							$res .= '</div>';
							$res .= '<div class="book_column" style="width: 250px;">';
								$res .= '<span>'.$value['ID_Buy'].'</span>';
								$res .= '<span>'.$value['ID_Client'].'</span>';
								$res .= '<span>'.$value['ID_Worker'].'</span>';
								$res .= '<span>'.$value['Date_Buy'].'</span>';
								$res .= '<span>'.$value['Cost'].' ₽</span>';
							$res .= '</div>';
						$res .= '</div>';
						$res .= '<form method="POST" class="book_row" style="justify-content: center; margin-top: 15px;">';
							$res .='<input class="hidden" type="text" name="idbuy_edit" value="'.$value['ID_Buy'].'">'; //скрытое поле для перехода на страницу редактирования
							$res .= '<input type="submit" name="cheque" value="Чек" class="submit_pers_acc" onclick="radiobox'.$value['ID_Buy'].'checked=true" style="margin-right: 15px;">';
							$res .= '<input type="submit" name="content_buy" value="Состав продажи" class="submit_pers_acc" onclick="radiobox'.$value['ID_Buy'].'checked=true" style="margin-right: 15px;">'; //состав продажи
							if ($_SESSION['post'] == 'admin') {
								$res .= '<input type="submit" name="edit" value="Изменить" class="submit_pers_acc" onclick="radiobox'.$value['ID_Buy'].'checked=true">';  //изменение продажи
								$res .='<input class="hidden" name="id_row" type="radio" id="radiobox'.$value['ID_Buy'].'" value="'.$value['ID_Buy'].'">'; //единичный чекбокс, в котором хранится айди книги при активировании 
							}
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
				$_SESSION['idbuy'] = $_POST['idbuy_edit'];
				echo '<meta http-equiv=Refresh content="0; buys_edit.php">'; //переход на страницу редактирования книги
			}	
			if (isset($_POST['content_buy'])) {
				$_SESSION['idbuy'] = $_POST['idbuy_edit'];
				echo '<meta http-equiv=Refresh content="0; buys_contents.php">'; //переход на страницу редактирования книги
			}
			if (isset($_POST['cheque'])) {
				$_SESSION['idbuy'] = $_POST['idbuy_edit'];
				echo '<meta http-equiv=Refresh content="0; cheque.php">'; //переход на страницу редактирования книги
			}
			echo $res;
		?>
	</main>
	<?php 
		$query = "SELECT * FROM `Buys`, `Clients` WHERE `Buys`.`ID_Client`=`Clients`.`ID_Client` AND `FIO`='ДОПОЛНИТЕЛЬНО'";
		$res = mysqli_query($link, $query) or die(mysqli_error($link));
		$row = mysqli_fetch_assoc($res);
		if ($row['ID_Buy'] != null) {
			$query = "DELETE FROM `Buys_Contents` WHERE `ID_Buy`='$row[ID_Buy]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
			$query = "DELETE FROM `Buys` WHERE `ID_Buy`='$row[ID_Buy]'";
			$res = mysqli_query($link, $query) or die(mysqli_error($link));
		}
	?>
</body>
</html>
<?php 
	$query = "SELECT * FROM `Buys` WHERE `Cost`='0'";
	$res = mysqli_query($link, $query) or die(mysqli_error($link));
	$row = mysqli_fetch_assoc($res);
	if ($row['ID_Buy'] != null) {
		$query = "DELETE FROM `Buys_Contents` WHERE `ID_Buy`='$row[ID_Buy]'";
		$res = mysqli_query($link, $query) or die(mysqli_error($link));
		$query = "DELETE FROM `Buys` WHERE `ID_Buy`='$row[ID_Buy]'";
		$res = mysqli_query($link, $query) or die(mysqli_error($link));
	}
?>