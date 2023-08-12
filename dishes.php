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
$limit = 3;

// positioning
$start = ($page -1) * $limit;

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
   <link rel="stylesheet" href="css/style.css">
<style>
.preloader {
  width: 100%;
  height: 100%;
  top: 0;
  position: fixed;
  z-index: 99999;
  background: #fff;
}
.preloader .cssload-speeding-wheel {
  position: absolute;
  top: calc(46.5%);
  left: calc(46.5%);
}
* {
  outline: none;
}
body {
  background: #fff;
  font-family: 'Open Sans', sans-serif;
  margin: 0;
  overflow-x: hidden;
  color: #67757c;
}
html {
  position: relative;
  min-height: 100%;
  background: #ffffff;
}
a:focus,
a:hover {
  text-decoration: none;
}
a.link {
  color: #F0A500;
}
a.link:focus,
a.link:hover {
  color: #F0A500;
}
.img-responsive,
.carousel.vertical .carousel-inner > .item > img,
.carousel.vertical .carousel-inner > .item > a > img {
  width: 100%;
  height: auto;
  display: inline-block;
}
.img-rounded {
  border-radius: 4px;
}
.mdi-set,
.mdi:before {
  line-height: initial;
}
h1,
h2,
h3,
h4,
h5,
h6 {
  color: #F0A500;
  font-weight: 400;
}
h1 {
  line-height: 40px;
  font-size: 36px;
}
h2 {
  line-height: 36px;
  font-size: 24px;
}
h3 {
  line-height: 30px;
  font-size: 21px;
}
h4 {
  line-height: 22px;
  font-size: 18px;
}
h5 {
  line-height: 18px;
  font-size: 16px;
  font-weight: 400;
}
h6 {
  line-height: 16px;
  font-size: 14px;
  font-weight: 400;
}
.display-5 {
  font-size: 3rem;
}
.display-6 {
  font-size: 36px;
}
.box {
  border-radius: 4px;
  padding: 10px;
}
.preloader {
  width: 100%;
  height: 100%;
  top: 0;
  position: fixed;
  z-index: 99999;
  background: #fff;
}
.preloader .cssload-speeding-wheel {
  position: absolute;
  top: calc(46.5%);
  left: calc(46.5%);
}
#main-wrapper {
  width: 100%;
}
.bg-white .card {
  box-shadow: none;
}
.box-shadow {
  box-shadow: 0 5px 20px rgba(0, 0, 0, 0.05) !important;
}
.dropzone {
  border: 1px dashed #b1b8bb;
}
.boxed #main-wrapper {
  width: 100%;
  max-width: 1300px;
  margin: 0 auto;
  -webkit-box-shadow: 0 0 60px rgba(0, 0, 0, 0.1);
  box-shadow: 0 0 60px rgba(0, 0, 0, 0.1);
}

.page-wrapper {
  background: #fafafa;
  padding-bottom: 60px;
}
.container-fluid {
  padding: 0 30px 25px;
}
@media (min-width: 1024px) {
  .page-wrapper {
    margin-left: 240px;
  }
  .footer {
    left: 240px;
  }
}





/*    width
-----------------------------*/
.w10pr {
  width: 10%;
}
.w12pr {
  width: 12%;
}
.p-28 {
  padding: 28px;
}
.p-10 {
  padding: 10px;
}

.header {
  position: relative;
  z-index: 50;
  background: #fff;
  box-shadow: 1px 0 5px rgba(0, 0, 0, 0.1);
}
.header .top-navbar {
  min-height: 50px;
  padding: 0 15px 0 0;
}
.header .top-navbar .dropdown-toggle:after {
  display: none;
}
.header .top-navbar .navbar-header {
  line-height: 45px;
  text-align: center;
  background: #fff;
}
.header .top-navbar .navbar-header .navbar-brand {
  margin-right: 0;
  padding-bottom: 0;
  padding-top: 0;
}
.header .top-navbar .navbar-header .navbar-brand .light-logo {
  display: none;
}
.header .top-navbar .navbar-header .navbar-brand b {
  line-height: 60px;
  display: inline-block;
}
.header .top-navbar .navbar-nav > .nav-item > .nav-link {
  padding-left: 0.75rem;
  padding-right: 0.75rem;
  font-size: 15px;
  line-height: 40px;
}
.header .top-navbar .navbar-nav > .nav-item.show {
  background: rgba(0, 0, 0, 0.05);
}

