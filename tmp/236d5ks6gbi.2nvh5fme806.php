

<div class="col-xs-12 col-sm-8 col-md-6 col-sm-offset-2 col-md-offset-3">
		
				 
  <div class="badger-left badger-success" data-badger="Select Meter">
		<form accept-charset="UTF-8" role="form" action="<?php echo $BASE.'/home/payment'; ?>" method="post" >
			<div class="btn-group" data-toggle="buttons-radio">
			 <?php foreach (($meters?:array()) as $meter): ?>
				 
			<input type="radio" name="meter" id="<?php echo trim($meter['meter_no']); ?>" value="<?php echo trim($meter['meter_no']); ?>" />
                <strong><?php echo trim($meter['name']); ?> &nbsp; [ <?php echo trim($meter['meter_no']); ?> ]</strong><br />
    <?php endforeach; ?>
    </div>
</div>
		
          <fieldset>
			     <div class="col-xs-4">	  	
            <div class="input-group">
				  <span class="input-group-addon"><strong>Amount: </strong></span>
                <span class="input-group-addon">$</span>
                <input type="text" class="form-control" placeholder="US Dollar" id="amount" name="amount" required>
                <span class="input-group-addon">.00</span>
            </div>
           
<br />


<p>
						<label for="captcha_qpay"><small>CAPTCHA</small></label><br />
						<img src="<?php echo $captcha_qpay; ?>" title="captcha" />
						<input id="captcha_qpay" name="captcha_qpay" type="text" required />
						</p>
<div class="container">
			<div class="row">
        <div class="col-xs-5">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        <span class="glyphicon glyphicon-bookmark"></span> Payment Method</h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        
                          
                          <button type="submit" name="submit" value="credit" class="btn btn-danger btn-lg"><i class="glyphicon glyphicon-list-alt"></i><br/> Credit Card</button>
                          <button type="submit" name="submit" value="paypal" class="btn btn-warning btn-lg"><i class="glyphicon glyphicon-bookmark"></i><br/> Paypal</button>
                          <button type="submit" name="submit" value="bank" class="btn btn-primary btn-lg"><i class="glyphicon glyphicon-signal"></i><br/> Bank</button>
			    	</fieldset>
			      	</form>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>


</div>

