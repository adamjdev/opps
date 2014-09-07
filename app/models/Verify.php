<?php

class Verify extends DB\SQL\Mapper {

    public function __construct(DB\SQL $db) {
        parent::__construct($db,'verification');
    }

    public function getUserMobile($mobile,$code) {
        $this->load(array('code=?',$mobile));
        $this->user=$mobile;
        $this->code=$code;
        $this->save();
    }

}
