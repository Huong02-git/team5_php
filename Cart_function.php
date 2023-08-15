<?php
if(isset($_POST['add_to_cart'])){

if($user_id == ''){
   header('location:user_login.php');
}else{
   $d_id = $_POST['d_id'];
   $sql = "SELECT * FROM dishes WHERE id = :d_id";
   $stmt = $conn->prepare($sql);
   $stmt->bindParam(':d_id', $d_id);
   $stmt->execute();
   $product = $stmt->fetch(PDO::FETCH_ASSOC);
// Thêm sản phẩm vào bảng cart
   $d_id = $_POST['d_id'];
   $d_id = filter_var($d_id, FILTER_SANITIZE_STRING);
   $title = $_POST['title'];
   $title = filter_var($title, FILTER_SANITIZE_STRING);
   $price = $_POST['price'];
   $price = filter_var($price, FILTER_SANITIZE_STRING);
   $image = $_POST['image'];
   $image = filter_var($image, FILTER_SANITIZE_STRING);
   $qty = $_POST['qty'];
   $qty = filter_var($qty, FILTER_SANITIZE_STRING);

   $check_cart_numbers = $conn->prepare("SELECT * FROM `cart` WHERE title = ? AND user_id = ?");
   $check_cart_numbers->execute([$title, $user_id]);

   if($check_cart_numbers->rowCount() > 0){
      echo "<span style='color:lightcoral; font-size: 20px; margin-left:60px'>Already added to cart!</span>";
   }else{
      $insert_cart = $conn->prepare("INSERT INTO `cart`(user_id, title, image) VALUES(?,?,?)");
      $insert_cart->execute([$user_id, $title, $image]);
      echo "<span style='color:green; font-size: 20px;margin-left:60px'>Added to cart!</span>";
   // Lấy khóa chính của bảng cart vừa thêm
   $cid = $conn->lastInsertId();

   // Thêm khóa chính của bảng carts và bảng dishes vào bảng details_cart
   $sql = "INSERT INTO `details_cart` (cid,d_id,totalDishes,price) VALUES (?,?,?,?)";
   $stmt = $conn->prepare($sql);
   $stmt->execute([$cid,$d_id,$qty,$price]);
      }
       }
    }
?>