.header .dropdown-menu {
  box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
  -webkit-box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
  -moz-box-shadow: 0 3px 12px rgba(0, 0, 0, 0.05);
  border-color: rgba(120, 130, 140, 0.13);
}
.header .dropdown-menu .dropdown-item {
  padding: 7px 1.5rem;
}

.mega-dropdown {
  position: static;
  width: 100%;
}
.mega-dropdown .dropdown-menu {
  width: 100%;
  padding: 30px;
  margin-top: 0;
}
.mega-dropdown ul {
  padding: 0;
}
.mega-dropdown ul li {
  list-style: none;
}
.mega-dropdown .carousel-item .container {
  padding: 0;
}
.mega-dropdown .nav-accordion .card {
  margin-bottom: 1px;
}
.mega-dropdown .nav-accordion .card-header {
  background: #ffffff;
}
.mega-dropdown .nav-accordion .card-header h5 {
  margin: 0;
}
.mega-dropdown .nav-accordion .card-header h5 a {
  text-decoration: none;
  color: #67757c;
}
ul.list-style-none {
  margin: 0;
  padding: 0;
}
ul.list-style-none li {
  list-style: none;
}
ul.list-style-none li a {
  color: #67757c;
  padding: 8px 0;
  display: block;
  text-decoration: none;
}
ul.list-style-none li a:hover {
  color: #1976d2;
}
.dropdown-item {
  padding: 8px 1rem;
  color: #67757c;
}
.custom-select {
  background: url("../../assets/images/custom-select.png") right 0.75rem center no-repeat;
}
textarea {
  resize: none;
}

