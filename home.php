<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
};

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
<?php include 'Cart_function.php'; ?>
<?php include 'header.php'; ?>

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
     
      <img src="uploaded_img/<?= $fetch_cate['image1']; ?>" alt="">
      <div class="name" style ="font-size: 30px; color:var(--orange);"><?= $fetch_cate['name']; ?></div><br>
      <div class="description" style ="font-size: 13px" ><?= $fetch_cate['description']; ?></div>
      <div class="btn"><?= $fetch_cate['name']; ?></div>
      <input type="hidden" name="id" value="<?= $fetch_cate['cates_id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_cate['name']; ?>">
      <input type="hidden" name="description" value="<?= $fetch_cate['description']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_cate['image1']; ?>">
   </form>
   <?php endforeach  ?>
</section>

<section class="dishes">

   <h1 class="title">Latest dishes</h1>

   <div class="box-container">

   <?php
      $select_dishes = $conn->prepare("SELECT * FROM `dishes` INNER JOIN categories ON dishes.cate_id = categories.cates_id LIMIT 6");
      $select_dishes->execute();
      if($select_dishes->rowCount() > 0){
         while($fetch_dishes = $select_dishes->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" class="box" method="POST">
   <div class="price"><span><?= $fetch_dishes['price']; ?></span>VNĐ</div>
      <a href="view_page.php?d_id=<?= $fetch_dishes['id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_dishes['image']; ?>" alt="">
      <div class="title" style ="font-size: 15px;"><?= $fetch_dishes['title']; ?></div>
      <div class="category" style ="font-size: 20px; color:var(--orange);">(<?= $fetch_dishes['name']; ?>)</div>
      <input type="hidden" name="d_id" value="<?= $fetch_dishes['id']; ?>">
      <input type="hidden" name="title" value="<?= $fetch_dishes['title']; ?>">
      <input type="hidden" name="cid" value="<?= $fetch_dishes['id']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_dishes['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_dishes['image']; ?>">
      <input type="number" min="1" value="1" name="qty" class="qty">
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