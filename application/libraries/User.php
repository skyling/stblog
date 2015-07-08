<?php
/**
 * Created by PhpStorm.
 * User: fren
 * Date: 2015/7/8
 * Time: 21:50
 */

class User {
    //用户信息
    private $_user = array();
    //用户id
    public $uid = 0;
    //用户登录名
    public $name = 0;
    //用户邮箱
    public $mail = '';
    //用户昵称
    public $screenName = '';
    //创建时间
    public $created = 0;
    //最后活跃时间
    public $activated = 0;
    //上次登录时间
    public $logged = 0;
    //用户所属组
    public $group = 'visitor';
    //本次登录token
    public $tocken = '';
    //CI句柄
    private $_CI;

    public function __construct()
    {
        $this->_CI = & get_instance();
        $this->_user = unserialize($this->_CI->session->userdata('user'));

        if(!empty($this->_user))
        {
            $this->uid = $this->_user['uid'];
            $this->name = $this->_user['name'];
            $this->mail = $this->_user['mail'];
            $this->screenName = $this->_user['screenName'];
            $this->created = $this->_user['created'];
            $this->activated = $this->_user['activated'];
            $this->logged = $this->_user['logged'];
            $this->group = $this->_user['group'];
            $this->tocken = $this->_user['token'];
        }

        log_message('debug', 'SKBLOG: user Domian library Class Initialized');
    }

}

/* End of file User.php*/