@media (min-width: 768px) {
  .navbar-header {
    width: 240px;
    -webkit-flex-shrink: 0;
    -ms-flex-negative: 0;
    flex-shrink: 0;
  }
  .navbar-header .navbar-brand {
    padding-top: 0;
  }
  .page-titles .breadcrumb {
    float: right;
  }
  .card-group .card:first-child {
    border-right: 1px solid rgba(0, 0, 0, 0.03);
  }
  .card-group .card:not(:first-child):not(:last-child) {
    border-right: 1px solid rgba(0, 0, 0, 0.03);
  }
  .material-icon-list-demo .icons div {
    width: 33%;
    padding: 15px;
    display: inline-block;
    line-height: 40px;
  }
  .mini-sidebar .page-wrapper {
    margin-left: 60px;
  }
  .mini-sidebar .footer {
    left: 60px;
  }
  .flex-wrap {
    -ms-flex-wrap: nowrap !important;
    flex-wrap: nowrap !important;
    -webkit-flex-wrap: nowrap !important;
  }
}
@media (max-width: 767px) {
  .header {
    position: fixed;
    width: 100%;
  }
  .header .top-navbar {
    padding-right: 15px;
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -webkit-flex-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
    -webkit-flex-wrap: nowrap;
    -ms-flex-wrap: nowrap;
    flex-wrap: nowrap;
    -webkit-align-items: center;
  }
  .header .top-navbar .navbar-collapse {
    display: -webkit-box;
    display: -webkit-flex;
    display: -ms-flexbox;
    display: flex;
    width: 100%;
  }
  .header .top-navbar .navbar-nav {
    -webkit-box-orient: horizontal;
    -webkit-box-direction: normal;
    -webkit-flex-direction: row;
    -ms-flex-direction: row;
    flex-direction: row;
  }
  .header .top-navbar .navbar-nav > .nav-item.show {
    position: static;
  }
  .header .top-navbar .navbar-nav > .nav-item.show .dropdown-menu {
    width: 100%;
    margin-top: 0;
  }
  .header .top-navbar .navbar-nav > .nav-item > .nav-link {
    padding-left: 0.50rem;
    padding-right: 0.50rem;
  }
  .header .top-navbar .navbar-nav .dropdown-menu {
    position: absolute;
  }
  .mega-dropdown .dropdown-menu {
    height: 480px;
    overflow: auto;
  }
  .mini-sidebar .page-wrapper {
    margin-left: 0;
    padding-top: 60px;
  }
}
.left-sidebar {
  position: absolute;
  width: 240px;
  height: 100%;
  top: 0;
  z-index: 20;
  padding-top: 60px;
  background: #fff;
  -webkit-box-shadow: 1px 0 20px rgba(0, 0, 0, 0.08);
  box-shadow: 1px 0 20px rgba(0, 0, 0, 0.08);
}
.fix-sidebar .left-sidebar {
  position: fixed;
}
.sidebar-footer {
  position: fixed;
  z-index: 10;
  bottom: 0;
  left: 0;
  -webkit-transition: 0.2s ease-out;
  -o-transition: 0.2s ease-out;
  transition: 0.2s ease-out;
  width: 240px;
  background: #fff;
  border-top: 1px solid rgba(120, 130, 140, 0.13);
}
.sidebar-footer a {
  padding: 15px;
  width: 33.333337%;
  float: left;
  text-align: center;
  font-size: 18px;
}
.scroll-sidebar {
  padding-bottom: 60px;
}
.collapse.in {
  display: block;
}
.sidebar-nav {
  background: #fff;
  padding: 0;
}
.sidebar-nav ul {
  margin: 0;
  padding: 0;
}
.sidebar-nav ul li {
  list-style: none;
}
.sidebar-nav ul li a {
  color: #607d8b;
  padding: 7px 35px 7px 15px;
  display: block;
  font-size: 14px;
  white-space: nowrap;
}
.sidebar-nav ul li a:hover {
  color: #1976d2;
}
.sidebar-nav ul li a:hover i {
  color: #1976d2;
}
.sidebar-nav ul li a.active {
  color: #1976d2;
  font-weight: 500;
}
.sidebar-nav ul li a.active i {
  color: #1976d2;
}
.sidebar-nav ul li ul {
  padding-left: 28px;
}
.sidebar-nav ul li ul li a {
  padding: 7px 35px 7px 15px;
}
.sidebar-nav ul li ul ul {
  padding-left: 15px;
}
.sidebar-nav ul li.nav-label {
  font-size: 12px;
  margin-bottom: 0;
  padding: 14px 14px 14px 20px;
  color: #607d8b;
  font-weight: 600;
  text-transform: uppercase;
}
.sidebar-nav ul li.nav-devider {
  height: 1px;
  background: rgba(120, 130, 140, 0.13);
  display: block;
}
.sidebar-nav > ul > li {
  margin-bottom: 5px;
}
.sidebar-nav > ul > li > a {
  border-left: 3px solid transparent;
}
.sidebar-nav > ul > li > a i {
  width: 27px;
  font-size: 16px;
  display: inline-block;
  vertical-align: middle;
  color: #99abb4;
}
.sidebar-nav > ul > li > a .label {
  position: absolute;
  right: 35px;
  top: 8px;
}
.sidebar-nav > ul > li > a.active {
  font-weight: 400;
  background: #fff;
  color: #1976d2;
}
.sidebar-nav > ul > li.active > a {
  color: #1976d2;
  font-weight: 500;
  border-left: 3px solid #fff;
}
.sidebar-nav > ul > li.active > a i {
  color: #1976d2;
}
.sidebar-nav .has-arrow {
  position: relative;
}
.sidebar-nav .has-arrow:after {
  position: absolute;
  content: '';
  width: 7px;
  height: 7px;
  border-width: 1px 0 0 1px;
  border-style: solid;
  border-color: #607d8b;
  right: 1em;
  -webkit-transform: rotate(135deg) translate(0, -50%);
  -ms-transform: rotate(135deg) translate(0, -50%);
  -o-transform: rotate(135deg) translate(0, -50%);
  transform: rotate(135deg) translate(0, -50%);
  -webkit-transform-origin: top;
  -ms-transform-origin: top;
  -o-transform-origin: top;
  transform-origin: top;
  top: 47%;
  -webkit-transition: all 0.3s ease-out;
  -o-transition: all 0.3s ease-out;
  transition: all 0.3s ease-out;
}
.sidebar-nav .active > .has-arrow:after {
  -webkit-transform: rotate(-135deg) translate(0, -50%);
  -ms-transform: rotate(-135deg) translate(0, -50%);
  -o-transform: rotate(-135deg) translate(0, -50%);
  top: 45%;
  width: 7px;
  transform: rotate(-135deg) translate(0, -50%);
}
.sidebar-nav .has-arrow[aria-expanded=true]:after {
  -webkit-transform: rotate(-135deg) translate(0, -50%);
  -ms-transform: rotate(-135deg) translate(0, -50%);
  -o-transform: rotate(-135deg) translate(0, -50%);
  top: 45%;
  width: 7px;
  transform: rotate(-135deg) translate(0, -50%);
}
.sidebar-nav li > .has-arrow.active:after {
  -webkit-transform: rotate(-135deg) translate(0, -50%);
  -ms-transform: rotate(-135deg) translate(0, -50%);
  -o-transform: rotate(-135deg) translate(0, -50%);
  top: 45%;
  width: 7px;
  transform: rotate(-135deg) translate(0, -50%);
}
@media (min-width: 768px) {
  .mini-sidebar .sidebar-nav {
    background: transparent;
  }
  .mini-sidebar .sidebar-nav #sidebarnav li {
    position: relative;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li > ul {
    position: absolute;
    left: 60px;
    top: 38px;
    width: 200px;
    z-index: 1001;
    background: #f2f6f8;
    display: none;
    padding-left: 1px;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li:hover > ul {
    height: auto !important;
    overflow: auto;
    display: block;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li:hover > ul.collapse {
    display: block;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li:hover > a {
    width: 260px;
    background: #f2f6f8;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li:hover > a .hide-menu {
    display: inline;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li:hover > a .label {
    display: none;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li > a.has-arrow:after {
    display: none;
  }
  .mini-sidebar .sidebar-nav #sidebarnav > li > a {
    padding: 9px 18px;
    width: 50px;
  }
  .mini-sidebar .user-profile {
    padding-bottom: 15px;
    width: 60px;
    margin-bottom: 7px;
  }
  .mini-sidebar .user-profile .profile-img {
    width: 50px;
    padding: 15px 0 0;
    margin: 0 0 0 6px;
  }
  .mini-sidebar .user-profile .profile-img .setpos {
    top: -35px;
  }
  .mini-sidebar .user-profile .profile-img:before {
    top: 15px;
  }
  .mini-sidebar .user-profile .profile-text {
    display: none;
  }
  .mini-sidebar .left-sidebar {
    width: 60px;
  }
  .mini-sidebar .scroll-sidebar {
    padding-bottom: 0;
    position: absolute;
    overflow-x: hidden !important;
  }
  .mini-sidebar .hide-menu {
    display: none;
  }
  .mini-sidebar .nav-label {
    display: none;
  }
  .mini-sidebar .sidebar-footer {
    display: none;
  }
  .mini-sidebar > .label {
    display: none;
  }
  .mini-sidebar .nav-devider {
    width: 60px;
  }
  .mini-sidebar.fix-sidebar .left-sidebar {
    position: fixed;
  }
}
@media (max-width: 767px) {
  .mini-sidebar .left-sidebar {
    position: fixed;
    left: -240px;
  }
  .mini-sidebar .sidebar-footer {
    left: -240px;
  }
  .mini-sidebar.show-sidebar .left-sidebar {
    left: 0;
  }
  .mini-sidebar.show-sidebar .sidebar-footer {
    left: 0;
  }
}

.breadcrumb {
  margin-bottom: 0;
}
.page-titles {
  background: #ffffff;
  margin: 0 0 30px;
  padding: 15px 10px;
  position: relative;
  z-index: 10;
  -webkit-box-shadow: 1px 0 5px rgba(0, 0, 0, 0.1);
  box-shadow: 1px 0 5px rgba(0, 0, 0, 0.1);
}
.page-titles h3 {
  margin-bottom: 0;
  margin-top: 0;
}
.page-titles .breadcrumb {
  padding: 0;
  background: transparent;
  font-size: 14px;
}
.page-titles .breadcrumb li {
  margin-top: 0;
  margin-bottom: 0;
}
.page-titles .breadcrumb .breadcrumb-item + .breadcrumb-item:before {
  content: "\e649";
  font-family: themify;
  color: #a6b7bf;
  font-size: 11px;
}
.page-titles .breadcrumb .breadcrumb-item.active {
  color: #99abb4;
}

.pager li > a {
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  color: #263238;
}
.pager li > span {
  -moz-border-radius: 4px;
  -webkit-border-radius: 4px;
  border-radius: 4px;
  color: #263238;
}
.footer {
  background: #ffffff none repeat scroll 0 0;
  border-top: 1px solid rgba(120, 130, 140, 0.13);
  color: #67757c;
  left: 0;
  padding: 17px 15px;
  position: absolute;
  right: 0;
}
.footer {
  left: 240px;
}

/*    Font Variable
----------------------*/
/*    Color Variable
-----------------------*/
/*    Solid Color
------------------*/
/*    Brand color
----------------------*/


.card {
  background: #ffffff none repeat scroll 0 0;
  margin: 10px 0;
  padding: 20px;
}
.card-subtitle {
  font-size: 900px;
  margin: 10px 0;
}
.card-title {
  font-weight: 500px;
  font-size: 900px;

}




/**
 * FOOTER
 */
footer {
  display: block;
  margin: 1rem 0;
  padding: 1rem 0 0;
}
footer p {
  font-size: 12px;
}

/*    Basic form
----------------------*/
label {
  font-weight: 400;
  margin-bottom: 10px;
}

.dropdown-menu li {
  font-size: 14px;
  padding: 5px 15px;
}

.inbox-leftbar {
  width: 240px;
  float: left;
  padding: 0 20px 20px 10px;
}

@media (max-width: 648px) {
  .inbox-leftbar {
    width: 100%;
  }
  .inbox-rightbar {
    margin-left: 0;
  }
}

@import url('components.css');

.dashboard .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(27rem, 1fr));
   gap:1.5rem;
   align-items: flex-start;
}

.dashboard .box-container .box{
   padding:1.5rem;
   text-align: center;
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: var(--white);
   border-radius: .5rem;
}

.dashboard .box-container .box h3{
   font-size: 3.5rem;
   color:var(--black);
}

.dashboard .box-container .box p{
   font-size: 2rem;
   background-color: var(--light-bg);
   color:var(--light-color);
   padding:1.5rem;
   margin:1rem 0;
   border:var(--border);
   border-radius: .5rem;
   color:var(--black);
}

.add-dishes form{
   max-width: 70rem;
   padding:2rem;
   margin:0 auto;
   text-align: center;
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: var(--white);
   border-radius: .5rem;
}

.add-dishes form .flex{
   display: flex;
   justify-content: space-between;
   flex-wrap: wrap;
}

.add-dishes form .flex .inputBox{
   width: 49%;
}

.add-dishes form .box{
   width: 100%;
   margin:1rem 0;
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   border-radius: .5rem;
   background-color: var(--light-bg);
   border:var(--border);
}

.add-dishes form textarea{
   height: 20rem;
   resize: none;
}

.show-dishes{
   padding-top: 0;
}

.show-dishes .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 33rem);
   gap:1.5rem;
   align-items: flex-start;
   justify-content:center;
}

