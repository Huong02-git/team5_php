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
   <title>Category</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'header.php'; ?>
  
   <!-- start: Inner page hero -->
            <div class="inner-page-hero bg-image" data-image-src="images/img/res.jpeg">
                <div class="container"> </div>
                <!-- end:Container -->
            </div>
            <div class="result-show">
                <div class="container">
                    <div class="row">
                       
                       
                    </div>
                </div>
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
														
  <br><br>                           
         
                          


<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>