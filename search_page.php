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
   <style>
  @import url('components.css');

.home-bg{
   background: url(../images/1.jpg) no-repeat;
   background-size: cover;
   background-position: center;
}

.home-bg .home{
   display: flex;
   align-items: center;
   min-height: 60vh;
}

.home-bg .home .content{
   width: 50rem;
}

.home-bg .home .content span{
   color:var(--orange);
   font-size: 2.5rem;
}

.home-bg .home .content h3{
   font-size: 3rem;
   text-transform: uppercase;
   margin-top: 1.5rem;
   color:var(--black);
}

.home-bg .home .content p{
   font-size: 1.6rem;
   padding:1rem 0;
   line-height: 2;
   color:var(--light-color);
}

.home-bg .home .content a{
   display: inline-block;
   width: auto;
}

.home-category .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 27rem);
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.home-category .box-container .box{
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.home-category .box-container .box img{
   width: 100%;
   margin-bottom: 1rem;
}

.home-category .box-container .box h3{
   text-transform: uppercase;
   color:var(--black);
   padding:1rem 0;
   font-size: 60px;
}

.home-category .box-container .box p{
   line-height: 2;
   font-size: 9px;
   color:var(--light-color);
   padding:.5rem 0;
}
.home-bg .home-category .box-container .box .name
.home-category{
   padding-bottom: 0;
}

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

.quick-view .box{
   max-width: 50rem;
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   position: relative;
   margin:0 auto;
}

.quick-view .box img{
   height: 25rem;
   margin-bottom: 1rem;
}

.quick-view .box .price{
   position: absolute;
   top:1rem; left:1rem;
   padding:1rem;
   border-radius: .5rem;
   background-color: var(--red);
   font-size: 1.8rem;
   color:var(--white);
}

.quick-view .box .price span{
   font-size: 2.5rem;
   color:var(--white);
   margin:0 .2rem;
}

.quick-view .box .qty{
   margin:.5rem 0;
   border-radius: .5rem;
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   border:var(--border);
   width: 100%;
}

.quick-view .box .name{
   font-size: 2rem;
   color:var(--black);
   padding:1rem 0;
}

.quick-view .box .details{
   padding:1rem 0;
   line-height: 2;
   font-size: 1.5rem;
   color:var(--light-color);
}

.p-category{
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(27rem, 1fr));
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.p-category{
   padding-bottom: 0;
}

.p-category a{
   padding:1.5rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   font-size: 2rem;
   text-transform: capitalize;
   color:var(--black);
}

.p-category a:hover{
   background-color: var(--black);
   color: var(--white);
}

.about .row{
   display: flex;
   flex-wrap: wrap;
   gap:3rem;
   align-items: center;
}

.about .row .box{
   flex:1 1 40rem;
   text-align: center;
}

.about .row .box img{
   margin-bottom: 2rem;
   height: 40rem;
}

.about .row .box h3{
   padding:1rem 0;
   font-size: 2.5rem;
   text-transform: uppercase;
   color:var(--black);
}

.about .row .box p{
   line-height: 2;
   font-size: 1.5rem;
   color:var(--light-color);
   padding:1rem 0;
}

.about .row .box .btn{
   display: inline-block;
   width: auto;
}

.reviews .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(30rem, 1fr));
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.reviews .box-container .box{
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.reviews .box-container .box img{
   height: 10rem;
   width: 10rem;
   border-radius: 50%;
   margin-bottom: 1rem;
   object-fit: cover;
}

.reviews .box-container .box p{
   padding:1rem 0;
   font-size: 1.6rem;
   color:var(--light-color);
   line-height: 2;
}

.reviews .box-container .box .stars{
   display: inline-block;
   padding:1rem;
   background-color: var(--light-bg);
   border:var(--border);
   border-radius: .5rem;
   margin:.5rem 0;
}

.reviews .box-container .box .stars i{
   font-size: 1.7rem;
   color:var(--orange);
   margin:0 .3rem;
}

.reviews .box-container .box h3{
   margin-top: 1rem;
   color:var(--black);
   font-size: 2rem;
}

.contact form{
   margin:0 auto;
   max-width: 50rem;
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   padding-top: 1rem;
}

.contact form .box{
   width: 100%;
   padding:1.2rem 1.4rem;
   border:var(--border);
   margin:1rem 0;
   background-color: var(--light-bg);
   font-size: 1.8rem;
   color:var(--black);
   border-radius: .5rem;
}

.contact form textarea{
   height: 15rem;
   resize: none;
}

.search-form form{
   display: flex;
   gap:1.5rem;
   align-items: center;
}

.search-form form .box{
   width: 100%;
   padding:1.4rem;
   border:var(--border);
   margin:1rem 0;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   font-size: 2rem;
   color:var(--black);
   border-radius: .5rem;
}

.search-form form .btn{
   display: inline-block;
   width: auto;
   margin-top: 0;
}

.shopping-cart .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 35rem);
   gap:1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.shopping-cart .box-container .box{
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
   position: relative;
}

.shopping-cart .box-container .box .price{
   padding:1rem 0;
   color:var(--red);
   font-size: 2.5rem;
}

.shopping-cart .box-container .box .price span{
   font-size: 2.5rem;
   color:var(--white);
   margin:0 .2rem;
}