.show-dishes .box-container .box{
   text-align: center;
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: var(--white);
   border-radius: .5rem;
   padding:2rem;
   position: relative;
}

.show-dishes .box-container .box .price{
   position: absolute;
   top:1rem; left:1rem;
   padding:1rem;
   font-size: 2rem;
   color:var(--white);
   background-color: var(--red);
   border-radius: .5rem;
}

.show-dishes .box-container .box img{
   width: 100%;
   margin-bottom: 1rem;
}

.show-dishes .box-container .box .name{
   margin:.5rem 0;
   font-size: 2rem;
   color:var(--black);
}

.show-dishes .box-container .box .cat{
   font-size: 1.8rem;
   color:var(--orange);
}

.show-dishes .box-container .box .details{
   padding-top: 1rem;
   font-size: 1.5rem;
   line-height: 1.5;
   color:var(--light-color);
}

.update-dish form{
   max-width: 50rem;
   padding:2rem;
   margin:0 auto;
   text-align: center;
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: var(--white);
   border-radius: .5rem;
}

.update-product form img{
   height: 25rem;
   object-fit: cover;
   margin-bottom: 1rem;
}

.update-product form .box{
   width: 100%;
   border:var(--border);
   background-color: var(--white);
   border-radius: .5rem;
   background-color: var(--light-bg);
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   margin:1rem 0;
}

