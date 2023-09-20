<?php
namespace App\Models;

# note: does not extends nor use codeigniter Model class

class Cryptography {
	public static function generate_uuid4() 
	{
		# https://stackoverflow.com/questions/2040240/php-function-to-generate-v4-uuid
        /* 32 random HEX + space for 4 hyphens */
		$out = bin2hex(random_bytes(18));

        $out[8]  = "-";
        $out[13] = "-";
        $out[18] = "-";
        $out[23] = "-";

        /* UUID v4 */
        $out[14] = "4";
    
        /* variant 1 - 10xx */
        $out[19] = ["8", "9", "a", "b"][random_int(0, 3)];

        return $out;
	}

    public static function validate_uuid4($uuid4) 
    {
        assert(is_string($uuid4));
        # https://stackoverflow.com/questions/12808597/php-verify-valid-uuid
        if (strlen($uuid4) != 36 || 
        (preg_match('/^[0-9a-f]{8}-[0-9a-f]{4}-4[0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/', $uuid4) !== 1)) 
        {
            return false;
        } else {
            return true;
        }
    }

    public static function generate_csrf_token($session_id) {

    }
}
?>