<?php

class UserController extends Controller {

	public function index()
    {
        $user = new User($this->db);
        $this->f3->set('users',$user->all());
        $this->f3->set('page_head','User List');
        $this->f3->set('message', $this->f3->get('PARAMS.message'));
        $this->f3->set('view','user/list.htm');
	}

	public function loginForm()
	{
		$this->f3->clear('SESSION');
		$user = new User($this->db);

        if($this->f3->exists('POST.login'))
        {
			
			$login = $this->f3->get('POST.mobile');
			$pw = $this->f3->get('POST.password');
			$captcha = $this->f3->get('SESSION.captcha');
			if ($captcha && strtoupper($this->f3->get('POST.captcha'))!=$captcha){
				$this->f3->reroute('/user/login/Invalid CAPTCHA code');
			}
			$auth = new \Auth($user, array('id'=>'mobile', 'pw'=>'password'));
			$login_result = $auth->login($login,md5($pw)); // returns true on successful login 
			
			if($login_result == 1){
			$user->load(array('mobile=?',$login));
			$this->f3->set('SESSION.mobile',$this->f3->get('POST.mobile'));
			if($user->verify == 'yes'){
			$this->f3->clear('PARAMS.message');
			$this->f3->set('user',$user);
			$this->f3->set('SESSION.lastseen',time());
            $this->f3->reroute('/home');
		}else{
			$this->f3->reroute('/confirmation');
		}
		}else{
			$this->f3->reroute('/user/login/Login Attempt Failed');
        }
        } else
        {
			$this->f3->clear('SESSION.mobile');
			$img=new Image;
			$this->f3->set('captcha',$this->f3->base64(
			$img->captcha('ui/fonts/thunder.ttf',16,5,'SESSION.captcha')->dump(),'image/png'));
			$this->f3->set('captcha_reg',$this->f3->base64(
			$img->captcha('ui/fonts/thunder.ttf',16,5,'SESSION.captcha_reg')->dump(),'image/png'));
            $this->f3->set('page_head','Login Page');
            $this->f3->set('message', $this->f3->get('PARAMS.message'));
            $this->f3->set('view','user/login.htm');
        }
	}

	public function register()
    {
        if($this->f3->exists('POST.register'))
        {
			
			if(($this->f3->get('POST.password') != $this->f3->get('POST.password_confirmation')) ||  !preg_match("/^3[0-9]{6}$/", $this->f3->get('POST.mobile'))){
			$this->f3->reroute('/user/login/Registration input not correct');
			}
			$captcha = $this->f3->get('SESSION.captcha_reg');
			if ($captcha && strtoupper($this->f3->get('POST.captcha_reg'))!=$captcha){
				$this->f3->reroute('/user/login/Invalid CAPTCHA code');
			}
			
            $user = new User($this->db);
            $user->first_name=$this->f3->get('POST.first_name');
            $user->last_name=$this->f3->get('POST.last_name');
            $user->email=$this->f3->get('POST.email');
            $user->mobile=$this->f3->get('POST.mobile');
			$user->password=md5($this->f3->get('POST.password'));
			$user->save();
			
			$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
			$mobile = $this->f3->get('POST.mobile');
			$verify = new Verify($this->db);
			$verify->getUserMobile($mobile,$code);
			
			$this->f3->set('SESSION.mobile',$this->f3->get('POST.mobile'));
            $this->f3->reroute('/confirmation');

		}
	}
    public function create()
    {
        if($this->f3->exists('POST.create'))
        {
            $user = new User($this->db);
            $user->add();

            $this->f3->reroute('/success/New User Created');
        } else
        {
            $this->f3->set('page_head','Create User');
            $this->f3->set('view','user/create.htm');
        }

    }

    public function update()
    {

        $user = new User($this->db);

        if($this->f3->exists('POST.update'))
        {
            $user->edit($this->f3->get('POST.id'));
            $this->f3->reroute('/success/User Updated');
        } else
        {
            $user->getById($this->f3->get('PARAMS.id'));
            $this->f3->set('user',$user);
            $this->f3->set('page_head','Update User');
            $this->f3->set('view','user/update.htm');
        }

    }

    public function delete()
    {
        if($this->f3->exists('PARAMS.id'))
        {
            $user = new User($this->db);
            $user->delete($this->f3->get('PARAMS.id'));
        }

        $this->f3->reroute('/success/User Deleted');
    }
    
