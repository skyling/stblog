<?php
/**
 * 控制台控制器
 * User: fren
 * Date: 2015/7/8
 * Time: 0:09
 */

class Dashboard extends ST_Auth_Controller{
    //传递到对应视图的数据
    private $_data = array();

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();

        $this->_data['page_title'] = '网页概要';
        $this->_data['parentPage'] = 'dashboard';
        $this->_data['currentPage'] = 'dashboard';
    }

    public function index()
    {
        //权限确认
        $this->auth->exceed('contributor');

        $my_recent_posts = $this->posts_model->get_post('post', 'public', $this->user->uid, 5, 0);
    }
}