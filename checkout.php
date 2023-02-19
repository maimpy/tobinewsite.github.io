<?php
// Подключение к базе данных
$servername = "localhost";
$username = "username";
$password = "password";
$dbname = "myDB";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Получение списка товаров из корзины
$sql = "SELECT product_id, quantity FROM cart";
$result = $conn->query($sql);
$cart_items = array();
$total_price = 0;
if ($result->num_rows > 0) {
  // Для каждого товара из корзины получаем его название и цену
  while ($row = $result->fetch_assoc()) {
    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $sql = "SELECT name, price FROM products WHERE id = $product_id";
    $product_result = $conn->query($sql);
    $product_row = $product_result->fetch_assoc();
    $name = $product_row['name'];
    $price = $product_row['price'];
    $cart_items[] = array(
      'product_id' => $product_id,
      'name' => $name,
      'price' => $price,
      'quantity' => $quantity
    );
    $total_price += $price * $quantity;
  }
}

// Создание заказа в базе данных
$sql = "INSERT INTO orders (total_price) VALUES ($total_price)";
$conn->query($sql);
$order_id = $conn->insert_id;

// Добавление товаров из корзины в заказ
foreach ($cart_items as $cart_item) {
  $product_id = $cart_item['product_id'];
  $quantity = $cart_item['quantity'];
  $sql = "INSERT INTO order_items (order_id, product_id, quantity) VALUES ($order_id, $product_id, $quantity)";
  $conn->query($sql);
}

// Очистка корзины
$sql = "DELETE FROM cart";
$conn->query($sql);

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Интернет-магазин</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Интернет-магазин</h1>
    <a href="index.php">Вернуться к каталогу</a>
  </header>
  <main>
    <h2>Заказ успешно оформлен</h2>
    <p>Номер вашего заказа: <?php echo $order_id; ?></p>
    <p>Итоговая сумма заказа: <?php echo $total_price; ?> руб.</p>
  </main>
</body>
</html>

