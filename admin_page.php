<?php

@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>admin page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">



</head>
<body class="fix-header">
    
    <!-- Main wrapper  -->
    <div id="main-wrapper">
    
<?php include 'admin_header.php'; ?>

<?php include 'left_sidebar.php'; ?>


<!-- Page wrapper  -->
        <section class="dashboard" style="height:1200px; margin-left:230px;">
            <!-- Bread crumb -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-primary">Dashboard</h3> </div>
               
            </div>
            <!-- End Bread crumb -->
   <div class="box-container" >
   <div class="box">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span>  <i class="fa-solid fa-user fa-2xl" style="color: #fa6400;"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2>
                                    <?php
         $select_accounts = $conn->prepare("SELECT * FROM `users`");
         $select_accounts->execute();
         $number_of_accounts = $select_accounts->rowCount();
         echo  $number_of_accounts;
      ?></h2>
                                    <p class="m-b-0">Users</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="box">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle"> 
                                    <span><i class="fa-solid fa-cart-shopping fa-2xl" aria-hidden="true" style="color: #fa6400;"></i>  </span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2>
                                    <?php
         $select_orders = $conn->prepare("SELECT * FROM `orders`");
         $select_orders->execute();
         $number_of_orders = $select_orders->rowCount();
         echo  $number_of_orders;
      ?>
                                    </h2>
                                    <p class="m-b-0">Orders</p>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="box">
                        <div class="card p-30">
                            <div class="media">
                                <div class="media-left meida media-middle">
                                    <span><i class="fa-solid fa-utensils fa-2xl" aria-hidden="true"  style="color: #fa6400;"></i></span>
                                </div>
                                <div class="media-body media-text-right">
                                    <h2>
                                    <?php
         $select_dishes = $conn->prepare("SELECT * FROM `dishes`");
         $select_dishes->execute();
         $number_of_dishes = $select_dishes->rowCount();
         echo    $number_of_dishes ;
      ?>
                                       </h2>
                                    <p class="m-b-0">Dishes</p>
                                </div>
                            </div>
                        </div>
                    </div>

   </div>

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