.placed-orders .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 33rem);
   gap:1.5rem;
   align-items: flex-start;
   justify-content:center;
}

.placed-orders .box-container .box{
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: var(--white);
   border-radius: .5rem;
   padding:2rem;
}

.placed-orders .box-container .box p{
   margin-bottom: 1rem;
   line-height: 1.5;
   font-size: 2rem;
   color:var(--light-color);
}

.placed-orders .box-container .box p span{
   color:var(--orange);
}

.placed-orders .box-container .box .drop-down{
   width: 100%;
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   border:var(--border);
   border-radius: .5rem;
   background-color: var(--light-bg);
   margin-bottom: .5rem;
}

.user-accounts .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 33rem);
   gap:1.5rem;
   align-items: flex-start;
   justify-content:center;
}

.user-accounts .box-container .box{
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: var(--white);
   border-radius: .5rem;
   padding:2rem;
   text-align: center;
}

.user-accounts .box-container .box img{
   height: 15rem;
   width: 15rem;
   border-radius: 50%;
   object-fit: cover;
   margin-bottom: 1rem;
}

.user-accounts .box-container .box p{
   line-height: 1.5;
   padding:.5rem 0;
   font-size: 2rem;
   color:var(--light-color);
}

.user-accounts .box-container .box p span{
   color:var(--orange);
}

