<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};


if(isset($_POST['add_to_cart'])){

   $d_id = $_POST['d_id'];
   $d_id = filter_var($d_id, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $image = $_POST['image'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` join `details_cart` on cart.id =  details_cart.cid  WHERE title = ? AND user_id = ?");
   $check_cart_numbers->execute([$title, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, d_id, title, price, image) VALUES(?,?,?,?,?)");
      $insert_cart->execute([$user_id, $d_id, $title, $price, $image]);
      $message[] = 'added to cart!';
   }

}



?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>

<section class="search-form">

   <form action="" method="POST">
      <input type="text" class="box" name="search_box" placeholder="search products...">
      <input type="submit" name="search_btn" value="search" class="btn">
   </form>

</section>

<?php



?>

<section class="dishes" style="padding-top: 0; min-height:100vh;">

   <div class="box-container">

   <?php
      if(isset($_POST['search_btn'])){
      $search_box = $_POST['search_box'];
      $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
      $select_dishes = $conn->prepare("SELECT * FROM `dishes` INNER JOIN categories ON dishes.cate_id = categories.cates_id  WHERE title LIKE '%{$search_box}%'");
      $select_dishes->execute();
      if($select_dishes->rowCount() > 0){
         while($fetch_dishes = $select_dishes->fetch(PDO::FETCH_ASSOC)){ 
   ?>
     <form action='dishes.php?cate_id=<?php echo $_GET['cate_id'];?>&action=add&id=<?php echo $fetch_dish['d_id']; ?>' class="box" method="POST">
      <div class="price">$<span><?= $fetch_dishes['price']; ?></span></div>
      <a href="view_page.php?did=<?= $fetch_dishes['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_dishes['image']; ?>" alt="">
      <div class="category"><?= $fetch_dishes['title']; ?></div>
      <div class="name"><?= $fetch_dishes['name']; ?></div>
      <input type="hidden" name="d_id" value="<?= $fetch_dishes['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_dishes['title']; ?>">
     <input type="hidden" name="cid" value="<?= $fetch_dishes['id']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_dishes['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_dishes['image']; ?>">
      <input type="number" min="1" value="1" name="qty" class="qty">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no result found!</p>';
      }
      
   };
   ?>

   </div>

</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>