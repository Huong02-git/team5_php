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

if(isset($_GET['cates_id'])){

$cate_id = $_GET['cates_id'];
$select_cate = $conn->prepare("SELECT * FROM `categories` WHERE cates_id = ?");
$select_cate->execute([$cate_id]);

};


if(isset($_POST['update_dish'])){

   $d_id = $_POST['d_id'];
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
   $old_image = $_POST['old_image'];

   $update_dish = $conn->prepare("UPDATE `dishes` SET title = ?, cate_id = ?, details = ?, price = ? WHERE dishes.id = ?");
   $update_dish->execute([$title, $cate_id, $details, $price, $d_id]);

   $message[] = 'dish updated successfully!';

   if(!empty($image)){
      if($image_size > 2000000){
         $message[] = 'image size is too large!';
      }else{

         $update_image = $conn->prepare("UPDATE `dishes` SET image = ? WHERE dishes.id = ?");
         $update_image->execute([$image, $d_id]);

         if($update_image){
            move_uploaded_file($image_tmp_name, $image_folder);
            unlink('uploaded_img/'.$old_image);
            $message[] = 'image updated successfully!';
         }
      }
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>update dishes</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">


</head>

<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-dish">

   <h1 class="title">update dish</h1>   

   <?php
      $update_id = $_GET['update'];
      $select_dishes = $conn->prepare("SELECT * FROM `dishes` WHERE id = ?");
      $select_dishes->execute([$update_id]);
      if($select_dishes->rowCount() > 0){
         while($fetch_dishes = $select_dishes->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image" value="<?= $fetch_dishes['image']; ?>">
      <input type="hidden" name="d_id" value="<?= $fetch_dishes['id']; ?>">
      <img src="uploaded_img/<?= $fetch_dishes['image']; ?>" alt="">
      <input type="text" name="title" placeholder="enter dish title" required class="box" value="<?= $fetch_dishes['title']; ?>">
      <input type="number" name="price" min="0" placeholder="enter dish price" required class="box" value="<?= $fetch_dishes['price']; ?>">
      <select name="cate_id" class="box" required>
           <?php 
                    foreach($cate_id as $value):
                ?>
                <option value="<?=$value['cates_id']?>"><?= $value['name'] ?></option>
                <?php
                    endforeach;
                ?>
         </select>
      <textarea name="details" required placeholder="enter dish details" class="box" cols="30" rows="10"><?= $fetch_dishes['details']; ?></textarea>
      <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png">
      <div class="flex-btn">
         <input type="submit" class="btn" value="update dish" name="update_dish">
         <a href="admin_dishes.php" class="option-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no dishes found!</p>';
      }
   ?>

</section>

<script src="js/script.js"></script>

</body>
</html>