<?php

class Meters extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'nawec_cash_reg');
    }
    
        public function all() {
        $this->load();
        return $this->query;
    }
    
        public function meters($mobile) {
         $this->load(array('phone=? and confirm="yes"',$mobile));
        return $this->query;
    }
 }
