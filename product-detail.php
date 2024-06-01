<?php include 'header.php';

include 'include/global.inc.php';

$errors = array();

if(!empty($_GET['product_id'])) {

    $id = $_GET['product_id'];

    /*Raw Query

    SELECT
    `product_id`,
    `product_name`,
    `product_description`,
    `product_price`,
    `product_image`,
    `product_quantity`,
    `product_status`,
    `product_date_available`
    FROM
    `products`
    WHERE product_id = 3

     */

    $query_get_details =
        "SELECT
		`product_id`,
		`product_name`,
		`product_description`,
		`product_price`,
		`product_image`,
		`product_quantity`,
		`product_status`,
		`product_date_available`
	FROM
		`products`
	WHERE product_id = " . $id . "";

    $result = mysqli_query($conn, $query_get_details);
    $details = mysqli_fetch_all($result);

    if (empty($details)) {
        $errors[0] = "Invalid Url";
    }

}

?>
<div class="container">
	<div class="product-detail">
		<?php if (empty($errors[0])) {?>
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				<div class="product-image">
					<img src="<?php echo 'images/new/' . $details[0][4] ?>" class="img-responsive"/>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<div class="product-content">
					<h2 class="product-title"><?php echo $details[0][1] ?></h2>
					<h5 class="price">Rs. <?php echo number_format($details[0][3], 0, '.', ',') ?></h5>
					<p class="<?php echo $details[0][5] > 0 ? "stock" : "out-stock" ?>"><?php echo $details[0][5] > 0 ? "In Stock" : "Out Of Stock" ?></p>
					<p class="summary"><?php echo $details[0][2] ?></p>
					<p><a href="<?php echo "add-cart.php?product_id=" . $details[0][0] ?>" class="btn btn-blue">Add to Cart</a></p>
				</div>
			</div>

		</div>
		<?php } else {?>
			<div style='margin: auto;
            width: 50%;
            border: 3px solid red;
            padding: 10px;'><h1 style='text-align:center'>Invalid Url</h1></div><br><br>
		<?php }?>
	</div>
</div>
<?php include 'footer.php';?>