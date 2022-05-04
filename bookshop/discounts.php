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
	<title>Скидки</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<strong><a href="index.php" class="username" title="Главная страница" style="font-size: 50px;">BookShop</a></strong>
				</div>
				<div class="username">
					<strong><a href="discounts.php" class="header_check" title="Обновить страницу">Скидки</a></strong>
				</div>
				<div>
					<div class="username">
						<strong><a href="discounts_new.php" class="username" style="font-size: 40px;" title="Добавить новую скидку">Добавить скидку</a></strong>
					</div>
				</div>	
			</div> 
			<form class="header_div_row_search" method="POST">
				<input class="header_search_submit" style="margin-right: 15px" title="Сортировка по возрастанию скидки" type="submit" name="search_submit_up" value="▲">
				<input class="header_search_submit" style="margin-right: 15px" title="Сортировка по убыванию скидки" type="submit" name="search_submit_down" value="▼">
				<input class="search" type="text" name="search" value="<?php echo $_POST['search']; ?>" placeholder="поиск по названию/автору" style="margin-right: 15px" autocomplete="off"> <!-- поиск -->
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
				if (isset($_POST['search_submit_up'])) { //сортировка по возрастанию
					$query = "SELECT * FROM `Books`, `Discounts` WHERE `Discounts`.`ID_Book`=`Books`.`ID_Book` ORDER BY `Discount`";
				}
				else {
					if (isset($_POST['search_submit_down'])) { //сортировка по убыванию
						$query = "SELECT * FROM `Books`, `Discounts` WHERE `Discounts`.`ID_Book`=`Books`.`ID_Book` ORDER BY `Discount` DESC";
					}
					else {
						$query = "SELECT * FROM `Books`, `Discounts` WHERE `Discounts`.`ID_Book`=`Books`.`ID_Book` ORDER BY RAND()"; //ORDER BY RAND() - рандомная сортировка
					}
				}
			}
			else { //если нажата кнопка поиска
				$query = "SELECT * FROM `Books`, `Discounts` WHERE `Discounts`.`ID_Book`=`Books`.`ID_Book` AND (`Name_Book` LIKE '%$_POST[search]%' OR `Author` LIKE '%$_POST[search]%' OR `Discounts`.`ID_Book` LIKE '%$_POST[search]%') ORDER BY RAND()";
			}
			$res=mysqli_query($link, $query) or die(mysqli_error($link));
			for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row); //row-из переменной res все данные в массив; book - создали ПУСТОЙ массив; book2 из массива row в массив book
			$res = ''; // обнуляем для дальнейшего использования
			$num = 0; //обнуляем переменную для подсчета кол-ва выведенных строк
			foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
				$num = $num+1;
				$res .= '<div class="book_section" style="width: 500px;">';
					$res .= '<div class="book_name_div">';
					if ($value['Quantity'] != 0) { //надпись "нет в наличии"
						$res .= '<strong class="book_name_text">'.$value['Name_Book'].'</strong>';
					}
					else {
						$res .= '<strong class="book_name_text">'.$value['Name_Book'].'</strong> <strong style="color: red;"> (нет в наличии)</strong>';
					}
					$res .= '</div>';
					$res .= '<div class="book_row">';
						$res .= '<div class="book_column" style="width: 250px;">';
							$res .= '<strong>Код книги: </strong>';
							$res .= '<strong>Автор: </strong>';
							$res .= '<strong>Цена: </strong>';
							$res .= '<strong>Скидка: </strong>';
							$res .= '<strong>Цена со скидкой: </strong>';
						$res .= '</div>';
						$res .= '<div class="book_column" style="width: 250px;">';
							$res .= '<span>'.$value['ID_Book'].'</span>';
							$res .= '<span>'.$value['Author'].'</span>';
							$res .= '<span>'.$value['Price'].' ₽</span>';
							$res .= '<span>'.$value['Discount'].' %</span>';
							$pwd = $value['Price'] - ($value['Price'] * $row_d['Discount'] / 100); //рассчет цены со скидкой
							$res .= '<span>'.$pwd.' ₽</span>';
						$res .= '</div>';
					$res .= '</div>';
					$res .= '<form method="POST" class="book_row" style="justify-content: center; margin-top: 15px;">';
						$res .='<input class="hidden" type="text" name="iddiscount_edit" value="'.$value['ID_Discount'].'">'; //скрытое поле для перехода на страницу редактирования
						$res .= '<input type="submit" name="edit" value="Изменить" class="submit_pers_acc" onclick="radiobox'.$value['ID_Discount'].'checked=true">'; 
						$res .='<input class="hidden" name="id_row" type="radio" id="radiobox'.$value['ID_Discount'].'" value="'.$value['ID_Discount'].'">'; //единичный чекбокс, в котором хранится айди книги при активировании 
					$res .= '</form>';
				$res .= '</div>';
			}
			if ($num == 0) {
				echo '<div class="index_wrap">';
					echo '<div class="index_div_row" style="margin-top: 100px;">';
						echo '<strong style="color: red;">Нет результатов.</strong>';
					echo '</div>';	
				echo '</div>';
			}
			if (isset($_POST['edit'])) {
				$_SESSION['iddiscount'] = $_POST['iddiscount_edit'];
				echo '<meta http-equiv=Refresh content="0; discounts_edit.php">'; //переход на страницу редактирования книги
			}	
			echo $res;
		?>
	</main>
</body>
</html>