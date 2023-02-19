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

// Получение товаров из базы данных
$sql = "SELECT id, name, price, image FROM products";
$result = $conn->query($sql);
$products = array();
if ($result->num_rows > 0) {
  while($row = $result->fetch_assoc()) {
    array_push($products, $row);
  }
}

// Закрытие соединения с базой данных
$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
  <title>Интернет-магазин</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <header>
    <h1>Интернет-магазин</h1>
    <a href="cart.php">Корзина</a>
  </header>
  <main>
    <h2>Каталог товаров</h2>
    <?php foreach ($products as $product) { ?>
      <div class="product">
        <img src="<?php echo $product['image']; ?>" alt="<?php echo $product['name']; ?>">
        <h3><?php echo $product['name']; ?></h3>
        <p><?php echo $product['price']; ?> руб.</p>
        <form action="add-to-cart.php" method="POST">
          <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
          <button type="submit">Добавить в корзину</button>
        </form>
      </div>
    <?php } ?>
  </main>
</body>
</html>
