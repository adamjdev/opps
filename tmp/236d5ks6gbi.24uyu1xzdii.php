	<div class="row">
        <div class="span12">
    		<div class="thumbnail center well well-small text-center">
                <h2>Registration Confirmation</h2>
                
                <p>To complete the registration, you must enter the registration code which has been send to your mobile phone</p>
           
                
                <form name="confirmation" action="<?php echo $BASE.'/confirmation'; ?>" method="post">
                    <div class="input-group "><span class="input-group-addon glyphicon glyphicon-edit"></span>
                        <input type="text" id="code" class="form-control" name="code" placeholder="Enter code">
                    </div>
                    <p>
                    did not recieve your code? click <a href="<?php echo $BASE.'/confirmation/resend'; ?>"><strong class="label label-primary">Here</strong></a>
                    or call QCare on 111 for help!</p>
                               
                    <br />
                    <input type="hidden" name="confirm_code" value="confirm_code" />
                    <div class="col-xs-6 col-md-2"><input type="submit" value="Confirm" class="btn btn-success btn-block btn-lg"></div>
              </form>
            </div>    
        </div>
	</div>
