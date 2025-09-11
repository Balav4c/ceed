<?php 
namespace App\Models\admin;

use CodeIgniter\Model;

class LoginModel extends Model {

	public function __construct() {
		$this->db = \Config\Database::connect();
	}
	public function checkLoginUser($email, $password) {
		
		return $this->db->query("select * from user where us_Email = '".$email."' and us_Password = '".$password."'")->getRow();

	}
}
?>
