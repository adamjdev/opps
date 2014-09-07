
		<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" >
	<div class="modal-dialog">
	
			
			<div class="modal-body">
	
	<div class="col-xs-12 col-md-10">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h2 class="panel-title">
                       <strong>Verify Purchase Details</strong></h2>
                </div>
                <div class="panel-body">
                   
                    <table class="table">
                        <tr>
                            <td>
                                <strong>Paid Amount: $ <?php echo $amount; ?></strong>
                            </td>
                        </tr>
                        <tr class="active">
                            <td>
                               <strong>Payment Charges: $ <?php echo $charge; ?></strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <strong>Service Fee: GMB <?php echo $service_fee; ?></strong>
                            </td>
                        </tr>
                        <tr class="active">
                            <td>
                               <strong>Purchase Amount: GMB <?php echo $vend_amount; ?></strong>
                            </td>
                        </tr>
                        
                    </table>
                </div>
                <div class="panel-footer">
                    <button type="button" class="btn btn-primary" data-dismiss="modal">I Agree</button>
                    1 month FREE trial</div>
            </div>
        </div>
					

</div>
		
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<h1>Checkout:</h1>
		
		
		<check if <?php if (isset($error)): ?>
			<h2 style="color:red">ERROR: <?php echo $error; ?></h2>
		<?php endif; ?>
		<check if <?php if (!isset($error)): ?>
			<div id="checkout_div"></div>
		
			<script type="text/javascript" src="https://stage.wepay.com/js/iframe.wepay.js">
			</script>
			
			<script type="text/javascript">
			WePay.iframe_checkout("checkout_div", "<?php echo $checkout->checkout_uri ?>");
			WePay.listen("iframe_checkout_complete", function() {
			window.location="<?php echo $BASE.'/home/payment/1'; ?>";
			//alert("Checkout is done"); // this is fired when checkout is complete
			});
			</script>
		<?php endif; ?>