.shopping-cart .box-container .box .fa-eye{
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

.shopping-cart .box-container .box .fa-eye:hover{
   color:var(--white);
   background-color: var(--black);
}

.shopping-cart .box-container .box .fa-times{
   position: absolute;
   top:1rem; left:1rem;
   border-radius: .5rem;
   height: 4.5rem;
   line-height: 4.3rem;
   width: 5rem;
   color:var(--white);
   font-size: 2rem;
   background-color: var(--red);
}

.shopping-cart .box-container .box .fa-times:hover{
   background-color: var(--black);
}

.shopping-cart .box-container .box img{
   width: 100%;
   margin-bottom: 1rem;
}

.shopping-cart .box-container .box .name{
   font-size: 2rem;
   color:var(--black);
   padding-top: 1rem;
}

.shopping-cart .box-container .box .qty{
   margin-top: 1rem;
   border-radius: .5rem;
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   border:var(--border);
   width: 100%;
}

.shopping-cart .box-container .sub-total{
   margin-top: 2rem;
   font-size: 2rem;
   color:var(--light-color);
}

.shopping-cart .box-container .sub-total span{
   color:var(--red);
}

.shopping-cart .cart-total{
   max-width: 50rem;
   margin: 0 auto;
   margin-top: 2rem;
   padding:2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.shopping-cart .cart-total p{
   margin-bottom: 2rem;
   font-size: 2.5rem;
   color:var(--light-color);
}

.shopping-cart .cart-total p span{
   color:var(--red);
}

.display-orders{
   text-align: center;
   padding-bottom: 0;
}

.display-orders p{
   display: inline-block;
   padding:1rem 2rem;
   margin:1rem .5rem;
   font-size: 2rem;
   text-align: center;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.display-orders p span{
   color:var(--red);
}

.display-orders .grand-total{
   margin-top: 2rem;
   font-size: 2.5rem;
   color:var(--light-color);
}

.display-orders .grand-total span{
   color:var(--red);
}

.checkout-orders form{
   padding:2rem;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.checkout-orders form h3{
   border-radius: .5rem;
   background-color: var(--black);
   color:var(--white);
   padding:1.5rem 1rem;
   text-align: center;
   text-transform: uppercase;
   margin-bottom: 2rem;
   font-size: 2.5rem;
}

.checkout-orders form .flex{
   display: flex;
   flex-wrap: wrap;
   gap:1.5rem;
   justify-content: space-between;
}

.checkout-orders form .flex .inputBox{
   width: 49%;
}

.checkout-orders form .flex .inputBox .box{
   width: 100%;
   border:var(--border);
   border-radius: .5rem;
   font-size: 1.8rem;
   color:var(--black);
   padding:1.2rem 1.4rem;
   margin:1rem 0;
   background-color: var(--light-bg);
}

.checkout-orders form .flex .inputBox span{
   font-size: 1.8rem;
   color:var(--light-color);
}

.placed-orders .box-container{
   display: flex;
   flex-wrap: wrap;
   gap:1.5rem;
   align-items: flex-start;
}

.placed-orders .box-container .box{
   padding:1rem 2rem;
   flex:1 1 40rem;
   border:var(--border);
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border-radius: .5rem;
}

.placed-orders .box-container .box p{
   margin:.5rem 0;
   line-height: 1.8;
   font-size: 2rem;
   color:var(--light-color);
}

.placed-orders .box-container .box p span{
   color:var(--green);
}









@media (max-width:768px){

   .home-bg{
      background-position: left;
   }

   .home-bg .home{
      justify-content: center;
      text-align: center;
   }

   .checkout-orders form .flex .inputBox{
      width: 100%;
   }

}

@media (max-width:450px){

   .home-category .box-container{
      grid-template-columns: 1fr;
   }

   .dishes .box-container{
      grid-template-columns: 1fr;
   }

   .quick-view .box img{
      height: auto;
      width: 100%;
   }

   .about .row .box img{
      width: 100%;
      height: auto;
   }   

   .shopping-cart .box-container{
      grid-template-columns: 1fr;
   }


}
   </style>
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
      $select_dishes = $conn->prepare("SELECT * FROM `dishes` INNER JOIN categories ON dishes.cate_id = categories.cate_id  WHERE title LIKE '%{$search_box}%'");
      $select_dishes->execute();
      if($select_dishes->rowCount() > 0){
         while($fetch_dishes = $select_dishes->fetch(PDO::FETCH_ASSOC)){ 
   ?>
     <form action='dishes.php?cate_id=<?php echo $_GET['cate_id'];?>&action=add&id=<?php echo $fetch_dish['d_id']; ?>' class="box" method="POST">
      <div class="price">$<span><?= $fetch_dishes['price']; ?></span></div>
      <a href="view_page.php?did=<?= $fetch_dishes['d_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_dishes['image1']; ?>" alt="">
      <div class="category" style ="font-size: 16px;"><?= $fetch_dishes['title']; ?></div>
      <div class="name" style ="font-size: 20px; color:var(--orange);"><?= $fetch_dishes['name']; ?></div>
      <input type="hidden" name="d_id" value="<?= $fetch_dishes['d_id']; ?>">
      <input type="hidden" name="d_name" value="<?= $fetch_dishes['title']; ?>">
     <input type="hidden" name="cid" value="<?= $fetch_dishes['cate_id']; ?>">
      <input type="hidden" name="d_price" value="<?= $fetch_dishes['price']; ?>">
      <input type="hidden" name="d_image" value="<?= $fetch_dishes['image1']; ?>">
      <input type="number" min="1" value="1" name="d_qty" class="qty">
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