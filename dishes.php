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
   $d_title = $_POST['d_title'];
   $d_title = filter_var($d_title, FILTER_SANITIZE_STRING);
   $d_price = $_POST['d_price'];
   $d_price = filter_var($d_price, FILTER_SANITIZE_STRING);
   $d_image = $_POST['d_image'];
   $d_image = filter_var($d_image, FILTER_SANITIZE_STRING);
   $d_qty = $_POST['d_qty'];
   $d_qty = filter_var($d_qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` join `Details_cart` on cart.cart_id =  Details_cart.cart_id  WHERE title = ? AND user_id = ?");
   $check_cart_numbers->execute([$d_title, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      $message[] = 'already added to cart!';
   }else{

      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, d_id, title, price, quantity, image) VALUES(?,?,?,?,?,?)");
      $insert_cart->execute([$user_id, $d_id, $d_title, $d_price, $d_qty, $d_image]);
      $message[] = 'added to cart!';
   }

}


// user input

if(isset($_GET['page'])){
   $page = $_GET['page'];
}else{
 $page =1;
}
$limit = 6;

// positioning
$start = ($page -1) * $limit;
$previous = $page -1;
$next = $page +1;
// query
$fetch_dishes = $conn->prepare( "SELECT SQL_CALC_FOUND_ROWS * FROM dishes INNER JOIN categories ON dishes.cate_id = categories.cate_id LIMIT {$start}, {$limit}" );
$fetch_dishes->execute();
$fetch_dishes = $fetch_dishes->fetchAll( PDO::FETCH_ASSOC );

// pages
$total = $conn->query( "SELECT FOUND_ROWS() as total" )->fetch()['total'];
$pages = ceil( $total / $limit );

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shop</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css\style.css"> 
<style>
  .dishes .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 35rem);
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.dishes .box-container .box{
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   position: relative;
}

.dishes .box-container .box .price{
   position: absolute;
   top:1rem; left:1rem;
   padding:1rem;
   border-radius: .5rem;
   background-color: var(--red);
   font-size: 1.8rem;
   color:var(--white);
   width: 150px;
}

.dishes .box-container .box .price span{
   font-size: 2.5rem;
   color:var(--white);
   margin:0 .2rem;
}

.dishes .box-container .box .fa-eye{
   position: absolute;
   top:1rem; right:1rem;
   border-radius: .5rem;
   height: 4.5rem;
   line-height: 4.3rem;
   width: 5rem;
   border:var(--border);
   color:var(--black);
   font-size: 2rem;
   background-color: var(--white);
}

.dishes .box-container .box .fa-eye:hover{
   color:var(--white);
   background-color: var(--black);
}

.dishes .box-container .box img{
   width: 100%;
   margin-bottom: 1rem;
}

.dishes .box-container .box .name{
   font-size: 2rem;
   color:var(--black);
   padding:1rem 0;
}

.dishes .box-container .box .qty{
   margin:.5rem 0;
   border-radius: .5rem;
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   border:var(--border);
   width: 100%;
}

.pagination {
      display: inline-block;
      border-radius: 10px;
      background-color:var(--black) ;
    }

    .pagination a.page-f {
      color:white;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      border: 1px solid #ddd;
      border-top-left-radius: 10px;
      border-bottom-left-radius: 10px;
      font-size: 20px;

    }

    .pagination a.page-link {
      color:white;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      border: 1px solid #ddd;
      font-size: 20px;

    }


    .pagination a.page-l {
      color:white;
      float: left;
      padding: 8px 16px;
      text-decoration: none;
      border: 1px solid #ddd;
      border-top-right-radius: 10px;
      border-bottom-right-radius: 10px;
      font-size: 20px;

    }

    .pagination a.active {
      background-color: var(--orange);
      color: white;
      border: 1px solid var(--orange);
    }

    .pagination a:hover:not(.active) {
      background-color: var(--orange);
    }

   </style>
</head>
<body>
   
<?php include 'header.php'; ?>


<section class="dishes">

   <h1 class="title">latest dishes</h1>

   <div class="box-container">  
   <?php foreach ( $fetch_dishes as $fetch_dishes): ?>
   <form action="" class="box" method="POST">
      <div class="price"><span><?= $fetch_dishes['price']; ?></span>VNĐ</div>

      <a href="view_page.php?d_id=<?= $fetch_dishes['d_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_dishes['image1']; ?>" alt="">
      <!-- <div class="name"><?= $fetch_dishes['title']; ?></div> -->
      <div class="category" style ="font-size: 16px; color:var(--orange);"><?= $fetch_dishes['name']; ?></div>
      <div class="name"><?= $fetch_dishes['title']; ?></div>
      <input type="hidden" name="did" value="<?= $fetch_dishes['d_id']; ?>">
      <input type="hidden" name="d_name" value="<?= $fetch_dishes['title']; ?>">
      <input type="hidden" name="cid" value="<?= $fetch_dishes['cate_id']; ?>">
      <input type="hidden" name="d_price" value="<?= $fetch_dishes['price']; ?>">
      <input type="hidden" name="d_image" value="<?= $fetch_dishes['image1']; ?>">
      <input type="number" min="1" value="1" name="d_qty" class="qty">
      <input type="submit" value="add to cart" class="btn" name="add_to_cart">
   </form>
   <?php endforeach  ?>
   </div>
<br><br>
  
  <center>
  <div class="pagination">
   <a class="page-f" href="dishes.php?page=1" >First</a>
   <a class="page-link" href="dishes.php?page=<?php echo $previous == 0 ? 1 : $previous ?>" >Previous</a>
    <?php 
  for ($i=1; $i <= $total ; $i++) {

         $current_page = $pages;
         $previous_1 = $current_page -5;
         $next_1 = $current_page +0;

if($i >= $previous_1 && $i <= $next_1 ) {
?>
   <a class="page-link" href="dishes.php?page=<?php echo $i ?>"><?php echo $i ?></a>
    <?php
}
 } ?>
   <a class="page-link" href="dishes.php?page=<?php echo $next > $total ? $total : $next ?>" >Next</a>
   <a class="page-l" href="dishes.php?page=<?php echo $total?>">Last</a>
</div>
<center>
			
</section>

<?php include 'footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>