.messages .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, 33rem);
   gap:1.5rem;
   align-items: flex-start;
   justify-content:center;
}

.messages .box-container .box{
   border:var(--border);
   box-shadow: var(--box-shadow);
   background-color: var(--white);
   border-radius: .5rem;
   padding:2rem;
}

.messages .box-container .box p{
   line-height: 1.5;
   padding:.5rem 0;
   font-size: 2rem;
   color:var(--light-color);
}

.messages .box-container .box p span{
   color:var(--orange);
}












@media (max-width:768px){

   .add-dishes form .flex .inputBox{
      width: 100%;
   }

}



@media (max-width:450px){

   .show-dishes .box-container{
      grid-template-columns: 1fr;
   }

   .update-product form img{
      height: auto;
      width: 100%;
   }

   .placed-orders .box-container{
      grid-template-columns: 1fr;
   }

   .user-accounts .box-container{
      grid-template-columns: 1fr;
   }

   .messages .box-container{
      grid-template-columns: 1fr;
   }

}

@import url('https://fonts.googleapis.com/css2?family=Rubik:wght@300;400;500;600&display=swap');

:root{
   --orange:#f39c12;
   --red:#e74c3c;
   --black:#333;
   --light-color:#666;
   --white:#fff;
   --light-bg:#f6f6f6;
   --border:.2rem solid var(--black);
   --box-shadow:0 .5rem 1rem rgba(0,0,0,.1);
}

*{
   font-family: 'Rubik', sans-serif;
   margin:0; padding:0;
   box-sizing: border-box;
   outline: none; border:none;
   text-decoration: none;
   color:var(--black);
}

*::selection{
   background-color: var(--orange);
   color:var(--white);
}

*::-webkit-scrollbar{
   height: .5rem;
   width: 1rem;
}

*::-webkit-scrollbar-track{
   background-color: transparent;
}

*::-webkit-scrollbar-thumb{
   background-color: var(--orange);
}

body{
   background-color: var(--light-bg);
   /* padding-bottom: 6.5rem; */
}

html{
   font-size: 62.5%;
   overflow-x: hidden;
   scroll-behavior: smooth;
   scroll-padding-top: 6.5rem;
}

section{
   padding:3rem 2rem;
   max-width: 1200px;
   margin:0 auto;
}

.disabled{
   user-select: none;
   pointer-events: none;
   opacity: .5;
}

.btn,
.delete-btn,
.option-btn{
   display: block;
   width: 100%;
   margin-top: 1rem;
   border-radius: .5rem;
   color:var(--white);
   font-size: 2rem;
   padding:1.3rem 3rem;
   text-transform: capitalize;
   cursor: pointer;
   text-align: center;
}

.btn{
   background-color: var(--orange);
}

