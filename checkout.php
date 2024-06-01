<?php include 'header.php';

/*

This file is used when user checkout the order products

1. If there is nothing in the cart then user will see an error message
2. other wise Users data will be inserted intop order array
3. products ordered by user will be inserted in order-product table with foreign key as order_id and product_id
4. The quantity ordered by user will be deleted from the DB

 */

session_start();

if (empty($_SESSION['cart'])) {
    header("Location:index.php");
}

require_once 'include/global.inc.php';

$first_name = "";
$last_name = "";
$email = "";
$mobile_number = "";
$address = "";
$city = "";
$zipcode = "";
$state = "";
$country = "";
$payment_method = "";

// this function is used for email vailidation purposes
function check_email($email)
{
    if (strpos($email, '@') === false || strpos($email, '.') === false) {
        return false;
    }

    return true;
}

function generateUniqueId($length = 20)
{
    $uniqueId = '';
    $characters = '0123456789';
    $charactersLength = strlen($characters);

    for ($i = 0; $i < $length; $i++) {
        $uniqueId .= $characters[rand(0, $charactersLength - 1)];
    }

    return $uniqueId;
}

// when user clicks the Complete Purchase Button below case will be executed
if (!empty($_POST['submit']) && $_POST['submit'] == 'Complete Purchase') {

    $errors = array();
    $success_msg = "";

    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $mobile_number = $_POST['mobile_number'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $zipcode = $_POST['zipcode'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $payment_method = $_POST['payment_method'];

    // Server Side Validations :

    if (empty($first_name)) {
        $errors[0] = "Please enter First name";
    } elseif (!ctype_alpha($first_name)) {
        $errors[0] = "Please enter only Alphabets";
    }

    if (empty($last_name)) {
        $errors[1] = "Please enter Last Name";
    } elseif (!ctype_alpha($last_name)) {
        $errors[1] = "Please enter only Alphabets";
    }

    if (empty($email)) {
        $errors[2] = "Please enter Email";
    }
    if (!check_email($email)) {
        $errors[2] = "Please Enter valid Email";
    }

    if (empty($mobile_number)) {
        $errors[3] = "Please enter your Mobile number";
    } elseif (strlen($mobile_number) != 10) { //strlen - it will return the length of string
        $errors[3] = "Please enter a 10-digit Mobile number";
    } elseif (!ctype_digit($mobile_number)) { //ctype_digit - it will check if the string contains all integer digit or not
        $errors[3] = "Please enter only Numbers";
    }

    // address validation
    if (empty($address)) {
        $errors[4] = "Please enter Address";
    }

    // city validation
    if (empty($city)) {
        $errors[5] = "Please enter City";
    }

    // zipcode validation
    if (empty($zipcode)) {
        $errors[6] = "Please Enter valid Zipcode";
    }

    // state validation
    if (empty($state)) {
        $errors[7] = "Please Enter State";
    }

    // country validation
    if (empty($country) || strlen($country) == 0) {
        $errors[8] = "Please Select a country";
    }

    $errors = array_filter($errors);

    if (count($errors) == 0) {

        // creating Session for storing user details

        /*

        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $email = $_POST['email'];
        $mobile_number = $_POST['mobile_number'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $zipcode = $_POST['zipcode'];
        $state = $_POST['state'];
        $country = $_POST['country'];
        $payment_method = $_POST['payment_method'];
         */


         if ($payment_method == "Paypal") {
            $_SESSION['user_details'] = array($first_name, $last_name, $email, $mobile_number, $address, $city, $zipcode, $state, $country, $order_id);
            header("Location: Paypa/");
        }

            die();

        $first_name = mysqli_real_escape_string($conn, $first_name);
        $last_name = mysqli_real_escape_string($conn, $last_name);
        $email = mysqli_real_escape_string($conn, $email);
        $mobile_number = mysqli_real_escape_string($conn, $mobile_number);
        $address = mysqli_real_escape_string($conn, $address);
        $city = mysqli_real_escape_string($conn, $city);
        $zipcode = mysqli_real_escape_string($conn, $zipcode);
        $state = mysqli_real_escape_string($conn, $state);
        $country = mysqli_real_escape_string($conn, $country);

        $order_id = generateUniqueId();
        /*

        Raw Query

        INSERT INTO `orders`(
        `first_name`,
        `last_name`,
        `email`,
        `mobile_number`,
        `address`,
        `city`,
        `zipcode`,
        `state`,
        `country`
        )
        VALUES(
        'abc',
        'abc',
        'abc@abc.com',
        '9090909090',
        'abc',
        'abc',
        '123455',
        'state',
        'country'
        )

         */

        $query_to_insert_order =
            "INSERT INTO `orders`(
                `orders_unique_id`,
					`first_name`,
					`last_name`,
					`email`,
					`mobile_number`,
					`address`,
					`city`,
					`zipcode`,
					`state`,
					`country`
				)
				VALUES(
                    '" . $order_id . "',
				'" . $first_name . "',
				'" . $last_name . "',
				'" . $email . "',
				'" . $mobile_number . "',
				'" . $address . "',
				'" . $city . "',
				'" . $zipcode . "',
				'" . $state . "',
				'" . $country . "'
			)";

        $result = mysqli_query($conn, $query_to_insert_order);
        $inserted_ids = array();

        foreach ($_SESSION['cart'] as $k => $v) {

            // code to update product quantity
            // decreaase the quantity of each product by order quantity

            $k = mysqli_real_escape_string($conn, $k);

            /*Raw Query

            SELECT
            `product_quantity`
            FROM
            `products`
            WHERE product_id = 23;

             */

            $query_to_select_quantity =
                "SELECT
				`product_quantity`
			FROM
				`products`
			WHERE product_id = " . $k;

            $result = mysqli_query($conn, $query_to_select_quantity);
            $quantity_array = mysqli_fetch_all($result);
            $remaining_quantity = $quantity_array[0][0] - $v[0];

            /*Raw Query

            UPDATE
            `products`
            SET

            `product_quantity` = '$remaining_quantity',
            WHERE product_id = 23;

             */

            $query_to_update_products_quantity =
                "UPDATE
				`products`
			SET

				`product_quantity` = " . $remaining_quantity . "
			WHERE product_id = " . $k;

            $result = mysqli_query($conn, $query_to_update_products_quantity);

            // now query to insert the data into order array and order_products Array

            $total_quantity = mysqli_real_escape_string($conn, $v[0]);
            $product_id = mysqli_real_escape_string($conn, $k);
            $product_name = mysqli_real_escape_string($conn, $v[2]);
            $product_desc = mysqli_real_escape_string($conn, $v[3]);
            $product_cost = mysqli_real_escape_string($conn, $v[4]);

            /*Raw Query

            INSERT INTO `order_product`(
            `order_id`,
            `product_id`,
            `total_quantity`,
            `price`,
            `product_name`
            )
            VALUES(

            23,
            3,
            4,
            2312,
            'DAS'
            )

             */

            $query_to_insert_orders =
                "INSERT INTO `order_product`(
					`orders_unique_id`,
					`product_id`,
					`total_quantity`,
					`price`,
					`product_name`
				)
				VALUES(

					'$order_id',
					'$product_id',
					'$total_quantity',
					'$product_cost',
					'$product_name'
				)";

            $result = mysqli_query($conn, $query_to_insert_orders);
            $inserted_ids[] = mysqli_insert_id($conn);

            // if (!empty($inserted_ids) && !empty($order_id)) {
            //     unset($_SESSION['cart']);
            //     // $_SESSION['cart_error'] = "Order Successfully Added";
            //     // header("Location: http://localhost/shopping-cart/error-msg");
            // }
        }

        if (!empty($inserted_ids)) {

            if ($payment_method == "payU") {
                $_SESSION['user_details'] = array($first_name, $last_name, $email, $mobile_number, $address, $city, $zipcode, $state, $country, $order_id);
                header("Location: payU/payuform.php");
            }

            if ($payment_method == "Paypal") {
                $_SESSION['user_details'] = array($first_name, $last_name, $email, $mobile_number, $address, $city, $zipcode, $state, $country, $order_id);
                header("Location: Paypa/");
            }

            $success_msg = "Product inserted successfully";
            // echo "<pre>";
            // print_r($inserted_ids);
            // echo "</pre>";
        }

    } else {
        $errors[9] = "Please See Below for the Errors!";
    }

}

