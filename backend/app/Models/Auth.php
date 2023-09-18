<?php


# beware that if you extends codeigniter Model class, then you should be aware that
# codeigniter Model class have a lot of reserved variable which you should
# not use for purpose other than the purpose stated in 
# https://codeigniter.com/user_guide/models/model.html
# for example, the value of variable $table is used by codeigniter Model class as table name 
# when performing db query. Don't use $table for other purpose.


class Auth extends Model {

	private static function check_if_email_registered($email) {
		# TODO
	}
	
	public static function sign_up(UserAccount $accountData) {
		data = [
			"success" => "false",
			"uuid4_valid" => null,
			"name_valid" => null,
			"username_valid" => null,
			"password_good" => null,
			"email_already_registered" => null,
			"log" => ""
		];

		# TODO
		response = []

		if(!UserAccount::validate_uuid4($accountData->get_uuid4())) {
			data['uuid4_valid'] = false;
			return response;
		}
		data['uuid4_valid'] = true;

		if(!UserAccount::validate_name($accountData->get_name())) {
			data['name_valid'] = false;
			return response;
		}
		data['name_valid'] = true;

		if(!UserAccount::validate_username($accountData->get_username())) {
			data['username_valid'] = false;
			return response;
		}
		data['username_valid'] = true;

		if(!UserAccount::validate_password_plaintext_good($accountData->get_password_plaintext())) {
			data['password_good'] = false;
			return response;
		}
		data['password_good'] = true;

		# TODO
		# Check whether email already registered or not

		return response;
	}
}


?>