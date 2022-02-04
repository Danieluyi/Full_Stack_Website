<?php

session_start();

// M: Establishing global connection with our database
include "includes/database.php" ;

// M: Containing multiple functions for manipulating
// front View
include "includes/functions.php" ;

// V: Contents of the HTML header section (without the </head> closing tag)
include "includes/header.php" ;

// V: Contents of the homepage (begins with the </head> closing tag)
include "includes/main.php" ;

?>

<!-- Cover -->
<main>
    <div class="hero">
      <a href="shop.php" class="btn1">View all products
</a>
    </div>
    <!-- Main -->
    <div class="wrapper">
            <h1>Featured Collection<h1>
            
      </div>



    <div id="content" class="container"><!-- container Starts -->

    <div class="row"><!-- row Starts -->

    <?php

	displayProductListings();

    ?>

    </div><!-- row Ends -->

    </div><!-- container Ends -->


	    <!-- FOOTER -->
		<footer class="page-footer">

<div class="footer-nav">
  <div class="container clearfix">

	<div class="footer-nav__col footer-nav__col--info">
	  <div class="footer-nav__heading">Information</div>
	  <ul class="footer-nav__list">
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">The brand</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Local stores</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Customer service</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Privacy &amp; cookies</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Site map</a>
		</li>
	  </ul>
	</div>

	<div class="footer-nav__col footer-nav__col--whybuy">
	  <div class="footer-nav__heading">Why buy from us</div>
	  <ul class="footer-nav__list">
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Shipping &amp; returns</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Secure shipping</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Testimonials</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Award winning</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Ethical trading</a>
		</li>
	  </ul>
	</div>

	<div class="footer-nav__col footer-nav__col--account">
	  <div class="footer-nav__heading">Your account</div>
	  <ul class="footer-nav__list">
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Sign in</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Register</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">View cart</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">View your lookbook</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Track an order</a>
		</li>
		<li class="footer-nav__item">
		  <a href="#" class="footer-nav__link">Update information</a>
		</li>
	  </ul>
	</div>


	<div class="footer-nav__col footer-nav__col--contacts">
	  <div class="footer-nav__heading">Contact details</div>
	  <address class="address">
	  Location: Web Store<br>
	  Lorem close, Ipsum District - Dolor
	</address>
	  <div class="phone">
		Mobile:
		<a class="phone__number" href="tel:0123456789">0123-456-789</a>
	  </div>
	  <div class="email">
		Email:
		<a href="mailto:lorem@webstore.khrov.com" class="email__addr">lorem@webstore.khrov.com</a>
	  </div>
	</div>

  </div>
</div>

<div class="banners">
  <div class="container clearfix">
<center>
  <a href="#" class="banner-social__link" style="background-color:rgb(4, 4, 85)">
	  <i class="icon-facebook"></i>
	</a>
	  <a href="#" class="banner-social__link" style="background-color:rgb(4, 4, 85)">
	  <i class="icon-twitter"></i>
	</a>
	  <a href="#" class="banner-social__link" style="background-color:rgb(4, 4, 85)">
	  <i class="icon-instagram"></i>
	</a>
	  <a href="#" class="banner-social__link" style="background-color:rgb(4, 4, 85)">
	  <i class="icon-pinterest-circled"></i>
	</a>
</center>

  </div>
</div>

<div class="page-footer__subline">
<div class="container clearfix">

<div class="copyright">
	&copy; 2022 Web Store&trade;
</div>
</div>
</div>
</footer>
</body>

</html>
