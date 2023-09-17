<?php
namespace App\Models;
use CodeIgniter\Model;
class Cryptography extends Model {
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
}
?>