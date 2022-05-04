<?php 
	session_start();
	$servername = "localhost"; //локальный сервер
	$database = "bookshop"; //название БД
	$username = "root";
	$passsword = "";
	$link = mysqli_connect($servername, $username, $password, $database); //создаем соединение
?>