.delete-btn{
   background-color: var(--red);
}

.option-btn{
   background-color: var(--orange);
}

.btn:hover,
.delete-btn:hover,
.option-btn:hover{
   background-color: var(--black);
}

.flex-btn{
   display: flex;
   flex-wrap: wrap;
   gap:1rem;
}

.flex-btn > *{
   flex:1;
}

.title{
   text-align: center;
   margin-bottom: 2rem;
   text-transform: uppercase;
   color:var(--black);
   font-size: 3.5rem;
}

.message{
   position: sticky;
   top:0;
   max-width: 1200px;
   margin:0 auto;
   background-color: var(--light-bg);
   padding:2rem;
   display: flex;
   align-items: center;
   justify-content: space-between;
   gap:1.5rem;
   z-index: 10000;
}

.message span{
   font-size: 2rem;
   color:var(--black);
}

.message i{
   font-size: 2.5rem;
   cursor: pointer;
   color:var(--red);
}

.message i:hover{
   color:var(--black);
}

.empty{
   padding:1.5rem;
   background: var(--white);
   color:var(--red);
   border-radius: .5rem;
   border:var(--border);
   font-size: 2rem;
   text-align: center;
   box-shadow: var(--box-shadow);
   text-transform: capitalize;
}

@keyframes fadeIn {
   0%{
      transform: translateY(1rem);
   }
}

.form-container{
   min-height: 100vh;
   display: flex;
   align-items: center;
   justify-content: center;
}

.form-container form{
   width: 50rem;
   background-color: var(--white);
   border-radius: .5rem;
   box-shadow: var(--box-shadow);
   border:var(--border);
   text-align: center;
   padding:2rem;
}

.form-container form h3{
   font-size: 3rem;
   color:var(--black);
   margin-bottom: 1rem;
   text-transform: uppercase;
}

.form-container form .box{
   width: 100%;
   margin:1rem 0;
   border-radius: .5rem;
   border:var(--border);
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   background-color: var(--light-bg);
}

.form-container form p{
   margin-top: 2rem;
   font-size: 2.2rem;
   color:var(--light-color);
}

.form-container form p a{
   color:var(--orange);
}

.form-container form p a:hover{
   text-decoration: underline;
}

.header{
   background: var(--white);
   position: sticky;
   top:0; left:0; right:0;
   z-index: 1000;
   box-shadow: var(--box-shadow);
}

.header .flex{
   display: flex;
   align-items: center;
   justify-content: space-between;
   padding:2rem;
   margin: 0 auto;
   max-width: 1200px;
   position: relative;
}

.header .flex .logo{
   font-size: 2.5rem;
   color:var(--black);
}

.header .flex .logo span{
   color:var(--orange);
}

.header .flex .navbar a{
   margin:0 1rem;
   font-size: 2rem;
   color:var(--light-color);
}

.header .flex .navbar a:hover{
   text-decoration: underline;
   color:var(--orange);
}

.header .flex .icons > *{
   font-size: 2.5rem;
   color:var(--light-color);
   cursor: pointer;
   margin-left: 1.5rem;
}

.header .flex .icons > *:hover{
   color:var(--orange);
}

.header .flex .icons a span,
.header .flex .icons a i{
   color:var(--light-color);
}

.header .flex .icons a:hover span,
.header .flex .icons a:hover i{
   color:var(--orange);
}

.header .flex .icons a span{
   font-size: 2rem;
}

#menu-btn{
   display: none;
}

.header .flex .profile{
   position: absolute;
   top:120%; right:2rem;
   box-shadow: var(--box-shadow);
   border:var(--border);
   border-radius: .5rem;
   padding:2rem;
   text-align: center;
   background-color: var(--white);
   width: 33rem;
   display: none;
   animation: fadeIn .2s linear;
}

.header .flex .profile.active{
   display: inline-block;
}

.header .flex .profile img{
   height: 15rem;
   width: 15rem;
   margin-bottom: 1rem;
   border-radius: 50%;
   object-fit: cover;
}

.header .flex .profile p{
   padding:.5rem 0;
   font-size: 2rem;
   color:var(--light-color);
}

