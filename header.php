
<header class="header">

<div class="flex">

   <a href="admin_page.php" class="logo"><i class="fa fa-cutlery" aria-hidden="true"></i>Andee<span>.</span></a>
   <nav class="navbar">
      <a href="home.php">Home</a>
      <a href="category.php">Menu</a>
     <a href="dishes.php">Dishes</a>
      <a href="orders.php">Orders</a>
   </nav>

   <div class="icons">
      <div id="menu-btn" class="fas fa-bars"></div>
      <div id="user-btn" class="fas fa-user"></div>
      <a href="search_page.php" class="fas fa-search"></a>
      <?php
         $count_cart_items = $conn->prepare("SELECT * FROM `cart` join `Details_cart` on cart.cart_id =  Details_cart.cart_id  WHERE user_id = ?");
         $count_cart_items->execute([$user_id]);
      ?>
      <a href="cart.php"><i class="fas fa-shopping-cart"></i><span>(<?= $count_cart_items->rowCount(); ?>)</span></a>
   </div>

   <div class="profile">
      <?php
         $select_profile = $conn->prepare("SELECT * FROM `users` WHERE user_id = ?");
         $select_profile->execute([$user_id]);
         $fetch_profile = $select_profile->fetch(PDO::FETCH_ASSOC);
      ?>
      <img src="uploaded_img/<?= $fetch_profile['image']; ?>" alt="">
      <p><?= $fetch_profile['name']; ?></p>
      <a href="user_profile_update.php" class="btn">update profile</a>
      <a href="logout.php" class="delete-btn">logout</a>
      <div class="flex-btn">
         <a href="login.php" class="option-btn">login</a>
         <a href="register.php" class="option-btn">register</a>
      </div>
   </div>

</div>

</header>