?>
<script type="text/javascript" src="Client/jquery.js"></script>
<script type="text/javascript" src="Client/jquery.validate.js"></script>
<script type="text/javascript" src="Client/validation.js"></script>
<div class="container">
	<div class="checkout">
		<?php if (!empty($errors[9])) {?>
			<div>
				<font color="#f00000" style="align-text: center" size="2px"><?php if (isset($errors[9])) {echo $errors[9];}?></font>
			</div>
			<?php }?>
			<?php if (!empty($success_msg)) {?>
			<div>
				<font color="#00FF00" style="align-text: center" size="2px"><?php if (isset($success_msg)) {echo $success_msg;}?></font>
			</div>
			<?php }?>
		<h2 class="page-title">Checkout</h2>
		<form id="sample" method="post" >
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>First Name <span class="color-danger">*</span></label>
						<input type="text" class="form-control" id="first_name" name="first_name" data-rule-firstname="true" value="<?php echo $first_name ?>"/>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[0])) {echo $errors[0];}?></font>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Last Name <span class="color-danger">*</span></label>
						<input type="text" class="form-control" id="last_name" name="last_name" data-rule-lastname="true" value="<?php echo $last_name ?>"/>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[1])) {echo $errors[1];}?></font>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Mobile Number <span class="color-danger">*</span></label>
						<input type="text" id="contact_no" name="mobile_number" value="<?php echo $mobile_number ?>" data-rule-mobile="true" class="form-control" />
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[3])) {echo $errors[3];}?></font>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Email <span class="color-danger">*</span></label>
						<input type="text" id="email" name="email" class="form-control" value="<?php $email?>" data-rule-email="true"/>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[2])) {echo $errors[2];}?></font>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="form-group">
						<label>Address <span class="color-danger">*</span></label>
						<textarea class="form-control" id="address_line1" name="address" value="<?php echo $address ?>" data-rule-addressLine1="true"></textarea>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[4])) {echo $errors[4];}?></font>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>City <span class="color-danger">*</span></label>
						<input type="text" name="city" id="city" class="form-control" value="<?php echo $city ?>" data-rule-mandatory="true"/>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[5])) {echo $errors[5];}?></font>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Zip Code<span class="color-danger">*</span></label>
						<input type="text" name="zipcode" id="pincode" class="form-control" value="<?php echo $zipcode ?>" data-rule-pincode="true"/>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[6])) {echo $errors[6];}?></font>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>State <span class="color-danger">*</span></label>
						<input type="text" name="state" id="state" class="form-control" value="<?php echo $state ?>" data-rule-mandatory="true"/>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[7])) {echo $errors[7];}?></font>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Country <span class="color-danger">*</span></label>
						<select name="country" class="form-control" data-rule-mandatory="true">
							<option value="" selected="">(please select a country)</option>
							<option value="AF">Afghanistan </option>
							<option value="AL">Albania </option>
							<option value="DZ">Algeria </option>
						</select>
					</div>
					<div>
						<font color="#f00000" size="2px"><?php if (isset($errors[8])) {echo $errors[8];}?></font>
					</div>
				</div>
			</div>
            <div class="row">
                <div class="form-group">
                                    <div >
                                        <label class="radio-inline" for="payment_method">
                                            <input
                                                name="payment_method"
                                                id="payment_method"
                                                <?php if ($payment_method == 'payU' || $payment_method == null) {echo 'checked';}?>
                                                value="payU"
                                                type="radio"
                                                checked=""
                                            >
                                            <span class="inline-block"><label>PayU</label></span>
                                        </label>
                                        <label class="radio-inline" for="payment_method">
                                            <input
                                                name="payment_method"
                                                id="payment_method"
                                                value="Paypal"
                                                type="radio"
                                                <?php if ($payment_method == 'Paypal') {echo 'checked';}?>
                                            >
                                            <span class="inline-block"><label>Paypal</label></span>
                                        </label>
                                        <label class="radio-inline" for="payment_method">
                                            <input
                                                name="payment_method"
                                                id="payment_method"
                                                value="authorize.net"
                                                type="radio"
                                                <?php if ($payment_method == 'authorize.net') {echo 'checked';}?>
                                            >
                                            <span class="inline-block"><label>Authorize.net</label></span>
                                        </label>
                                    </div>
                                </div>
                </div>
			<div class="row">
				<div class="col-lg-12 col-md-12 col-sm-12">
					<div class="cart-footer">
						<input type="submit" name="submit" class="btn btn-blue" value="Complete Purchase"/>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>
<?php include 'footer.php';?>