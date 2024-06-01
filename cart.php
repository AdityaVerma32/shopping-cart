<?php include 'header.php';

require_once 'include/global.inc.php';
session_start();

$errors = array();
$data = array();
$grand_total = 0;
$input_values = array();

// this function is used to update the number of products
if (!empty($_POST['Update'])) {

    try {
        $val = $_POST['update_value'];
        $id = $_POST['Update'];
        $temp_arr = explode('#', $id);
        $id = end($temp_arr);

        $query_to_check_quantity =
            "SELECT product_quantity
		FROM products
		WHERE product_id = " . $id . "";

        $result = mysqli_query($conn, $query_to_check_quantity);
        $quantity = mysqli_fetch_all($result);
        $quantity = $quantity[0][0];

        if ($quantity > $val) {
            if (!empty($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id] = array($val, $_SESSION['cart'][$id][1], $_SESSION['cart'][$id][2], $_SESSION['cart'][$id][3], $_SESSION['cart'][$id][4], $_SESSION['cart'][$id][5]);
            } else {
                $_SESSION['cart_error'] = "Error While Updating Cart";
                header("Location: http://localhost/shopping-cart/error-msg");
            }
        } else {
            $_SESSION['cart_error'] = "Not Enough Qauntity Available";
            header("Location: http://localhost/shopping-cart/error-msg");
        }
    } catch (Exception $e) {
        $_SESSION['cart_error'] = $e->getMessage();
        header("Location: http://localhost/shopping-cart/error-msg");
    }
}

// this function is used to remove the product from cart
if (!empty($_POST['Remove'])) {
    $id = $_POST['Remove'];
    $temp_arr = explode('#', $id);
    $id = end($temp_arr);

    $Key_remove = -1;

    foreach ($data as $k => $v) {
        if (in_array($id, $v)) {
            $Key_remove = $k;
        }
    }

    // deleting from input array
    unset($input_values[$Key_remove]);

    // deleting from data array
    unset($data[$Key_remove]);

    // removing from session(cart)
    if (!empty($_SESSION['cart'][$id])) {
        unset($_SESSION['cart'][$id]);
    } else {
        $errors[0] = "Error While Updating Cart";
    }

}

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $key => $value) {
        $data[] = $value;
        $input_values[$key] = $value[0];
    }

} else {
    $errors[3] = "Your cart is empty";
}

?>
<div class="container">
	<div class="cart">
		<h2 class="page-title">Cart</h2>
		<div class="table-responsive">

		<?php if (!empty($errors[0])) {
    echo "<div style='margin: auto;
            width: 50%;
            border: 3px solid red;
            padding: 10px;'><h1 style='text-align:center'>" . $errors[0] . "</h1></div><br><br>";
}
?>
			<?php if (!empty($errors[1])) {
    echo "<div style='margin: auto;
            width: 50%;
            border: 3px solid red;
            padding: 10px;'><h1 style='text-align:center'>" . $errors[1] . "</h1></div><br><br>";
}
?>
			<?php if (!empty($errors[2])) {
    echo "<div style='margin: auto;
            width: 50%;
            border: 3px solid red;
            padding: 10px;'><h1 style='text-align:center'>" . $errors[2] . "</h1></div><br><br>";
}
?>
	<?php if (!empty($errors[3])) {
    echo "<div style='margin: auto;
            width: 50%;
            border: 3px solid red;
            padding: 10px;'><h1 style='text-align:center'>" . $errors[3] . "</h1></div><br><br>";
}
?>
			<table>
				<thead>
					<tr>
						<th>Product Name</th>
						<th>Unit Price</th>
						<th>Quantity</th>
						<th>Item Total</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 0;while ($i < count($data)) {?>
					<tr>
						<td><?php echo $data[$i][2] ?></td>
						<td>Rs. <?php echo number_format($data[$i][4], 0, '.', ',') ?></td>
						<td>
							<form method="post" >
								<div class="form-group">
									<input type="text" class="form-control" name="update_value" value="<?php echo $input_values[$data[$i][1]] ?>"/>
									<button type="submit" class="btn btn-warning" name="Update" value="<?php echo "update#" . $data[$i][1] ?>">Update</button>
								</div>
							</form>
						</td>
						<td>Rs. <?php $grand_total += $data[$i][0] * $data[$i][4];
    echo number_format(($data[$i][0] * $data[$i][4]), 0, '.', ',')?></td>
						<td>
                            <form method="post">
                                <button type="submit"  class="btn btn-danger" name="Remove" value="<?php echo 'remove#' . $data[$i][1] ?>">Remove</button>
                            </form>
					    </td>
					</tr>
					<?php $i++;}?>
					<tr>
						<td></td>
						<td></td>
						<td ><b>Total</b></td>
						<td><b>Rs. <?php echo number_format($grand_total) ?></b></td>
						<td></td>
					</tr>
				</tbody>
			</table>
			<div class="cart-footer">
				<a href="index.php" class="btn btn-blue">Continue Shopping</a>
                <?php if (empty($errors[3])) {?>
                    <a href="checkout.php" class="btn btn-green">Check Out</a>
                <?php }?>
			</div>
		</div>
	</div>
</div>
<?php include 'footer.php';?>