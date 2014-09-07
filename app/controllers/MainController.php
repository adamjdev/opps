<?php
class MainController extends Controller {

	function beforeroute() {
		$this->f3->set('message','');
		if ($this->f3->get('SESSION.lastseen')+$this->f3->get('expiry')*3600<time())
			// Session has expired
			$this->f3->reroute('/user/login');
		// Update session data
		$this->f3->set('SESSION.lastseen',time());
	}
	
		public function home()
    {
		
		$mobile = $this->f3->get('SESSION.mobile');
        $meters = new Meters($this->db);
        $this->f3->set('meters',$meters->meters($mobile));
        $img=new Image;
		$this->f3->set('captcha_qpay',$this->f3->base64(
		$img->captcha('ui/fonts/thunder.ttf',15,5,'SESSION.captcha')->dump(),'image/png'));
        $this->f3->set('page_head','Home');
        $this->f3->set('message', $this->f3->get('PARAMS.message'));
        $this->f3->set('view','home.htm');
        $this->f3->set('body','qpower.htm');
        if($this->f3->exists('PARAMS.amount')){
		$amount = $this->f3->get('PARAMS.amount');
		$charge= ($amount *3.9/100) ;
		$amount1=$amount -$charge;
		$service_fee=10;
		$vend_amount = ($amount1 * 41) -10;
		$this->f3->set('charge', $charge);
		$this->f3->set('service_fee', $service_fee);
		$this->f3->set('vend_amount', $vend_amount);
		
		}
	}
	
	public function payment()
	{
		$captcha = $this->f3->get('SESSION.captcha_qpay');
			
		if($this->f3->exists('POST.amount')){
			if ($captcha && strtoupper($this->f3->get('POST.captcha_qpay'))!=$captcha){
				$this->f3->reroute('/home/Invalid CAPTCHA code');
			}
		$amount = $this->f3->get('POST.amount');
		$submit = $this->f3->get('POST.submit');
		if($submit == 'paypal')
		$charge= ($amount *3.9/100);
		if($submit == 'credit')
		$charge= ($amount *2.9/100);
		if($submit == 'bank')
		$charge= ($amount *0.9/100);
		
		$amount1=$amount -$charge;
		$service_fee=10;
		$vend_amount = round(($amount1 * 41) -10,0);
		$this->f3->set('amount', $amount);
		$this->f3->set('charge', $charge);
		$this->f3->set('service_fee', $service_fee);
		$this->f3->set('vend_amount', $vend_amount, 60);
		$this->f3->set('meter', $this->f3->get('POST.meter'), 60);
		
		//set the session vairables
		$this->f3->set('SESSION.vend_amount',$vend_amount);
		$this->f3->set('SESSION.meter',$this->f3->get('POST.meter'));
		
		$client_id = "100047";
$client_secret = "dc6457521d";
$access_token = "STAGE_0340a90c4dcbf2101713e14fe5dec0acf509e8db983ab647e2e9a81de0b8ac37";
$account_id = "1443337002"; // you can find your account ID via list_accounts.php which users the /account/find call

/** 
 * Initialize the WePay SDK object 
 */
//require '../../wepay/wepay.php';
Wepay::useStaging($client_id, $client_secret);
$wepay = new WePay($access_token);

/**
 * Make the API request to get the checkout_uri
 * 
 */
try {
	$checkout = $wepay->request('/checkout/create', array(
			'account_id' => $account_id, // ID of the account that you want the money to go to
			'amount' => $amount, // dollar amount you want to charge the user
			'fee_payer' => "payee", //service fee will be paid by payee
			'short_description' => "this is a qpower payment", // a short description of what the payment is for
			'type' => "SERVICE", // the type of the payment - choose from GOODS SERVICE DONATION or PERSONAL
			'mode' => "iframe", // put iframe here if you want the checkout to be in an iframe, regular if you want the user to be sent to WePay
			
		)
	);
	$this->f3->set('SESSION.checkout',$checkout);
} catch (WePayException $e) { // if the API call returns an error, get the error message for display later
	$error = $e->getMessage();
	$this->f3->set('error', $error);
}

		$this->f3->set('checkout', $checkout);
		
		
		 $this->f3->set('view','home.htm');
        $this->f3->set('body','ajax-display.htm');
		
		}
	
	}
	
			public function payment_complete()
    {
		
		$nawec = new Nawec;
		
		$mobile = $this->f3->get('SESSION.mobile');
       
        if($this->f3->exists('PARAMS.amount')){
		$amount = $this->f3->get('PARAMS.amount');
		$vend_amount = $this->f3->get('SESSION.vend_amount');	
		$meter = $this->f3->get('SESSION.meter');
		$checkout = $this->f3->get('SESSION.checkout');
		list($success,$error) = $nawec->pre_vend($meter ,$vend_amount, 9313);
		
		
			
		$this->f3->reroute('/home/payment/complete');
		//clear session vairables
		$this->f3->clear('SESSION.vend_amount');
		$this->f3->clear('SESSION.meter');
		
		
	
		}else{
		$this->f3->reroute('/home');
		}
	}
	
	 public function purchase_confirmation()
    {
		 $verify = new Verify($this->db);
		 $mobile = $this->f3->get('SESSION.mobile');
         //$verify->load(array('user=?',$mobile));
         
			
		
			
            $this->f3->set('message', $this->f3->get('PARAMS.checkout'));
            //$CI->stdClass->userdata('checkout_id')->username
            $checkout = $this->f3->get('SESSION.checkout');
            $checkout_id = $checkout->checkout_id;
            $checkout_uri = $checkout->checkout_uri;
            
            $this->f3->set('checkout_id', $checkout_id);
            $this->f3->set('checkout_uri', $checkout_uri);
            $this->f3->set('mobile', $mobile);
            $this->f3->set('view','purchase_confirm.htm');
            
        
    }
	
}
