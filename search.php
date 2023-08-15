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
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
  
</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="search-form">

   <form action="" method="POST">
      <input type="text" class="box" name="search_box" placeholder="search products...">
      <input type="submit" name="search_btn" value="search" class="btn">
   </form>

</section>

<?php



?>

<section class="products" style="padding-top: 0; min-height:100vh;">

   <div class="box-container">

   <?php

$sql = "SELECT * FROM categories";
$cate_ids = $conn->prepare($sql); 
$cate_ids->execute();
$cate_id = $cate_ids->fetchAll( PDO::FETCH_ASSOC );

if(isset($_GET['name'])){

$cate_id = $_GET['name'];
$select_cate = $conn->prepare("SELECT * FROM `categories` WHERE cates_id = ?");
$select_cate->execute([$cate_id]);

};
      if(isset($_POST['search_btn'])){
      $search_box = $_POST['search_box'];
      $search_box = filter_var($search_box, FILTER_SANITIZE_STRING);
      $select_dishes = $conn->prepare("SELECT * FROM `dishes` INNER JOIN categories ON dishes.cate_id = categories.cates_id WHERE title LIKE '%{$search_box}%'");
      $select_dishes->execute();
      if($select_dishes->rowCount() > 0){
         while($fetch_dishes = $select_dishes->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action='' class="box" method="POST">
      <div class="price"><?= $fetch_dishes['price']; ?>&#8363</div>
      <img src="uploaded_img/<?= $fetch_dishes['image']; ?>" alt="">
      <div class="name"><?= $fetch_dishes['title']; ?></div>
      <div class="cate_id"><?= $fetch_dishes['name']; ?></div>
      <div class="details"><?= $fetch_dishes['details']; ?></div>
      <div class="flex-btn">
         <a href="admin_update_dish.php?update=<?= $fetch_dishes['id']; ?>" class="option-btn">update</a>
         <a href="admin_dishes.php?delete=<?= $fetch_dishes['id']; ?>" class="delete-btn" onclick="return confirm('delete this dish?');">delete</a>
      </div>
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