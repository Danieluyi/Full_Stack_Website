<?php
// Establishing connection with our database
include "database.php" ;
//

// Function harvesting IP data of clients
function logVisitorIp()
{
	// preg_match() to guard against malicious injections from clients
	if ( !empty($_SERVER["HTTP_CLIENT_IP"]) &&
		preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/", $_SERVER["HTTP_CLIENT_IP"]) )
		return $_SERVER["HTTP_CLIENT_IP"];
	else if ( !empty($_SERVER["HTTP_X_REAL_IP"]) &&
		preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/", $_SERVER["HTTP_X_REAL_IP"]) )
		return $_SERVER["HTTP_X_REAL_IP"] ;
	else if ( !empty($_SERVER["HTTP_X_FORWARDED_FOR"]) &&
		preg_match("/^([0-9]{1,3}\.){3}[0-9]{1,3}$/", $_SERVER["HTTP_CLIENT_IP"]) )
		return $_SERVER["HTTP_X_FORWARDED_FOR"];
	else
		return $_SERVER["REMOTE_ADDR"];
}
//

// Function counting and showing number of
// items-added-to-cart by a customer
// When a user clicks on "add to cart" in a product page
// A new row is created inside Cart table in Database.
// The ip_add metadata under the Cart table is filled with the
// users IP address to identify it from other customer's orders.
// This function simply counts and returns the number of Rows that has the 
// Customer's IP in the Cart Table. The value is then fixed at the top-left corner
// of our WebPage
function itemCount() : void
{
	// $database is defined in database.php
	global $database;
	
	$ipAddr = logVisitorIp() ;
	$query = "SELECT * FROM cart WHERE ip_add='$ipAddr'" ;
	$sqlOutput = mysqli_query($database, $query) ;
	$resultRows = mysqli_num_rows($sqlOutput) ;
	echo $resultRows ;
}

//

// Function incrementing and showing total price
// of items-added-to-cart by a customer
function priceCount() : void
{
	// $database is defined in database.php
	global $database ;
	
	$ipAddr = logVisitorIp() ;
	$query = "SELECT * FROM cart WHERE ip_add='$ipAddr'" ;
	$sqlOutput = mysqli_query($database, $query) ;
	$priceSum = 0 ;
	while( $oneSqlRow = mysqli_fetch_array($sqlOutput) )
	{
		$productPrice = $oneSqlRow["p_id"] ;
		$orderQuantity = $oneSqlRow["qty"] ;
		$priceSum += ($productPrice * $orderQuantity) ;
	}
	echo '$' . $priceSum ;
}
//

// Function Displaying product listings in rectangular boxes
// max of 8 product listings will be displayed where it is called
// It is called in index.php
function displayProductListings() : void
{
	// $database is defined in database.php
	global $database ;
	
	$query = "SELECT * FROM products ORDER BY 1 DESC LIMIT 0,8" ;
	$sqlOutput = mysqli_query($database, $query) ;

	// This loop extracts required details from each Table row returned
	// from our database query, then makes a rectangular box that 
	// displays product details as ECHO HTML to the frontend VIEW
	while( $oneSqlRow = mysqli_fetch_array($sqlOutput) )
	{	
		$productName = $oneSqlRow["product_title"] ;
		$productPrice_tmp = $oneSqlRow["product_price"] ;
		$productBanner1 = $oneSqlRow["product_img1"] ;
		$productLabel_tmp = $oneSqlRow["product_label"] ;
		$sellerNumber = $oneSqlRow["manufacturer_id"] ;

		$querySellerInfo = "SELECT * FROM manufacturers WHERE manufacturer_id='$sellerNumber'" ;
		$sellerInfoOutput = mysqli_query($database, $querySellerInfo) ;
		$oneSellerInfoRow = mysqli_fetch_array($sellerInfoOutput) ;
		$sellerName = $oneSellerInfoRow["manufacturer_title"] ;
		
		$promoPrice_tmp = $oneSqlRow["product_psp_price"] ;
		$productUrl = $oneSqlRow["product_url"] ;

		if( $productLabel_tmp == "Sale" || $productLabel_tmp == "Gift" )
		{
			$productPrice = "<del> $$productPrice_tmp </del>" ;
			$promoPrice = "| $$promoPrice_tmp" ;
		}
		else
		{
			$productPrice = "$$productPrice_tmp" ;
			$promoPrice = '' ;
		}

		if( $productLabel_tmp != "" )
		{	
			$productLabel = " <a class='label sale' href='#' style='color:black;'>
							<div class='thelabel'>$productLabel_tmp</div>
							<div class='label-background'> </div>
							</a> " ;
		}

	echo " <div class='col-md-4 col-sm-6 single' >
			<div class='product' >
			<a href='$productUrl' >'
			<img src='admin_area/product_images/$productBanner1' class='img-responsive' >
			</a>
			<div class='text' >
			<center>
			<p class='btn btn-primary'> $sellerName </p>
			</center>
			<hr>
			<h3><a href='$productUrl' >$productName</a></h3>
			<p class='price' > $productPrice $promoPrice </p>
			<p class='buttons' >
			<a href='$productUrl' class='btn btn-default' >View details</a>
			<a href='$productUrl' class='btn btn-primary'>
			<i class='fa fa-shopping-cart'></i> Add to cart
			</a>
			</p>
			</div>
			$productLabel
			</div>
			</div> " ;
	}
}
//

// Function to extract data from HTML form input, then arrange
// the data into a structured query lang before sending to database
// then process database output and echoes the product listing
// in HTML to the frontend VIEW
function altProductListings() : void
{
	// $database is defined in database.php
	global $database ;

	// Concatenating the Database query for Product manufacturers'
	// data stored under "manufacturer_id" metadata in DB Table
	$aWhere = array() ;
	if( isset($_REQUEST["man"]) && is_array($_REQUEST["man"]) )
	{
		foreach( $_REQUEST["man"] as $pickKey => $pickVal )
		{
			if( (int)$pickVal )
				$aWhere[] = "manufacturer_id=" . (int)$pickVal ;
		}	
	}

	// Concatenating the Database query for product categories
	// stored under "p_cat" metadata in DB Table
	if( isset($_REQUEST["p_cat"]) && is_array($_REQUEST["p_cat"]) )
	{
		foreach( $_REQUEST["p_cat"] as $pickKey => $pickVal )
		{
			if( (int)$pickVal )
				$aWhere[] = "p_cat_id=" . (int)$pickVal ;	
		}
		
	}

	// Concatenating the Database query for product categories
	// stored under "cat_id" metadata in DB Table
	if( isset($_REQUEST["cat"]) && is_array($_REQUEST["cat"]) )
	{
		foreach( $_REQUEST["cat"] as $pickKey => $sVal )
		{	
			if( (int)$pickVal )
				$aWhere[] = "cat_id=" .(int)$pickVal ;
		}
	}

	// This part structures the query to request products
	// from the database table
	$per_page = 6 ;
	if( isset($_GET["page"]) )
		$page = $_GET["page"] ;
	else
		$page = 1 ;
	$start_from = ($page -1) * $per_page ;
	if ( $start_from < 0 )
		$start_from = 0 ;
	$sLimit = " ORDER BY 1 DESC LIMIT $start_from, $per_page" ;
	if( count($aWhere) > 0 )
		$aWhere_tmp = " WHERE " . implode(" or ", $aWhere) . $sLimit ;
	else
		$aWhere_tmp = $sLimit ;
	$query = "SELECT * FROM products " . $aWhere_tmp ;

	// at this point, $sqlOutput is storing a string similar to
	// SELECT * FROM products WHERE manufacturer_id=1 or cat_id=1 ORDER BY 1 DESC LIMIT 0, 6
	$sqlOutput = mysqli_query($database, $query) ;

	// This loop extracts required details from each Table row returned
	// from our database query, then makes a rectangular box that 
	// displays product details as ECHO HTML to the frontend VIEW
	while( $oneSqlRow = mysqli_fetch_array($sqlOutput) )
	{
		$productName = $oneSqlRow['product_title'] ;
		$productPrice_tmp = $oneSqlRow['product_price'] ;
		$productBanner1 = $oneSqlRow['product_img1'] ;
		$productLabel_tmp = $oneSqlRow['product_label'] ;
		$sellerNumber = $oneSqlRow['manufacturer_id'] ;

		$querySellerInfo = "SELECT * FROM manufacturers WHERE manufacturer_id='$sellerNumber'" ;
		$sellerInfoOutput = mysqli_query($database, $querySellerInfo) ;
		$oneSellerInfoRow = mysqli_fetch_array($sellerInfoOutput) ;
		$sellerName = $oneSellerInfoRow["manufacturer_title"] ;
		$promoPrice_tmp = $oneSqlRow['product_psp_price'] ;
		$productUrl = $oneSqlRow['product_url'] ;
		
		if($productLabel_tmp == "Sale" or $productLabel_tmp == "Gift")
		{
			$productPrice = "<del> $$productPrice_tmp </del>" ;
			$promoPrice = "| $$promoPrice_tmp" ;
		}
		else
		{	
			$productPrice = "$$productPrice_tmp" ;
			$promoPrice = "" ;
		}

		if( $productLabel_tmp != "" )
		{	
			$productLabel = " <a class='label sale' href='#' style='color:black;'>
							<div class='thelabel'>$productLabel_tmp</div>
							<div class='label-background'> </div>
							</a> " ;
		}
		
		echo " <div class='col-md-4 col-sm-6 center-responsive' >
				<div class='product' >
				<a href='$productUrl' >
				<img src='admin_area/product_images/$productBanner1' class='img-responsive' >
				</a>
				<div class='text' >
				<center>
				<p class='btn btn-primary'> $manufacturer_name </p>
				</center>
				<hr>
				<h3><a href='$productUrl' >$productName</a></h3>
				<p class='price' > $productPrice $promoPrice </p>
				<p class='buttons' >
				<a href='$productUrl' class='btn btn-default' >View details</a>
				<a href='$productUrl' class='btn btn-primary'>
				<i class='fa fa-shopping-cart' data-price=$productPrice_tmp></i> Add to cart
				</a>
				</p>
				</div>
				$productLabel
				</div>
				</div> " ;
	}
}
//

// Function for displaying numbered links 
// to multiple product listing pages
// visible at the bottom of shop.php
function displayPaginator() : void
{
	// $database is defined in database.php
	global $database ;

	$per_page = 6 ;
	$aWhere = array() ;
	$aPath = '' ;

	// Concatenating the Database query for Product manufacturers'
	// data stored under "manufacturer_id" metadata in DB Table
	if( isset($_REQUEST["man"]) && is_array($_REQUEST["man"]) )
	{
		foreach( $_REQUEST["man"] as $pickKey => $pickVal )
		{
			if( (int)$pickVal )
			{
				$aWhere[] = "manufacturer_id=" . (int)$pickVal ;
				$aPath .= "man[]=" . (int)$pickVal . '&' ;
			}
		}
	}
	
	// Concatenating the Database query for product categories
	// stored under "p_cat" metadata in DB Table
	if( isset($_REQUEST["p_cat"])&&is_array($_REQUEST["p_cat"]) )
	{
		foreach( $_REQUEST["p_cat"] as $pickKey => $pickVal )
		{
			if( (int)$pickVal )
			{
				$aWhere[] = "p_cat_id=" . (int)$pickVal ;
				$aPath .= "p_cat[]=" . (int)$pickVal . '&' ;
			}
		}	
	}	

	// Concatenating the Database query for product categories
	// stored under "cat_id" metadata in DB Table
	if( isset($_REQUEST["cat"]) && is_array($_REQUEST["cat"]) )
	{
		foreach( $_REQUEST["cat"] as $sKey => $sVal )
		{
			if( (int)$sVal )
			{
				$aWhere[] = "cat_id=" . (int)$sVal ;
				$aPath .= "cat[]=" . (int)$sVal . '&' ;
			}
		}
	}
	// This part structures the query to request products
	// from the database table
	if( count($aWhere) > 0 )
		$aWhere_tmp = " WHERE " . implode(" or ", $aWhere) ;
	else
		$aWhere_tmp = '' ;
	$query = "SELECT * FROM products " . $aWhere_tmp ;

	// at this point, $sqlOutput is storing a string similar to
	// SELECT * FROM products WHERE manufacturer_id=1 or cat_id=1 ORDER BY 1 DESC LIMIT 0, 6
	$sqlOutput = mysqli_query($database, $query) ;
	$recordCount = mysqli_num_rows($sqlOutput) ;
	$pageCount = ceil($recordCount / $per_page) ;

	// Now we output URL links for paginating
	echo "<li><a href='shop.php?page=1" ;
	if( !empty($aPath) )
		echo '&' . $aPath ;
	echo "' >" . 'First Page' . "</a></li>" ;
	for ( $i = 1; $i <= $pageCount; $i++ )
		echo "<li><a href='shop.php?page=" . $i . (!empty($aPath) ? '&' . $aPath : '') . "' >" . $i ."</a></li>" ;
	echo "<li><a href='shop.php?page=$pageCount" ;
	if( !empty($aPath) )
		echo "&" . $aPath ;
	echo "' >" . 'Last Page' . "</a></li>" ;
}

?>


	

	
	