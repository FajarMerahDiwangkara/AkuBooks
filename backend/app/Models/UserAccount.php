<?php
namespace App\Models;

# note: does not extends nor use codeigniter Model class

class UserAccount {
	# https://codeigniter.com/user_guide/models/model.html#models
	private $uuid4;
	private $hashed_password;
	private $email_address;
	private $name;
	private $username;
	private $email_address_verified;

	public static function generate_uuid4() 
	{
		return Cryptography::generate_uuid4();
	}

	public function set_uuid4($uuid4)
	{
		assert(is_string($uuid4));
		$uuid4 = $this->uuid4;
	}

	public function get_uuid4()
	{
		return $this->uuid4;
	}

	# https://stackoverflow.com/questions/9166914/using-default-arguments-in-a-function

	public static function hash_password($passwordPlaintext) 
	{
		assert(is_string($passwordPlaintext));
		# yes password hash change everytime called, same password may produce different hash,
		# it is intented to be like that.
		# all information needed to verify a plaintext and a ciphertext is already
		# available in the ciphertext.
		# use password_verify() to verify a password and a plaintext.
		$passwordCiphertext = password_hash($passwordPlaintext, PASSWORD_DEFAULT);
		return $passwordCiphertext;
	}

	public static function verify_password($passwordPlaintext, $passwordCiphertext) {
		assert(is_string($passwordPlaintext));
		assert(is_string($passwordCiphertext));
		return password_verify($passwordPlaintext, $passwordCiphertext);
	}

	public function set_hashed_password($password, $hash=true){
		assert(is_string($password));
		if($hash == true) {
			$this->hashed_password = hash_password($password);
		} else {
			$this->hashed_password = $password;
		}
	}

	public function get_hashed_password()
	{
		return $this->hashed_password;
	}

	public function set_email_address($email_address) {
		assert(is_string($email_address));
		# best way to verify whether email address is valid or not, 
		# is to try send verification email to the email address 
		# and ask user to verify the email address.
		$this->email_address = $email_address;
	}

	public function get_email_address() {
		return $this->email_address;
	}

	public function set_name($name) {
		assert(is_string($name));
		$this->name = $name;
	}

	public function get_name() {
		return $this->name;
	}

	public function set_username($username) {
		assert(is_string($username));
		$this->username = $username;
	}

	public function get_username() {
		return $this->username;
	}

	public function set_email_address_verified($bool) {
		$this->email_address_verified = $bool;
	}

	public function get_email_address_verified() {
		return $this->email_address_verified;
	}

	public static function verify_if_password_strong($passwordPlaintext) {
		assert(is_string($passwordPlaintext));
		if($passwordPlaintext == null || !is_string($passwordPlaintext) || len($passwordPlaintext) < 8) {
			return false;
		} else {
			return true;
		}
	}


}
?>