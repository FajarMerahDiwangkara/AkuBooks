<?php

namespace App\Models;

use CodeIgniter\Database\Query;

class Auth{

	public static function check_if_email_address_registered($email_address) {
		assert(is_string($email_address));
		# https://codeigniter.com/user_guide/database/connecting.html
		$db = db_connect('userAccount', true);
		# https://www.securityjourney.com/post/how-to-prevent-sql-injection-vulnerabilities-how-prepared-statements-work
		# https://codeigniter.com/user_guide/database/queries.html#prepared-queries
		# https://stackoverflow.com/questions/72511782/codeigniter-4-named-prepared-statements-throws-error-code-500-the-number-of-va
		$preparedQuery = $db->prepare(static function ($db) {
		$query = 'SELECT 1 FROM user_account_info WHERE email=?';
		return (new Query($db))->setQuery($query);
		});
		# https://codeigniter.com/user_guide/database/results.html
		$result = $preparedQuery->execute($email_address)->getResultArray();
		if(count($result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function sign_up(UserAccount $accountData) {
		$data = [
			"success" => false,
			"uuid4_already_exist" => null,
			"name_valid" => null,
			"username_valid" => null,
			"password_good" => null,
			"email_address_not_registered" => null,
			"log" => ""
		];

		$db = db_connect('userAccount', true);
		
		# remember that != is different from !==
		if($accountData->get_uuid4() !== null) {
			# if accountData already contain uuid4,
			# then check that uuid4 is valid and
			# does not exist in database yet
			assert(UserAccount::validate_uuid4($accountData->get_uuid4()));
			$preparedQuery = $db->prepare(static function ($db) {
			$query = 'SELECT 1 FROM user_account_info WHERE uuid4=?';
			return (new Query($db))->setQuery($query);
			});
			# https://codeigniter.com/user_guide/database/results.html
			$result = $preparedQuery->execute($accountData->get_uuid4())->getResultArray();
			if(count($result) > 0) {
				$data['uuid4_already_exist'] = true;
				$data['log'] = "Account uuid4 already exist, unable to register.";
				return $data;
			}
		} else {
			$accountData->set_uuid4(UserAccount::generate_uuid4());
		}
		$data['uuid4_already_exist'] = false;

		if($accountData->get_email_address_verified() == null) {
			# check if accountData already have value for email_address_verified,
			# if not then set value to false
			$accountData->set_email_address_verified(false);
		}

		if(!UserAccount::validate_name($accountData->get_name())) {
			$data['name_valid'] = false;
			$data['log'] = "Account name is not valid.";
			return $data;
		}
		$data['name_valid'] = true;

		if(!UserAccount::validate_username($accountData->get_username())) {
			$data['username_valid'] = false;
			$data['log'] = "Account username is not valid.";
			return $data;
		}
		$data['username_valid'] = true;

		if(!UserAccount::validate_password_plaintext_good($accountData->get_password_plaintext())) {
			$data['password_good'] = false;
			$data['log'] = "Account password is too weak.";
			return $data;
		}
		$data['password_good'] = true;

		if(self::check_if_email_address_registered($accountData->get_email_address())) {
			$data['email_address_not_registered'] = false;
			$data['log'] = "Account email is already registered.";
			return $data;
		}
		$data['email_address_not_registered'] = true;

		$preparedQuery = $db->prepare(static function ($db) {
		$query = 'INSERT INTO user_account_info(uuid4, hashed_password, email, name, username, email_verified)'.
		'VALUES (?,?,?,?,?,?);';
		return (new Query($db))->setQuery($query);
		});
		$results = $preparedQuery->execute(
		$accountData->get_uuid4(),
		UserAccount::hash_password($accountData->get_password_plaintext()),
		$accountData->get_email_address(),
		$accountData->get_name(),
		$accountData->get_username(),
		$accountData->get_email_address_verified()
		);
		# assert that data insert successfully
		assert(is_bool($results));
		assert($results);

		$data['success'] = true;
		return $data;
	}
}


?>