<?php
// Получение идентификатора товара, который нужно добавить в корзину
$product_id = $_POST['product_id'];

// Подключение к базе данных
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Проверка, есть ли уже этот товар в корзине
$sql = "SELECT id, quantity FROM cart WHERE product_id = $product_id";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  // Товар уже есть в корзине, увеличиваем количество
  $row = $result->fetch_assoc();
  $cart_item_id = $row['id'];
  $new_quantity = $row['quantity'] + 1;
  $sql = "UPDATE cart SET quantity = $new_quantity WHERE id = $cart_item_id";
} else {
  // Товара еще нет в корзине, добавляем его
  $sql = "INSERT INTO cart (product_id, quantity) VALUES ($product_id, 1)";
}
$conn->query($sql);

// Закрытие соединения с базой данных
$conn->close();

// Перенаправление на страницу каталога товаров
header('Location: index.php');
exit;
