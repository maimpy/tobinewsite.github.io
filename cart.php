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

// Получение товаров из базы данных, которые уже добавлены в корзину
$sql = "SELECT products.id, products.name, products.price, products.image, cart.quantity FROM products JOIN cart ON products.id = cart.product_id";
$result = $conn->query($sql);
$cart_items = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    array_push($cart_items, $row);
  }
}

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Корзина</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Корзина</h>
    <a href="index.php">Вернуться к каталогу</a>
  </header>
  <main>
    <h2>Корзина</h2>
    <?php if (count($cart_items) == 0) { ?>
      <p>Ваша корзина пуста.</p>
    <?php } else { ?>
      <table>
        <tr>
          <th>Название товара</th>
          <th>Цена за штуку</th>
          <th>Количество</th>
          <th>Сумма</th>
        </tr>
        <?php foreach ($cart_items as $cart_item) { ?>
          <tr>
            <td><?php echo $cart_item['name']; ?></td>
            <td><?php echo $cart_item['price']; ?> руб.</td>
            <td><?php echo $cart_item['quantity']; ?></td>
            <td><?php echo $cart_item['price'] * $cart_item['quantity']; ?> руб.</td>
          </tr>
        <?php } ?>
      </table>
      <p>Итого: <?php echo array_reduce($cart_items, function($total, $item) { return $total + $item['price'] * $item['quantity']; }, 0); ?> руб.</p>
      <form action="checkout.php" method="POST">
        <button type="submit">Оформить заказ</button>
      </form>
    <?php } ?>
  </main>
</body>
</html>

