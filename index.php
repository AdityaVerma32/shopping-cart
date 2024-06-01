<?php include 'header.php';

require_once 'include/global.inc.php';

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

 */


$query_to_select_all_products =
"SELECT
	`product_id`,
	`product_name`,
	`product_description`,
	`product_price`,
	`product_image`,
	`product_quantity`
	FROM
	`products`
	WHERE product_date_available < '" . date('Y-m-d', time()) . "'
	AND product_status = 'active'";
$result = mysqli_query($conn, $query_to_select_all_products);
$products = mysqli_fetch_all($result);

?>
<div class="container">
	<div class="product">
		<h2 class="page-title">Products</h2>
		<div class="row">
			<?php $i = 0;while ($i < count($products)) {?>
			<div class="col-lg-4 col-md-4 col-sm-4">
				<div class="product-item">
					<img src="<?php echo 'images/new/' . $products[$i][4] ?>" class="img-responsive"/>
					<h4><?php echo $products[$i][1] ?></h4>
					<h5 class="price">Rs. <?php echo number_format($products[$i][3], 0, '.', ',') ?></h5>
					<p class="<?php echo $products[$i][5] > 0 ? "stock" : "out-stock" ?>"><?php echo $products[$i][5] > 0 ? "In Stock" : "Out Of Stock" ?></p>
					<p><a href="<?php echo 'product-detail.php?product_id=' . $products[$i][0] ?>" class="btn btn-blue">Quick View</a></p>
				</div>
			</div>
			<?php $i++;}
;?>
		</div>
	</div>
</div>
<?php include 'footer.php';?>