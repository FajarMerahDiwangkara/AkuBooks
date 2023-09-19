<?php
namespace App\Models;

# note: does not extends nor use codeigniter Model class

class UserAccount {
	# https://codeigniter.com/user_guide/models/model.html#models

	# https://codeigniter.com/userguide3/general/styleguide.html
	private $_uuid4;
	private $_password_plaintext;
	private $_email_address;
	private $_name;
	private $_username;
	private $_email_address_verified;

	public static function generate_uuid4() 
	{
		return Cryptography::generate_uuid4();
	}

	public static function validate_uuid4($uuid4)
	{
		assert(is_string($uuid4));
		return Cryptography::validate_uuid4($uuid4);
	}

	public function set_uuid4($uuid4)
	{
		assert(is_string($uuid4));
		$this->_uuid4 = $uuid4;
	}

	public function get_uuid4()
	{
		return $this->_uuid4;
	}

	# https://stackoverflow.com/questions/9166914/using-default-arguments-in-a-function

	public static function hash_password($password_plaintext) 
	{
		assert(is_string($password_plaintext));
		# yes password hash change everytime called, same password may produce different hash,
		# it is intented to be like that.
		# all information needed to verify a plaintext and a ciphertext is already
		# available in the ciphertext.
		# use password_verify() to verify a password and a plaintext.
		$password_ciphertext = password_hash($password_plaintext, PASSWORD_DEFAULT);
		return $password_ciphertext;
	}

	public static function verify_password($password_plaintext, $password_ciphertext) {
		assert(is_string($password_plaintext));
		assert(is_string($password_ciphertext));
		return password_verify($password_plaintext, $password_ciphertext);
	}

	public function set_password_plaintext($password_plaintext, $hash=true){
		assert(is_string($password_plaintext));
		$this->_password_plaintext = $password_plaintext;
		
	}

	public function get_password_plaintext()
	{
		return $this->_password_plaintext;
	}

	public function set_email_address($email_address) {
		assert(is_string($email_address));
		# best way to verify whether email address is valid or not, 
		# is to try send verification email to the email address 
		# and ask user to verify the email address.
		$this->_email_address = $email_address;
	}

	public function get_email_address() {
		return $this->_email_address;
	}

	public function set_name($name) {
		assert(is_string($name));
		$this->_name = $name;
	}

	public function get_name() {
		return $this->_name;
	}

	public function set_username($username) {
		assert(is_string($username));
		$this->_username = $username;
	}

	public function get_username() {
		return $this->_username;
	}

	public function set_email_address_verified($bool) {
		$this->_email_address_verified = $bool;
	}

	public function get_email_address_verified() {
		return $this->_email_address_verified;
	}

	public static function validate_name($name) {
		assert(is_string($name));
		if(strlen(trim($name)) == 0) {
			return false;
		}
		# TODO

		return true;
	}

	public static function validate_username($username) {
		assert(is_string($username));
		# TODO
		if(strlen(trim($username)) == 0) {
			return false;
		}

		return true;
	}


	public static function validate_password_plaintext_good($password_plaintext) {
		assert(is_string($password_plaintext));
		if(strlen($password_plaintext) < 8) {
			return false;
		} else {
			return true;
		}
	}


}
?>