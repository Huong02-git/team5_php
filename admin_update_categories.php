<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};

if(isset($_POST['update_category'])){
  $cate_id= $_POST['cates_id'];
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $description = $_POST['description'];
   $description = filter_var($description, FILTER_SANITIZE_STRING);
  
   $image1 = $_FILES['image1']['name'];
   $image1 = filter_var($image1, FILTER_SANITIZE_STRING);
   $image1_size = $_FILES['image1']['size'];
   $image1_tmp_name = $_FILES['image1']['tmp_name'];
   $image1_folder = 'uploaded_img/'.$image1;
   $old_image1 = $_POST['old_image1'];

   $update_category = $conn->prepare("UPDATE `categories` SET name = ?, description = ? WHERE cates_id = ?");
   $update_category->execute([$name, $description, $cate_id]);

   $message[] = 'category updated successfully!';

   if(!empty($image1)){
      if($image1_size > 2000000){
         $message[] = 'image size is too large!';
      }else{

         $update_image1 = $conn->prepare("UPDATE `categories` SET image1 = ? WHERE cates_id = ?");
         $update_image1->execute([$image1, $cate_id]);

         if($update_image1){
            move_uploaded_file($image1_tmp_name, $image1_folder);
            unlink('uploaded_img/'.$old_image1);
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
   <title>update categories</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
 

</head>
<body>
   
<?php include 'admin_header.php'; ?>

<section class="update-category">

   <h1 class="title">update category</h1>   

   <?php
      $update_id = $_GET['update'];
      $select_categories = $conn->prepare("SELECT * FROM `categories` WHERE cates_id = ?");
      $select_categories->execute([$update_id]);
      if($select_categories->rowCount() > 0){
         while($fetch_categories = $select_categories->fetch(PDO::FETCH_ASSOC)){ 
   ?>
   <form action="" method="post" enctype="multipart/form-data">
      <input type="hidden" name="old_image1" value="<?= $fetch_categories['image1']; ?>">
      <input type="hidden" name="cate_id" value="<?= $fetch_categories['cates_id']; ?>">
      <img src="uploaded_img/<?= $fetch_categories['image1']; ?>" alt="">
      <input type="text" name="name" placeholder="enter category name" required class="box" value="<?= $fetch_categories['name']; ?>">
      <textarea name="description" required placeholder="enter category description" class="box" cols="30" rows="10"><?= $fetch_categories['description']; ?></textarea>
      <input type="file" name="image1" class="box" accept="image/jpg, image/jpeg, image/png">
      <div class="flex-btn">
         <input type="submit" class="btn" value="update category" name="update_category">
         <a href="admin_categories.php" class="option-btn">go back</a>
      </div>
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">no categories found!</p>';
      }
   ?>

</section>








<script src="js/script.js"></script>

</body>
</html>