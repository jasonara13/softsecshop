<?php

?>
<div class="footer">
    <div class="row">
        <?php if($_SERVER['REQUEST_URI'] != "/login.php"): ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cart-footer-div">
                <p><a href="#cartinfo"><button class="btn btn-secondary cart-footer"><img src="/assets/images/cart.png" width="30" height="30" /> <span id="cartbadge">0</span></button></a></p>
            </div>
        <?php else: ?>
            <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 cart-footer-div">
                <p>&nbsp;</p>
            </div>
        <?php endif; ?>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
            <p class="copyright">Software Security Shop <i class="fa fa-copyright"></i> 2019</p>          
        </div>
    </div>
  
</div>
