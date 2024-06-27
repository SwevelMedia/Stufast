<?php



namespace App\Models;



use CodeIgniter\Model;



class Users extends Model

{

	protected $table = 'users';

	protected $primaryKey = 'id';

	protected $DBGroup = 'default';

	protected $allowedFields = ['oauth_id', 'device_token', 'job_id', 'fullname', 'email', 'password', 'phone_number', 'address', 'province', 'regence', 'district', 'village', 'date_birth', 'profile_picture', 'role', 'activation_status', 'activation_code', 'updated_at', 'created_at'];



	function isAlreadyRegister($authid)

	{

		return $this->db->table('users')->getWhere(['oauth_id' => $authid])->getRowArray() > 0 ? true : false;
	}

	function isAlreadyRegisterByEmail($email)

	{

		return $this->db->table('users')->getWhere(['email' => $email])->getRowArray() > 0 ? true : false;
	}

	function updateUserData($userdata, $authid)

	{

		return $this->db->table("users")->where(['oauth_id' => $authid])->update($userdata);
	}

	function updateUserByEmail($userdata, $email)

	{

		return $this->db->table("users")->where(['email' => $email])->update($userdata);
	}

	public function updateUserById($data, $id)
	{
		return $this->builder()->set($data)->where('id', $id)->update();
	}

	function getUser($email)
	{
		return $this->db->table('users')->join('jobs', 'jobs.job_id=users.job_id', 'left')->getWhere(['email' => $email])->getRowArray();
	}

	function getUserByEmail($email)
	{
		return $this->builder()->select('id,fullname,email,date_birth,province,regence,district,village,address,phone_number,profile_picture,company,role,user_cv.about,user_cv.linkedin,user_cv.facebook,user_cv.instagram,users.job_id,job_name')->join('jobs', 'jobs.job_id=users.job_id', 'left')->join('user_cv', 'user_cv.user_id=users.id', 'left')->where('email', $email)->get()->getRowArray();
	}

	function getDataByEmail($email)
	{
		return $this->builder()->where('email', $email)->get()->getRowArray();
	}

	public function getTalent($userId = null)
	{
		if ($userId != null) {
			return $this->db->table($this->table)->select('users.fullname,users.email,users.phone_number,users.profile_picture,users.address,user_cv.*')->join('user_cv', 'user_cv.user_id = users.id')->where('role', 'member')->where('user_cv.user_id', $userId)->get()->getRowObject();
		}

		return $this->db->table($this->table)->select('users.fullname,users.email,users.phone_number,users.profile_picture,users.address,user_cv.*')->join('user_cv', 'user_cv.user_id = users.id')->where('role', 'member')->get()->getResultObject();
	}
}
