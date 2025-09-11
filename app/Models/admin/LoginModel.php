<?php 
namespace App\Models\admin;

use CodeIgniter\Model;

class LoginModel extends Model {

	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function checkLoginUser($email, $password) {
		
		return $this->db->query("select * from user where email = '".$email."' and password = '".$password."'")->getRow();

	}
}
?>
