<?php
@include 'config.php';
session_start();
$user_id = $_SESSION['user_id'];
if(!isset($user_id)){
   header('location:login.php');};
if (isset($_POST['add_to_cart'])) {
  $d_id = $_POST['d_id'];
  $d_id = filter_var($d_id, FILTER_SANITIZE_STRING);
  $d_title = $_POST['d_title'];
  $d_title = filter_var($d_title, FILTER_SANITIZE_STRING);
  $d_price = $_POST['d_price'];
  $d_price = filter_var($d_price, FILTER_SANITIZE_STRING);
  $d_image = $_POST['d_image'];
  $d_image = filter_var($d_image, FILTER_SANITIZE_STRING);
  $d_qty = $_POST['d_qty'];
  $d_qty = filter_var($d_qty, FILTER_SANITIZE_STRING);
  $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` join `Details_cart` on cart.cart_id = Details_cart.cart_id WHERE title = :title AND user_id = :user_id");
  $check_cart_numbers->bindParam(':title', $d_title);
  $check_cart_numbers->bindParam(':user_id', $user_id);
  $check_cart_numbers->execute();};
if ($check_cart_numbers->fetchColumn() > 0) {
  $message[] = 'already added to cart!';
}else{
  $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, d_id, title, price, quantity, image) VALUES(:user_id,:d_id,:title,:price,:quantity,:image)");
  $insert_cart->bindValue(':user_id', $user_id);
  $insert_cart->bindValue(':d_id', $d_id);
  $insert_cart->bindValue(':title', $d_title);
  $insert_cart->bindValue(':price', $d_price);
  $insert_cart->bindValue(':quantity', $d_qty);
  $insert_cart->bindValue(':image', $d_image);
  $insert_cart->execute();
  $message[] = 'added to cart!';
}
?>
<!DOCTYPE html>
<html lang="en"><head>
</head><body>
  <section class="quick-view">
    <h1 class="title">quick view</h1>
    <?php
    if (isset($_GET['d_id'])) {
      $d_id = $_GET['d_id'];
      $select_dishes = $conn->prepare("SELECT * FROM `dishes`INNER JOIN categories ON dishes.cate_id = categories.cate_id WHERE d_id = :d_id");
      $select_dishes->bindParam(':d_id', $d_id);
      $select_dishes->execute();
if ($select_dishes->rowCount() > 0) {
   while ($fetch_dishes = $select_dishes->fetch(PDO::FETCH_ASSOC)) { ?>
          <form action="" class="box" method="POST">
            <div class="price">$<span><?= $fetch_dishes['price']; ?></span>/-</div>
            <img src="uploaded_img/<?= $fetch_dishes['image']; ?>" alt="">
            <div class="name"><?= $fetch_dishes['title']; ?></div>
            <div class="category"><?= $fetch_dishes['name']; ?></div>
            <div class="details"><?= $fetch_dishes['details']; ?></div>
            <input type="hidden" name="d_id" value="<?= $fetch_dishes['d_id']; ?>">
            <input type="hidden" name="d_title" value="<?= $fetch_dishes['title']; ?>">
            <input type="hidden" name="d_price" value="<?= $fetch_dishes['price']; ?>">
            <input type="hidden" name="d_image" value="<?= $fetch_dishes['image']; ?>">
            <input type="number" min="1" value="1" name="d_qty" class="qty">
            <input type="submit" value="add to cart" class="btn" name="add_to_cart">
          </form>
        <?php    }
      }else{echo '<p class="empty">no dishes added yet!</p>';}
    } 
    ?>
    </section>
    <?php include 'footer.php'; ?>
    <script src="js/script.js"></script></body></html>
