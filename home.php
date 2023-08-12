<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};


if(isset($_POST['add_to_cart'])){

   $d_id = $_POST['d_id'];
   $d_id = filter_var($did, FILTER_SANITIZE_STRING);
   $d_name = $_POST['d_name'];
   $d_name = filter_var($d_name, FILTER_SANITIZE_STRING);
   $d_price = $_POST['d_price'];
   $d_price = filter_var($d_price, FILTER_SANITIZE_STRING);
   $d_image = $_POST['d_image'];
   $d_image = filter_var($d_image, FILTER_SANITIZE_STRING);
   $d_qty = $_POST['d_qty'];
   $d_qty = filter_var($d_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` join `Details_cart` on cart.cart_id =  Details_cart.cart_id WHERE title = ? AND user_id = ?");
   $check_cart_numbers->execute([$p_name, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

     
      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, d_id, name, price, quantity, image) VALUES(?,?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $did, $d_name, $d_price, $d_qty, $d_image]);
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
   <title>home page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">
   
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'header.php'; ?>
<
<div class="home-bg">

   <section class="home">

      <div class="content">
         <span>Supper hungry?</span>
         <h3>Don't be panic, we have varierty dishes for you</h3>
         <p>Got Hungry? Get the food you want, from our luxery restaurant, delivered at blinking speed.</p>
         <a href="dishes.php" class="btn">ORDER NOW</a>
      </div>

   </section>

</div>

<section class="home-category">

   <h1 class="title">Our Menu</h1>

   <div class="box-container">
   <?php
   //cate
$fetch_cates = $conn->prepare( "SELECT * FROM categories" );
$fetch_cates->execute();
$fetch_cates = $fetch_cates->fetchAll( PDO::FETCH_ASSOC );
?>

   <?php foreach ( $fetch_cates as $fetch_cate ): ?>
   <form action="" class="box" method="POST">
     
      <img src="uploaded_img/<?= $fetch_cate['image']; ?>" alt="">
      <div class="name"><?= $fetch_cate['name']; ?></div>
      <div class="description"><?= $fetch_cate['description']; ?></div>
      <div class="btn"><?= $fetch_cate['name']; ?></div>
      <input type="hidden" name="cate_id" value="<?= $fetch_cate['cate_id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_cate['name']; ?>">
      <input type="hidden" name="description" value="<?= $fetch_cate['description']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_cate['image']; ?>">
   </form>
   <?php endforeach  ?>
</section>

<section class="products">

   <h1 class="title">Latest dishes</h1>

   <div class="box-container">

   <?php
      $select_dishes = $conn->prepare("SELECT * FROM `dishes` INNER JOIN categories ON dishes.cate_id = categories.cate_id LIMIT 6");
      $select_dishes->execute();
      if($select_dishes->rowCount() > 0){
         while($fetch_dishes = $select_dishes->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action='dishes.php?cate_id=<?php echo $_GET['cate_id'];?>&action=add&id=<?php echo $fetch_dish['d_id']; ?>' class="box" method="POST">
      <div class="price">$<span><?= $fetch_dishes['price']; ?></span></div>
      <a href="view_page.php?did=<?= $fetch_dishes['d_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_dishes['image']; ?>" alt="">
      <div class="category"><?= $fetch_dishes['title']; ?></div>
      <div class="name"><?= $fetch_dishes['name']; ?></div>
      <input type="hidden" name="did" value="<?= $fetch_dishes['d_id']; ?>">
      <input type="hidden" name="d_name" value="<?= $fetch_dishes['title']; ?>">
     <input type="hidden" name="cid" value="<?= $fetch_dishes['cate_id']; ?>">
      <input type="hidden" name="d_price" value="<?= $fetch_dishes['price']; ?>">
      <input type="hidden" name="d_image" value="<?= $fetch_dishes['image']; ?>">
      <input type="number" min="1" value="1" name="d_qty" class="qty">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no dishes added yet!</p>';
   }
   ?>

   </div>

</section>






<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>