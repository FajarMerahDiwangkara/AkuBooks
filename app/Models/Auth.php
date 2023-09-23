<?php

namespace App\Models;

use CodeIgniter\Database\Query;

class Auth{

	public static function is_logged_in() {
		# https://stackoverflow.com/questions/6249707/check-if-php-session-has-already-started
		if(session_status() == PHP_SESSION_NONE || !isset($_SESSION['login_session_token']) 
		|| $_SESSION['login_session_token'] == null ) {
			return false;
		}
		# check that login session token is still valid
		$db = db_connect('userAccount', true);
		$prepared_query = $db->prepare(static function ($db) {
			$query = 'SELECT 1 FROM user_account_login_session WHERE session_id=? AND login_session_token=?';
			return (new Query($db))->setQuery($query);
		}
		);
		$query_result = $prepared_query->execute(session_id(), $_SESSION['login_session_token'])->getResultArray();
		if(count($query_result) == 0) {
			return false;
		}

		return true;
	}

	public static function set_login_session($user_uuid4, $session_id, $login_session_token) {
		$db = db_connect('userAccount', true);
		$prepared_query = $db->prepare(static function ($db) {
			$query = 'DELETE FROM user_account_login_session WHERE user_uuid4=?';
			return (new Query($db))->setQuery($query);
		}
		);
		$prepared_query->execute($user_uuid4);
		$prepared_query = $db->prepare(static function ($db) {
			$query = 'INSERT INTO user_account_login_session(user_uuid4, session_id, login_session_token,'.
			' session_creation_datetime)'.
			' VALUES (?,?,?,?);';
			return (new Query($db))->setQuery($query);
		}
		);
		$prepared_query->execute(
			$user_uuid4, 
			$session_id, 
			$login_session_token, 
			date('y-m-d h:m:s', time())
		);
		# https://codeigniter4.github.io/userguide/libraries/cookies.html
		$_SESSION['login_session_token'] = $login_session_token;
	}

	public static function check_if_email_address_registered($email_address) {
		assert(is_string($email_address));
		# https://codeigniter.com/user_guide/database/connecting.html
		$db = db_connect('userAccount', true);
		# https://www.securityjourney.com/post/how-to-prevent-sql-injection-vulnerabilities-how-prepared-statements-work
		# https://codeigniter.com/user_guide/database/queries.html#prepared-queries
		# https://stackoverflow.com/questions/72511782/codeigniter-4-named-prepared-statements-throws-error-code-500-the-number-of-va
		$prepared_query = $db->prepare(static function ($db) {
		$query = 'SELECT 1 FROM user_account_info WHERE email=?';
		return (new Query($db))->setQuery($query);
		});
		# https://codeigniter.com/user_guide/database/results.html
		$query_result = $prepared_query->execute($email_address)->getResultArray();
		if(count($query_result) > 0) {
			return true;
		} else {
			return false;
		}
	}
	
	public static function sign_up(UserAccount $account_data, $filter_data=true) {
		$data = [
			"success" => false,
			"already_logged_in" => null,
			"uuid4_already_exist" => null,
			"name_valid" => null,
			"username_valid" => null,
			"email_address_not_null" => null,
			"password_good" => null,
			"email_address_not_registered" => null,
			"log" => ""
		];

		$db = db_connect('userAccount', true);
		
		# remember that != is different from !==
		if($account_data->get_uuid4() !== null) {
			# if account_data already contain uuid4,
			# then check that uuid4 is valid and
			# does not exist in database yet
			assert(UserAccount::validate_uuid4($account_data->get_uuid4()));
			$prepared_query = $db->prepare(static function ($db) {
			$query = 'SELECT 1 FROM user_account_info WHERE uuid4=?';
			return (new Query($db))->setQuery($query);
			});
			# https://codeigniter.com/user_guide/database/results.html
			$result = $prepared_query->execute($account_data->get_uuid4())->getResultArray();
			if(count($result) > 0) {
				$data['uuid4_already_exist'] = true;
				$data['log'] = "Account uuid4 already exist, unable to register.";
				return $data;
			}
		} else {
			$account_data->set_uuid4(UserAccount::generate_uuid4());
		}
		$data['uuid4_already_exist'] = false;

		if($account_data->get_email_address_verified() == null) {
			# check if account_data already have value for email_address_verified,
			# if not then set value to false
			$account_data->set_email_address_verified(false);
		}

		if($filter_data && $account_data->get_name() != null) {
			$account_data->set_name(htmlspecialchars($account_data->get_name()));
		}

		if(!UserAccount::validate_name($account_data->get_name())) {
			$data['name_valid'] = false;
			$data['log'] = "Account name is not valid.";
			return $data;
		}
		$data['name_valid'] = true;

		if($filter_data && $account_data->get_username() != null) {
			$account_data->set_username(htmlspecialchars($account_data->get_username()));
		}

		if(!UserAccount::validate_username($account_data->get_username())) {
			$data['username_valid'] = false;
			$data['log'] = "Account username is not valid.";
			return $data;
		}
		$data['username_valid'] = true;

		if($filter_data && $account_data->get_email_address() != null) {
			$account_data->set_email_address(htmlspecialchars($account_data->get_email_address()));
		}

		if($account_data->get_email_address() == null) {
			$data['email_address_not_null'] = false;
			$data['log'] = "Email address is null";
			return $data;
		}
		$data['email_address_not_null'] = true;

		if(!UserAccount::validate_password_plaintext_good($account_data->get_password_plaintext())) {
			$data['password_good'] = false;
			$data['log'] = "Account password is too weak.";
			return $data;
		}
		$data['password_good'] = true;

		if(self::check_if_email_address_registered($account_data->get_email_address())) {
			$data['email_address_not_registered'] = false;
			$data['log'] = "Account email is already registered.";
			return $data;
		}
		$data['email_address_not_registered'] = true;

		$prepared_query = $db->prepare(static function ($db) {
		$query = 'INSERT INTO user_account_info(uuid4, hashed_password, email, name, username, email_verified)'.
		'VALUES (?,?,?,?,?,?);';
		return (new Query($db))->setQuery($query);
		});
		$results = $prepared_query->execute(
		$account_data->get_uuid4(),
		UserAccount::hash_password($account_data->get_password_plaintext()),
		$account_data->get_email_address(),
		$account_data->get_name(),
		$account_data->get_username(),
		$account_data->get_email_address_verified()
		);
		# assert that data insert successfully
		assert(is_bool($results));
		assert($results);

		$data['success'] = true;
		return $data;
	}

