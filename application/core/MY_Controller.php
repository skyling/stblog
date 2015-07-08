<?php
/**
 * Created by PhpStorm.
 * User: fren
 * Date: 2015/7/5
 * Time: 12:20
 */


/**
 * 前台父类控制器
 * Class ST_Controller
 */
class ST_Controller extends CI_Controller{

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->utility->check_compatibility();//检查PHP版本
        $this->utility->check_blog_status();//检查博客状态

        //设置主题
        $this->load->theme = setting_item('current_theme');

        //前台页面均使用主题皮肤功能
        $this->load->switch_theme_on();
    }

    protected function load_theme_view($view, $vars = array(), $cached = TRUE, $return = FALSE)
    {
        //加载对应主题下的view
        if(file_exists(FCPATH . ST_THEMES_DIR . DIRECTORY_SEPARATOR . setting_item('current_theme') . DIRECTORY_SEPARATOR . $view . '.php'))
        {
            echo $this->load->view($view, $vars, $return);
        }
        else
        {
            show_404();
        }

        // 是否开启缓存
        if(1 == intval(setting_item('cache_enabled')) && $cached)
        {
            $cache_expired = setting_item('cache_expire_time');
            $cache_expired = ($cache_expired && is_numeric($cache_expired)) ? intval($cache_expired) : 60;

            //开启缓存
            $this->output->cache($cache_expired);
        }
    }


}

class ST_Auth_Controller extends CI_Controller{
    function __construct()
    {
        parent::__construct();

        //加载验证库
        $this->load->library('auth');

        //检查是否登录
        if( ! $this->auth->hasLogin())
        {
            redirect('admin/login?ref=' . urlencode($this->uri->uri_string()));
        }
        //加载后台控制器公共库
        $this->load->library('form_validation');
        $this->load->library('user');
        //加载后台控制器公共模型
        $this->load->model('users_model');
        //加载后台控制器helper

        //后台管理页面,不使用皮肤
        $this->load->switch_theme_off();

    }
}


/* End of file MY_Controller.php */