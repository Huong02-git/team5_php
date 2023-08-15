<?php
@include 'config.php';

session_start();

$admin_id = $_SESSION['admin_id'];

if(!isset($admin_id)){
   header('location:login.php');
};




define("ROW_PER_PAGE",5);
require_once('config.php');
?>
<html>
<head>

<meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">

   <meta name="description" content="">
    <meta name="author" content="">
    <!-- Bootstrap Core CSS -->
    <link href="css/lib/bootstrap/bootstrap.min.css" rel="stylesheet">
   <title>users</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->

</head>
<body>
<?php include 'admin_header.php'; ?>
<?php	
$search_keyword = '';
	if(!empty($_POST['search']['keyword'])) {
		$search_keyword = $_POST['search']['keyword'];
	}
	$select_products= 'SELECT * FROM dishes inner join categories on dishes.cate_id = categories.cates_id WHERE title LIKE :keyword ORDER BY dishes.id DESC ';
	
	/* Pagination Code starts */
	$per_page_html = '';
	$page = 1;
	$start=0;
	if(!empty($_POST["page"])) {
		$page = $_POST["page"];
		$start=($page-1) * ROW_PER_PAGE;
	}
	$limit=" limit " . $start . "," . ROW_PER_PAGE;
	$pagination_statement = $conn->prepare($select_products);
	$pagination_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
	$pagination_statement->execute();

	$row_count = $pagination_statement->rowCount();
	if(!empty($row_count)){
		$per_page_html .= "<div style='text-align:center;margin:20px 0px;'>";
		$page_count=ceil($row_count/ROW_PER_PAGE);
		if($page_count>1) {
			for($i=1;$i<=$page_count;$i++){
				if($i==$page){
					$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page current" />';
				} else {
					$per_page_html .= '<input type="submit" name="page" value="' . $i . '" class="btn-page" />';
				}
			}
		}
		$per_page_html .= "</div>";
	}
	
	$query = $select_products.$limit;
	$pdo_statement = $conn->prepare($query);
	$pdo_statement->bindValue(':keyword', '%' . $search_keyword . '%', PDO::PARAM_STR);
	$pdo_statement->execute();
	$result = $pdo_statement->fetchAll();
?>
 <b><h2 class="card-title" >ALL DISHES</h2></b>
<form name='frmSearch' action='' method='post'>
<div class="card">
    <div class="card-body">
	<div><input type='text' name='search[keyword]' placeholder="Search Here..."  value="<?php echo $search_keyword; ?>" id='keyword' maxlength='100'>
<input type="submit" name="search_btn" value="search" class="btn">
</div>
	<div class="table-responsive m-t-40">
<table class="display nowrap table table-hover table-striped table-bordered" cellspacing="1" width="100% " >
  <thead>
	<tr>
	  <th class='table-header' style="color:var(--orange)" width='7%'>Title</th>
    <th class='table-header' style="color:var(--orange)" width='7%'>Caterory</th>
	  <th class='table-header' style="color:var(--orange)"  width='5%'>Price</th>
      <th class='table-header' style="color:var(--orange)"  width='10%'>Description</th>
      <th class='table-header' style="color:var(--orange)"  width='5%'>Image</th>
	</tr>
  </thead>
  <tbody id='table-body'>
	<?php
	if(!empty($result)) { 
		foreach($result as $row) {
	?>
	  <tr class='table-row'>
		<td><?php echo $row['title']; ?></td>
    <td><?php echo $row['name']; ?></td>
		<td><?php echo $row['price']; ?></td>
        <td><?php echo $row['details']; ?></td>
		<td><center><img src="uploaded_img/<?= $row['image']; ?>" alt="" style="max-height:200px;max-width:150px;"/></center></td>
	  </tr>
    <?php
		}
	}
	?>
  </tbody>
</table>
</div></div>
<?php echo $per_page_html; ?>
</form>
<!-- footer -->
<footer class="footer"> 
 <p class="credit"> @ <?= date('Y'); ?> Website made by <span>VANTH</span>🧡🧡🧡</p>
</footer>
            <!-- End footer -->
</body>
</html>