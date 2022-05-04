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
	<title>Состав продажи</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<a href="buys.php" class="username" title="Продажи" style="font-size: 40px;">← Назад</a>
				</div>
				<div class="username">
					<strong><a href="buys_contents.php" class="header_check" title="Обновить страницу">Состав продажи</a></strong>
				</div>
				<div>
					<div class="username">
						<strong><a href="buys_contents_new.php" class="username" style="font-size: 40px;" title="Добавить новый состав">Добавить состав</a></strong>
					</div>
				</div>
			</div> 
			<form class="header_div_row_search" method="POST">
				<input class="search" type="text" name="search" value="<?php echo $_POST['search']; ?>" placeholder="поиск по коду книги" style="margin-right: 15px" autocomplete="off"> <!-- поиск -->
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
				$query = "SELECT * FROM `Buys_Contents` WHERE `ID_Buy`='$_SESSION[idbuy]' ORDER BY RAND()"; //ORDER BY RAND() - рандомная сортировка
			}
			else { //если нажата кнопка поиска
				$query = "SELECT * FROM `Buys_Contents` WHERE `ID_Buy`='$_SESSION[idbuy]' AND `ID_Book` LIKE '%$_POST[search]%' ORDER BY RAND()";
			}
			$res=mysqli_query($link, $query) or die(mysqli_error($link));
			for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row); //row-из переменной res все данные в массив; book - создали ПУСТОЙ массив; book2 из массива row в массив book
			$res = ''; // обнуляем для дальнейшего использования
			$num = 0; //обнуляем переменную для подсчета кол-ва выведенных строк
			$res .= '<div class="book_section" style="width: 300px;">';
				$res .= '<div class="book_row" style="display: flex; justify-content: center;">';
					$res .= '<strong>Код продажи:</strong>';
					$res .= '<span>&emsp;'.$_SESSION['idbuy'].'</span>';
				$res .= '</div>';
			$res .= '</div>';
			foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
				if ($value['FIO']!='УДАЛЕНО') {
					$num = $num+1;
					$res .= '<div class="book_section" style="width: 300px;">';
						$res .= '<div class="book_row">';
							$res .= '<div class="book_column" style="width: 250px;">';
								$res .= '<strong>Код книги: </strong>';
								$res .= '<strong>Кол-во: </strong>';
							$res .= '</div>';
							$res .= '<div class="book_column" style="width: 250px;">';
								$res .= '<span>'.$value['ID_Book'].'</span>';
								$res .= '<span>'.$value['Quantity'].'</span>';
							$res .= '</div>';
						$res .= '</div>';
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
			echo $res;
		?>
	</main>
</body>
</html>