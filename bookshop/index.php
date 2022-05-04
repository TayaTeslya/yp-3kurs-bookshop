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
	<title>Главная страница</title>
</head>
<body>
	<header class="header_header">
		<div class="header_div_column">
			<div class="header_div_row">
				<div class="username">
					<strong><a href="index.php" class="username" title="Главная страница" style="font-size: 50px;">BookShop</a></strong>
				</div>
				<div>
					<?php 
						if ($_SESSION['auth'] == 'true') {
							?> 
							<div class="username">
								<a href="pers_account.php" class="username" title="Личный кабинет"><?php echo $_SESSION['username']?></a>
							</div>
							<?php
						}
						else {
							?>
								<div class="username">
									<a href="auth.php" class="username" title="Авторизоваться" style="text-decoration: underline; font-size: 25px;">Авторизоваться</a>
								</div>
							<?php
						}
					?>
				</div>
			</div>
		</div>
	</header>
	<main style="width: 100vw">
		<?php 
			if ($_SESSION['auth'] == 'true') {
				if ($_SESSION['post'] == 'admin' OR $_SESSION['post'] == 'worker') {
					echo '<div class="index_wrap">';
						echo '<div class="index_div_row" style="margin-top: 30px;">';
							//Books
							echo '<div class="index_div">';
								echo '<strong><a href="books.php" title="Перейти на страницу с книгами" class="href_index">Книги</a></strong>';
							echo '</div>';
							//Discounts
							echo '<div class="index_div">';
								echo '<strong><a href="discounts.php" title="Перейти на страницу со скидками" class="href_index">Скидки</a></strong>';
							echo '</div>';
						echo '</div>';	
						echo '<div class="index_div_row">';
							//Clients
							echo '<div class="index_div">';
								echo '<strong><a href="clients.php" title="Перейти на страницу с скидочными картами" class="href_index">Клиенты</a></strong>';
							echo '</div>';
							//Buys & Buys_Content
							echo '<div class="index_div">';
								echo '<strong><a href="buys.php" title="Перейти на страницу с продажами" class="href_index">Продажи</a></strong>';
							echo '</div>';
						echo '</div>';
						echo '<div class="index_div_row">';
						//Publishers
						echo '<div class="index_div">';
							echo '<strong><a href="	publishers.php" title="Перейти на страницу с издательствами" class="href_index">Издательства</a></strong>';
						echo '</div>';
						if ($_SESSION['post'] != 'worker') {	
							//Workers
							echo '<div class="index_div">';
								echo '<strong><a href="workers.php" title="Перейти на страницу с сотрудниками" class="href_index">Сотрудники</a></strong>';
							echo '</div>';
						}
						echo '</div>';
					echo '</div>';
				}
			}
			else {
				echo '<div class="index_wrap">';
					echo '<div class="index_div_row" style="margin-top: 150px;">';
						echo '<strong style="color: red;">Для просмотра необходимо авторизоваться. ➚</strong>';
					echo '</div>';	
				echo '</div>';
			}
		?>
	</main>
</body>
</html>