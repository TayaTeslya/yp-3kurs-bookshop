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
	<style type="text/css">
		* {
			font-family: CONSOLAS;
		}
	</style>
	<title>Чек</title>
</head>
<body>
	<main class="main_book" style="height: 100vh; width: 100vw; align-items: center;">
		<?php 
			if ($_SESSION['auth'] != 'true' OR $_SESSION['idbuy'] == null) {
				echo '<meta http-equiv=Refresh content="0; index.php">'; //в случае попадения на эту страницу неавторизованного пользователя, редирект на главную страницу
			}
		?>
		<div style="display: flex; flex-direction: column; width: 400px;">
			<strong style="font-size: 30px; text-align: center;">ООО "BookStore"</strong>
			<span style="text-align: center;">Чек № <?php echo $_SESSION['idbuy'];?></span>
			<?php 
				$query = "SELECT * FROM `Workers`, `Buys` WHERE `ID_Buy`='$_SESSION[idbuy]' AND `Workers`.`ID_Worker`=`Buys`.`ID_Worker`";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
			?>
			<span style="text-align: center;">Продавец: <?php echo $row['FIO'];?></span>
			<span style="text-align: center;">===================================</span>
			<?php 
				$query = "SELECT * FROM `Buys_Contents` WHERE `ID_Buy`='$_SESSION[idbuy]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				for ($values=[]; $row=mysqli_fetch_assoc($res); $values[]=$row); //row-из переменной res все данные в массив; book - создали ПУСТОЙ массив; book2 из массива row в массив book
				$res = ''; // обнуляем для дальнейшего использования
				$num = 0; //обнуляем переменную для подсчета кол-ва выведенных строк
				$cost = 0;
				foreach ($values as $value) { //foreach-цикл, values-массив с данными из БД, value - строка из БД
					$num = $num + 1;
					$query = "SELECT * FROM `Books` WHERE `ID_Book`='$value[ID_Book]'";
					$result = mysqli_query($link, $query) or die(mysqli_error($link));
					$row = mysqli_fetch_assoc($result);
					$res .= '<span><strong>'.$num.'.</strong> '.$row['Name_Book'].' - '.$row['Author'].'</span>';
					$query = "SELECT * FROM `Publishers` WHERE `ID_Publisher`='$row[ID_Publisher]'";
					$result = mysqli_query($link, $query) or die(mysqli_error($link));
					$rows = mysqli_fetch_assoc($result);
					$res .= '<span>Издательство: '. $rows['Name_Publisher'].'</span>';
					$row['Price'] = number_format($row['Price'], 2, '.', '');
					$res .= '<span>'.$value['Quantity'].' X '.$row['Price'].'</span>';
					$cost_price = number_format($value['Quantity']*$row['Price'], 2, '.', '');
					$cost = $cost + $cost_price;
					$res .= '<span>Стоимость: '.$cost_price.'</span>';
				}
				echo $res;
			?>
			<span style="text-align: center;">===================================</span>
			<?php 
				$query = "SELECT * FROM `Buys` WHERE `ID_Buy`='$_SESSION[idbuy]'";
				$res = mysqli_query($link, $query) or die(mysqli_error($link));
				$row = mysqli_fetch_assoc($res);
			?>
			<span>Всего: <?php echo number_format($cost, 2, '.', '');?></span>
			<span>Скидка: <?php echo number_format($cost-$row['Cost'], 2, '.', '');?></span>
			<span><?php echo $row['Date_Buy']?></span>
			<span style="text-align: center;">===================================</span>
			<strong style="font-size: 40px;">ИТОГ: <?php echo number_format($row['Cost'], 2, '.', '');?></strong>
			<div style="display: flex; justify-content: center;">
				<input type="button" style="margin-top: 50px; width: 150px; font-family: 'Bad Script';" class="submit_pers_acc" onclick="this.style='display: none'; print();" value="Распечатать">
			</div>
		</div>
	</main>
</body>
</html>