    public function confirmation()
    {
		 $verify = new Verify($this->db);
		 $mobile = $this->f3->get('SESSION.mobile');
         //$verify->load(array('user=?',$mobile));
         
		if(!($this->f3->get('SESSION.mobile')))
			// Invalid session
			$this->f3->reroute('/user/login');
			
		if($this->f3->exists('POST.confirm_code'))
        {
			$user = new User($this->db);
			$user->load(array('mobile=?',$mobile));
		    $confirm_details = $verify->find(array('user=?',$mobile),array('order'=>'id DESC', 'limit'=>1));
		    foreach ($confirm_details as $obj)
		    $confirm_code = $obj->code;
			$code = $this->f3->get('POST.code');
			if($code == $confirm_code){
				$user->verify='yes';
				$user->save();
				$this->f3->reroute('/home');
			}else{
			$this->f3->reroute('/confirmation/Invalid confirmation code entered');
			}
		}
        if($this->f3->exists('SESSION.mobile') && !$this->f3->exists('POST.confirm_code'))
        {
            $this->f3->set('message', $this->f3->get('PARAMS.message'));
            $this->f3->set('view','confirmation.htm');
        }else{

        $this->f3->reroute('/user/login');
		}
    }
    
    public function resendCode(){
			$code = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 5);
			$mobile = $this->f3->get('SESSION.mobile');
			$verify = new Verify($this->db);
			$verify->getUserMobile($mobile,$code);
			$this->f3->reroute('/confirmation/New code resend to your phone number');
	}
	
	public function password(){
		if($this->f3->exists('POST.password_reset'))
		{
			$mobile = $this->f3->get('SESSION.mobile');
			$current_password = $this->f3->get('POST.current_password');
			$new_password = $this->f3->get('POST.new_password');
			$confirm_password = $this->f3->get('POST.password_confirm');
			if(($new_password != $confirm_password) || strlen($new_password) < 4)
			$this->f3->reroute('/user/password/passwords do not match or lest than 4 characters');
			
		    $user = new User($this->db);
			$user->load(array('mobile=?',$mobile));
		    if($user->password !== md5($current_password)){
		    $this->f3->reroute('/user/password/the current '.$user->password .' password is incorrect '.$current_password);
			}else{
				$user->resetPassword($mobile,$new_password);
				$this->f3->reroute('/user/password/Password successfully change');
			}
		}
		$this->f3->set('page_head','Password Reset');
        $this->f3->set('message', $this->f3->get('PARAMS.message'));
        $this->f3->set('view','home.htm');
        $this->f3->set('body','user/password.htm');
	}
	
	public function profile()
    {
        $user = new User($this->db);
        $mobile = $this->f3->get('SESSION.mobile');
        $user->load(array('mobile=?',$mobile));
        if($this->f3->exists('POST.update_profile'))
        {
			if(($this->f3->get("POST.year") != "") && ($this->f3->exists("POST.month") != "") && ($this->f3->exists("POST.day") != ""))
			$date = $this->f3->get("POST.year") .'-'.$this->f3->get("POST.month") .'-'.$this->f3->get("POST.day");
	
			$user->first_name = $this->f3->get("POST.first_name");
			$user->last_name = $this->f3->get("POST.last_name");
			$user->email = $this->f3->get("POST.email");
			$user->address = $this->f3->get("POST.address");
			if(isset($date) && $date != ""){
			$user->DOB = $date;
		}
			$user->save();
			
			$this->f3->reroute('/user/profile/Profile updated');
		}
 
        $this->f3->set('message', $this->f3->get('PARAMS.message'));
        $this->f3->set('first_name', $user->first_name);
        $this->f3->set('last_name', $user->last_name);
        $this->f3->set('profile_name', $user->first_name.' '.$user->last_name );
        $this->f3->set('user_address', $user->address);
        $this->f3->set('user_email', $user->email);
        $this->f3->set('user_telephone', $user->mobile);
        $newDate = date("F d, Y", strtotime($user->DOB));
        $this->f3->set('user_DOB', $newDate);
        $this->f3->set('view','home.htm');
        $this->f3->set('body','user/profile.htm');
	}
}
