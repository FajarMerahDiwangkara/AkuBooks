<?php


# beware that if you extends codeigniter Model class, then you should be aware that
# codeigniter Model class have a lot of reserved variable which you should
# not use for purpose other than the purpose stated in 
# https://codeigniter.com/user_guide/models/model.html
# for example, the value of variable $table is used by codeigniter Model class as table name 
# when performing db query. Don't use $table for other purpose.


class Auth extends Model {
	public static function sign_up(UserAccount $accountData) {
		response = ["log" => ""];
		if(!Cryptography::validate_uuid4($accountData->get_uuid4())) {
			throw new Exception('uuid4 format is invalid');
		}

		# TODO
		
	}
}


?>