.update-profile form{
   max-width: 70rem;
   margin: 0 auto;
   background-color: var(--white);
   box-shadow: var(--box-shadow);
   border:var(--border);
   border-radius: .5rem;
   padding:2rem;
   text-align: center;
}

.update-profile form .flex{
   display: flex;
   gap:1.5rem;
   justify-content: space-between;
}

.update-profile form img{
   height: 20rem;
   width: 20rem;
   margin-bottom: 1rem;
   border-radius: 50%;
   object-fit: cover;
}

.update-profile form .inputBox{
   text-align: left;
   width: 49%;
}

.update-profile form .inputBox span{
   display: block;
   padding-top: 1rem;
   font-size: 1.8rem;
   color:var(--light-color);
}

.update-profile form .inputBox .box{
   width: 100%;
   padding:1.2rem 1.4rem;
   font-size: 1.8rem;
   color:var(--black);
   border:var(--border);
   border-radius: .5rem;
   margin:1rem 0;
   background-color: var(--light-bg);
}













.footer{
   background-color: var(--white);
}

.footer .box-container{
   display: grid;
   grid-template-columns: repeat(auto-fit, minmax(27rem, 1fr));
   gap:2.5rem;
   align-items: flex-start;
}

.footer .box-container .box h3{
   text-transform: uppercase;
   color:var(--black);
   margin-bottom: 2rem;
   font-size: 2rem;
}

.footer .box-container .box a,
.footer .box-container .box p{
   display: block;
   padding:1.3rem 0;
   font-size: 1.6rem;
   color:var(--light-color);
}

.footer .box-container .box a i,
.footer .box-container .box p i{
   color:var(--orange);
   padding-right: 1rem;
}

.footer .box-container .box a:hover{
   text-decoration: underline;
   color:var(--orange);
}

.footer .credit{
   margin-top: 2rem;
   padding: 2rem 1.5rem;
   padding-bottom: 2.5rem;
   line-height: 1.5;
   border-top: var(--border);
   text-align: center;
   font-size: 2rem;
   color:var(--black);
}

.footer .credit span{
   color:var(--orange);
}



/* media queries  */

@media (max-width:991px){

   html{
      font-size: 55%;
   }
   
}

@media (max-width:768px){

   #menu-btn{
      display: inline-block;
   }

   .header .flex .navbar{
      border-top: var(--border);
      border-bottom: var(--border);
      background-color: var(--white);
      position: absolute;
      top:99%; left:0; right:0;
      transition: .2s linear;
      clip-path: polygon(0 0, 100% 0, 100% 0, 0 0);
   }

   .header .flex .navbar.active{
      clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
   }

   .header .flex .navbar a{
      display: block;
      margin:2rem;   
   }

   .update-profile form .flex{
      flex-wrap: wrap;
      gap:0;
   }

   .update-profile form .flex .inputBox{
      width: 100%;
   }

}

@media (max-width:450px){

   html{
      font-size: 50%;
   }

   .flex-btn{
      flex-flow: column;
      gap:0;
   }

   .title{
      font-size: 3rem;
   }
   
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
   <?php foreach ( $fetch_dishes as $fetch_dish): ?>
   <form action="" class="box" method="POST">
      <div class="price">$<span><?= $fetch_dish['price']; ?></span></div>
      <a href="view_page.php?d_id=<?= $fetch_dish['d_id']; ?>" class="fas fa-eye"></a>
      <img src="uploaded_img/<?= $fetch_dish['image']; ?>" alt="">
      <div class="name"><?= $fetch_dish['title']; ?></div>
      <div class="category"><?= $fetch_dish['name']; ?></div>
      <div class="name"><?= $fetch_dish['title']; ?></div>
      <input type="hidden" name="d_id" value="<?= $fetch_dish['d_id']; ?>">
      <input type="hidden" name="d_name" value="<?= $fetch_dish['title']; ?>">
      <input type="hidden" name="cid" value="<?= $fetch_dish['cate_id']; ?>">
      <input type="hidden" name="d_price" value="<?= $fetch_dish['price']; ?>">
      <input type="hidden" name="d_image" value="<?= $fetch_dish['image']; ?>">
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
         $previous_2 = $current_page -2;
         $next_2 = $current_page +2;

if($i >= $previous_2 && $i <= $next_2 ) {
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