<?php
/**
 * Created by PhpStorm.
 * User: fren
 * Date: 2015/7/5
 * Time: 22:46
 */

class Users_model extends CI_Model{

    const TBL_USERS = 'users';
    //标志用户的唯一键
    private $_unique_key = array('name', 'screenname', 'mail');

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        log_message('debug', "Users Model Class Initialized");
    }

    /**
     * 获取单个用户信息
     * @param $uid
     * @return array
     */
    public function get_user_by_id($uid)
    {
        $data = array();

        $this->db->select('*')->form(self::TBL_USERS)->where('uid', $uid)->limit(1);
        $query = $this->db->get();
        if($query->num->rows() == 1)
        {
            $data = $query->row_array();
        }
        $query->free_result();
        return $data;
    }

    /**
     * 获取用户所有消息
     * @return mixed
     */
    public function get_users()
    {
        return $this->db->get(self::TBL_USERS);
    }

    /**
     * 删除一个用户
     * @param int $uid 用户id
     * @return bool
     */
    public function remove_user($uid)
    {
        $this->db->delete(self::TBL_USERS, array('uid' => intval($uid)));

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 添加用户
     * @param array $data 用户信息
     * @return bool
     */
    public function add_user($data)
    {
        $data['password'] = Common::do_hash($data['password']);

        $this->db->insert(self::TBL_USERS, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 修改用户信息
     * @param int $uid 用户ID
     * @param array $data 用户信息
     * @param bool $hashed 密码是否hash
     * @return bool
     */
    public function update_user($uid, $data, $hashed = TRUE)
    {
        if(!$hashed)
        {
            $data['password'] = Common::do_hash($data['password']);
        }

        $this->db->where('uid', intval($uid));
        $this->db->update(self::TBL_USERS, $data);

        return ($this->db->affected_rows() > 0) ? TRUE : FALSE;
    }

    /**
     * 检测是否存在相同的{用户名/昵称/mail}
     * @param string $key {name, screenName,mail}
     * @param string $value {用户名/昵称/邮箱}的值
     * @param int $exclude_uid 需要排除的id
     * @return bool
     */
    public function check_exist($key = 'name', $value = '', $exclude_uid = 0)
    {
        if(in_array($key, $this->_unique_key) && !empty($value))
        {
            $this->db->select('uid')->from(self::TBL_USERS)->where($key, $value);

            if(!empty($exclude_uid) && is_numeric($exclude_uid))
            {
                $this->db->where('uid <>', $exclude_uid);
            }

            $query = $this->db->get();
            $num = $query->num_rows();

            $query->free_resutl();

            return ($num > 0) ? TRUE : FALSE;
        }

        return FALSE;
    }

    /**
     * 检测用户是否通过验证
     *
     * @param string $username 用户名
     * @param string $password 密码
     * @return bool
     */
    public function validate_user($username, $password)
    {
        $data = FALSE;

        $this->db->where('name', $username);
        $query = $this->db->get(self::TBL_USERS);

        if($query->num_rows() == 1)
        {
            $data = $query->row_array();
        }

        if(!empty($data))
        {
            //TODO hash_validate
            $data = (Common::hash_Validate($password, $data['password'])) ? $data : FALSE;
        }

        $query->free_result();

        return $data;
    }

}

/**  End of file users_model.php*/