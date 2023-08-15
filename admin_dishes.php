<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

 $sql = "SELECT * FROM categories";
   $cate_ids = $conn->prepare($sql); 
   $cate_ids->execute();
   $cate_id = $cate_ids->fetchAll( PDO::FETCH_ASSOC );

if(isset($_GET['name'])){

   $cate_id = $_GET['name'];
   $select_cate = $conn->prepare("SELECT * FROM `categories` WHERE cates_id = ?");
   $select_cate->execute([$cate_id]);

};

if(isset($_POST['add_dish'])){

   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $cate_id = $_POST['cate_id'];
   $cate_id = filter_var($cate_id, FILTER_SANITIZE_STRING);
   $details = $_POST['details'];
   $details = filter_var($details, FILTER_SANITIZE_STRING);

   $image = $_FILES['image']['name'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $image_size = $_FILES['image']['size'];
   $image_tmp_name = $_FILES['image']['tmp_name'];
   $image_folder = 'uploaded_img/'.$image;

   $select_dishes = $conn->prepare("SELECT * FROM `dishes` INNER JOIN categories ON dishes.cate_id = categories.cates_id WHERE title = ?");
   $select_dishes->execute([$title]);

   if($select_dishes->rowCount() > 0){
      $message[] = 'dishes title already exist!';
   }else{

   $insert_dishes = $conn->prepare("INSERT INTO `dishes`(title, cate_id, details, price, image) VALUES(?,?,?,?,?)");
  $insert_dishes->execute([$title, $cate_id, $details, $price, $image]);

  if($insert_dishes){
     if($image_size > 2000000){
        $message[] = 'image size is too large!';
     }else{
        move_uploaded_file($image_tmp_name, $image_folder);
        $message[] = 'new dish added!';
     }

  }

   }

};

if(isset($_GET['delete'])){

   $delete_id = $_GET['delete'];
   $select_delete_image = $conn->prepare("SELECT image FROM `dishes` WHERE dishes.id = ?");
   $select_delete_image->execute([$delete_id]);   $fetch_delete_image = $select_delete_image->fetch(PDO::FETCH_ASSOC);
   unlink('uploaded_img/'.$fetch_delete_image['image']);
   $delete_dishes = $conn->prepare("DELETE FROM `dishes` WHERE dishes.id = ?");
   $delete_dishes->execute([$delete_id]);
   $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE cart.id = ?");
   $delete_cart->execute([$delete_id]);
   header('location:admin_dishes.php');
}
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dishes</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   

   
</head>
<body>
<?php include 'admin_header.php'; ?>

<section class="search-form">

   <form action="search.php" method="POST">
      <input type="text" class="box" name="search_box" placeholder="search dishes...">
      <input type="submit" name="search_btn" value="search" class="btn">
   </form>

</section>
<section class="add-dishes">

   <h1 class="title">add new dish</h1>

  
   <form action="" method="POST" enctype="multipart/form-data">
      <div class="flex">
         <div class="inputBox">
         <input type="text" name="title" class="box" required placeholder="enter dishes title">
         <select name="cate_id" class="box" required>
           <?php 
                    foreach($cate_id as $value):
                ?>
                <option value="<?=$value['cates_id']?>"><?= $value['name'] ?></option>
                <?php
                    endforeach;
                ?>
         </select>
         </div>
         <div class="inputBox">
         <input type="number" min="0" name="price" class="box" required placeholder="enter dish price">
         <input type="file" name="image" required class="box" accept="image/jpg, image/jpeg, image/png">
         </div>
      </div>
      <textarea name="details" class="box" required placeholder="enter dish details" cols="30" rows="10"></textarea>
      <input type="submit" class="btn" value="add dish" name="add_dish">
   </form>

</section>

  
  
<section class="show-dishes">

   <h1 class="title">dish added</h1>
  
   <div class="box-container">

   <?php
      $show_dishes = $conn->prepare("SELECT * FROM `dishes` INNER JOIN categories ON dishes.cate_id = categories.cates_id");
      $show_dishes->execute();
      if($show_dishes->rowCount() > 0){
         while($fetch_dishes = $show_dishes->fetch(PDO::FETCH_ASSOC)){  
   ?>
   <form action='' class="box" method="POST">
      <div class="price"><?= $fetch_dishes['price']; ?>VNĐ</div>
      <img src="uploaded_img/<?= $fetch_dishes['image']; ?>" alt="">
      <div class="name"><?= $fetch_dishes['title']; ?></div>
      <div class="cate_id" style="font-size: 20px; color: orange;"><?= $fetch_dishes['name']; ?></div>
      <div class="details"><?= $fetch_dishes['details']; ?></div>
      <div class="flex-btn">
         <a href="admin_update_dish.php?update=<?= $fetch_dishes['id']; ?>" class="option-btn">Update</a>
         <a href="admin_dishes.php?delete=<?= $fetch_dishes['id']; ?>" class="delete-btn" onclick="return confirm('delete this dish?');">Delete</a>
      </div>
   </form>
   <?php
      }
   }else{
      echo '<p class="empty">no dish added yet!</p>';
   }
   ?>

   </div>

</section>
<!-- footer -->
<footer class="footer"> 
 <p class="credit"> @ <?= date('Y'); ?> Website made by <span>VANTH</span>🧡🧡🧡</p>
</footer>
            <!-- End footer -->



<script src="js/script.js"></script>

</body>
</html>