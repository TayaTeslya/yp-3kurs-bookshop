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
	<title>Издательства</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<strong><a href="index.php" class="username" title="Главная страница" style="font-size: 50px;">BookShop</a></strong>
				</div>
				<div class="username">
					<strong><a href="publishers.php" class="header_check" title="Обновить страницу">Издательства</a></strong>
				</div>
				<div>
					<div class="username">
						<strong><a href="publishers_new.php" class="username" style="font-size: 40px;" title="Добавить новое издательство">Добавить издательство</a></strong>
					</div>
				</div>
			</div> 
			<form class="header_div_row_search" method="POST">
				<input class="search" type="text" name="search" value="<?php echo $_POST['search']; ?>" placeholder="поиск по коду/названию" style="margin-right: 15px" autocomplete="off"> <!-- поиск -->
				<input class="header_search_submit" type="submit" name="search_submit" value="🔍">
			</form>
		</div>
	</header>
	<main class="main_book" style="width: 96vw;">
		<?php 
			if ($_SESSION['auth'] != 'true') {
				echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
			}
			if (isset($_POST['search_submit']) AND empty($_POST['search']) OR !isset($_POST['search_submit'])) { //если не нажата кнопка поиска или поиск пустой
				$query = "SELECT * FROM `Publishers` ORDER BY RAND()"; //ORDER BY RAND() - рандомная сортировка
			}
			else { //если нажата кнопка поиска
				$query = "SELECT * FROM `Publishers` WHERE `ID_Publisher` LIKE '%$_POST[search]%' OR `Name_Publisher` LIKE '%$_POST[search]%' ORDER BY RAND()";
			}
			$res=mysqli_query($link, $query) or die(mysqli_error($link));
			for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row); //row-из переменной res все данные в массив; book - создали ПУСТОЙ массив; book2 из массива row в массив book
			$res = ''; // обнуляем для дальнейшего использования
			$num = 0; //обнуляем переменную для подсчета кол-ва выведенных строк
			foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
				if ($value['Name_Publisher']!='УДАЛЕНО') {
					$num = $num+1;
					$res .= '<div class="book_section" style="width: 300px;">';
						$res .= '<div class="book_row">';
							$res .= '<div class="book_column" style="width: 150px;">';
								$res .= '<strong>Код издательства: </strong>';
								$res .= '<strong>Название: </strong>';
							$res .= '</div>';
							$res .= '<div class="book_column" style="width: 150px;">';
								$res .= '<span>'.$value['ID_Publisher'].'</span>';
								$res .= '<span>'.$value['Name_Publisher'].'</span>';
							$res .= '</div>';
						$res .= '</div>';
						$res .= '<form method="POST" class="book_row" style="justify-content: center; margin-top: 15px;">';
							$res .='<input class="hidden" type="text" name="idpublisher_edit" value="'.$value['ID_Publisher'].'">'; //скрытое поле для перехода на страницу редактирования
							$res .= '<input type="submit" name="edit" value="Изменить" class="submit_pers_acc" onclick="radiobox'.$value['ID_Publisher'].'checked=true">'; 
							$res .='<input class="hidden" name="id_row" type="radio" id="radiobox'.$value['ID_Publisher'].'" value="'.$value['ID_Publisher'].'">'; //единичный чекбокс, в котором хранится айди книги при активировании 
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
				$_SESSION['idpublisher'] = $_POST['idpublisher_edit'];
				echo '<meta http-equiv=Refresh content="0; publishers_edit.php">'; //переход на страницу редактирования книги
			}	
			echo $res;
		?>
	</main>
</body>
</html>