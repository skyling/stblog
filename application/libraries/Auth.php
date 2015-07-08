<?php
/**
 * 控制用户登录和登出,以及一个简单的权限控制ACL实现
 * User: fren
 * Date: 2015/7/5
 * Time: 18:01
 */

class Auth {
    //用户
    private $_user = array();
    //是否登录
    private $_hasLogin = NULL;
    //CI句柄
    private $_CI;
    //用户组
    public $groups = array(
        'administrator' => 0,//管理员
        'editor'        => 1,//编辑者
        'contributor'   => 2,//贡献者
    );

    /**
     * 构造函数
     */
    function __construct()
    {
        $this->_CI = & get_instance();

        $this->_CI->load->model('users_model');

        $this->_user = unserialize($this->_CI->session->userdata('user'));

        log_message('debug', 'STBLOG: Authentication library Class Initialized');
    }

    /**
     * 判断用户是否已经登录
     * @return bool|null
     */
    public function hasLogin()
    {
        //检查session,并与数据库里的数据相匹配
        if( NULL !== $this->_hasLogin)
        {
            return $this->_hasLogin;
        }
        else
        {
            if( ! empty($this->_user) && NULL !== $this->_user['uid'])
            {
                $user = $this->_CI->users_model->get_user_by_id($this->_user['uid']);

                if($user && $user['token'] == $this->_user['token'])
                {
                    $user['activated'] = time();
                    $this->_CI->users_model->update_user($this->_user['uid'], $user);
                    return ($this->_hasLogin = TRUE);
                }
            }
            return ($this->_hasLogin = FALSE);
        }
    }

    /**
     * 判断用户权限
     * @param string $group 用户组
     * @param bool $return 是否为返回模式
     * @return bool|void
     */
    public function exceed($group, $return = FALSE)
    {
        //权限验证通过
        if(array_key_exists($group, $this->groups) && $this->groups[$this->_user['group']] <= $this->groups[$group])
        {
            return TRUE;
        }

        // 权限为通过, 同时为返回模式
        if($return){
            return FALSE;
        }

        show_error('禁止访问: 你的权限不足');
        return;
    }

    /**
     * 处理用户登出
     */
    public function process_logout()
    {
        $this->_CI->session->sess_destroy();
        redirect('admin/login');
    }

    public function process_login($user)
    {
        //获取用户信息
        $this->_user = $user;
        //每次登陆时需要更新的数据
        $this->_user['logged'] = now();
        $this->_user['activated'] = $user['logged'];
        //每登陆一次更新token
        $this->_user['token'] = sha1(now().rand());

        if($this->_CI->users_model->update_user($this->_user['uid'], $this->_user))
        {
            //设置session
            $this->_set_session();
            $this->_hasLogin = TRUE;

            return TRUE;
        }
        return FALSE;
    }

    /**
     * 设置session
     */
    private function _set_session()
    {
        $session_data = array('user'=>serialize($this->_user));
        $this->_CI->session->set_userdata($session_data);
    }
}