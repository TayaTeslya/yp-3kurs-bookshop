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
	<title>Книги</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<strong><a href="index.php" class="username" title="Главная страница" style="font-size: 50px;">BookShop</a></strong>
				</div>
				<div class="username">
					<strong><a href="books.php" class="header_check" title="Обновить страницу">Книги</a></strong>
				</div>
				<div>
					<div class="username">
						<strong><a href="books_new.php" class="username" style="font-size: 40px;" title="Добавить новую книгу">Добавить книгу</a></strong>
					</div>
				</div>
			</div> 
			<form class="header_div_row_search" method="POST">
				<input class="search" type="text" name="search" value="<?php echo $_POST['search']; ?>" placeholder="поиск по коду/названию/автору" style="margin-right: 15px" autocomplete="off"> <!-- поиск -->
				<input class="header_search_submit" type="submit" name="search_submit" value="🔍">
			</form>
		</div>
	</header>
	<main class="main_book">
		<?php 
			if ($_SESSION['auth'] != 'true') {
				echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
			}
			if (isset($_POST['search_submit']) AND empty($_POST['search']) OR !isset($_POST['search_submit'])) { //если не нажата кнопка поиска или поиск пустой
				$query = "SELECT * FROM `Books` ORDER BY RAND()"; //ORDER BY RAND() - рандомная сортировка
			}
			else { //если нажата кнопка поиска
				$query = "SELECT * FROM `Books` WHERE `Name_Book` LIKE '%$_POST[search]%' OR `Author` LIKE '%$_POST[search]%' OR `ID_Book` LIKE '%$_POST[search]%' ORDER BY RAND()";
			}
			$res=mysqli_query($link, $query) or die(mysqli_error($link));
			for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row); //row-из переменной res все данные в массив; book - создали ПУСТОЙ массив; book2 из массива row в массив book
			$res = ''; // обнуляем для дальнейшего использования
			$num = 0; //обнуляем переменную для подсчета кол-ва выведенных книг
			// $res .= '<form class="header_div_row_search" method="POST" style="margin-top: 30px;">';
			// 	$res .= '<input class="submit_pers_acc" type="submit" name="search_submit" value="Добавить новую книгу">';
			// $res .= '</form>';
			foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
				if ($value['Name_Book']!='УДАЛЕНО') {
					$num = $num+1;
					$query = "SELECT `Name_Publisher` FROM `Publishers`, `Books` WHERE `Publishers`.`ID_Publisher`=`Books`.`ID_Publisher` AND `ID_Book`='$value[ID_Book]'";
					$result = mysqli_query($link, $query) or die(mysqli_error($link));
					$row_p = mysqli_fetch_assoc($result);
					$query = "SELECT `Discount` FROM `Discounts`, `Books` WHERE `Discounts`.`ID_Book`=`Books`.`ID_Book` AND `Books`.`ID_Book`='$value[ID_Book]'";	
					$result = mysqli_query($link, $query) or die(mysqli_error($link));
					$row_d = mysqli_fetch_assoc($result);
					$res .= '<div class="book_section">';
						$res .= '<div class="book_name_div">';
						if ($value['Quantity'] != 0) { //надпись "нет в наличии"
							$res .= '<strong class="book_name_text">'.$value['Name_Book'].'</strong>';
						}
						else {
							$res .= '<strong class="book_name_text">'.$value['Name_Book'].'</strong> <strong style="color: red;"> (нет в наличии)</strong>';
						}
						$res .= '</div>';
						$res .= '<div class="book_row">';
							$res .= '<div class="book_column" style="width: 150px;">';
								$res .= '<strong>Код книги: </strong>';
								$res .= '<strong>Автор: </strong>';
								$res .= '<strong>Год: </strong>';
								$res .= '<strong>Кол-во страниц: </strong>';
								$res .= '<strong>Цена: </strong>';
								$res .= '<strong>Издательство: </strong>';
								$res .= '<strong>Кол-во на складе: </strong>';
								$res .= '<strong>Скидка: </strong>';
								if ($row_d['Discount'] != null) {
									$res .= '<strong>Цена со скидкой: </strong>';
								}
							$res .= '</div>';
							$res .= '<div class="book_column" style="width: 250px;">';
								$res .= '<span>'.$value['ID_Book'].'</span>';
								$res .= '<span>'.$value['Author'].'</span>';
								$res .= '<span>'.$value['Year'].'</span>';
								$res .= '<span>'.$value['Pages'].'</span>';
								$res .= '<span>'.$value['Price'].' ₽</span>';
								$res .= '<span>'.$row_p['Name_Publisher'].'</span>';
								$res .= '<span>'.$value['Quantity'].'</span>';
								if ($row_d['Discount'] != null) {
									$res .= '<span>'.$row_d['Discount'].' %</span>';
									$pwd = $value['Price'] - ($value['Price'] * $row_d['Discount'] / 100); //рассчет цены со скидкой
									$res .= '<span>'.$pwd.' ₽</span>';
								}
								else {
									$res .= '<span>-</span>';
								}
							$res .= '</div>';
							$res .= '<div class="book_column" style="width: 600px;">';
								$res .= '<span>'.$value['Description'].'</span>';
							$res .= '</div>';
						$res .= '</div>';
						$res .= '<form method="POST" class="book_row" style="justify-content: center; margin-top: 15px;">';
							$res .='<input class="hidden" type="text" name="idbook_edit" value="'.$value['ID_Book'].'">'; //скрытое поле для перехода на страницу редактирования
							$res .= '<input type="submit" name="edit" value="Изменить" class="submit_pers_acc" onclick="radiobox'.$value['ID_Book'].'checked=true">'; 
							$res .='<input class="hidden" name="id_row" type="radio" id="radiobox'.$value['ID_Book'].'" value="'.$value['ID_Book'].'">'; //единичный чекбокс, в котором хранится айди книги при активировании 
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
				$_SESSION['idbook'] = $_POST['idbook_edit'];
				echo '<meta http-equiv=Refresh content="0; books_edit.php">'; //переход на страницу редактирования книги
			}	
			echo $res;
		?>
	</main>
</body>
</html>