	public static function sign_in($email_address, $password_plaintext) {
		$data = [
			"success" => false,
			"already_logged_in" => null,
			"email_address_not_null" => null,
			"password_not_null" => null,
			"email_address_or_password_correct" => null,
			"email_address_not_verified" => null,
			"log" => ""
		];

		$db = db_connect('userAccount', true);

		if(session_status() == PHP_SESSION_NONE) {
			session_start();
		}

		if(self::is_logged_in()) {
			$data['already_logged_in'] = true;
			$data['log'] = "You are already logged in";
			return $data;
		}
		$data['already_logged_in'] = false;

		if($email_address == null) {
			$data['email_address_not_null'] = false;
			$data['log'] = "Email address is null";
			return $data;
		}
		$data['email_address_not_null'] = true;

		if($password_plaintext == null) {
			$data['password_not_null'] = false;
			$data['log'] = "Password is null";
			return $data;
		}
		$data['password_not_null'] = true;

		$prepared_query = $db->prepare(static function ($db) {
		$query = 'SELECT uuid4,hashed_password FROM user_account_info WHERE email=?;';
		return (new Query($db))->setQuery($query);
		});
		$query_result = $prepared_query->execute($email_address)->getResultArray();
		$passwordCiphertext = null;
		if(count($query_result) != 0) {
			$passwordCiphertext = $query_result[0]['hashed_password'];
		}
		if(!UserAccount::verify_password($password_plaintext, $passwordCiphertext)) {
			$data['email_address_or_password_correct'] = false;
			$data['log'] = "Email address or password is incorrect.";
			return $data;
		}
		$data['email_address_or_password_correct'] = true;

		$user_uuid4 = $query_result[0]['uuid4'];

		self::set_login_session($user_uuid4, session_id(), Cryptography::generate_uuid4());

		$data['success'] = true;
		
		return $data;
	}

	public static function sign_out() {
		$data = [
			"success" => false
		];
		if(session_status() == PHP_SESSION_NONE) {
			session_start();
		}
		if(isset($_SESSION['login_session_token'])) {
			$db = db_connect('userAccount', true);
			$prepared_query = $db->prepare(static function ($db) {
				$query = 'SELECT user_uuid4 FROM user_account_login_session WHERE session_id=? AND login_session_token=?';
				return (new Query($db))->setQuery($query);
			}
			);
			$query_result = 
			$prepared_query->execute(session_id(), $_SESSION['login_session_token'])->getResultArray();
			if(count($query_result) != 0) {
				$user_uuid4 = $query_result[0]['user_uuid4'];
				$db = db_connect('userAccount', true);
				$prepared_query = $db->prepare(static function ($db) {
					$query = 'DELETE FROM user_account_login_session WHERE user_uuid4=?';
					return (new Query($db))->setQuery($query);
				}
				);
				$prepared_query->execute($user_uuid4);
				# https://stackoverflow.com/questions/6076214/why-is-php-generating-the-same-session-ids-everytime-in-test-environment-wamp
				session_regenerate_id(TRUE); 
				session_destroy();
				$data["success"] = true;
			}
		}

		return $data;
	}
}


?>