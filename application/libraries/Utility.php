<?php
/**
 * 实用函数
 * User: fren
 * Date: 2015/7/5
 * Time: 14:33
 */

class Utility {

    /**
     * CI 句柄
     * @var object
     */
    private $_CI;

    /**
     * 构造函数
     */
    public function __construct()
    {
        $this->_CI = & get_instance();

        log_message('debug', 'STBblog: Utility class initialized.');
    }

    /**
     * 获取激活的插件
     * @return array
     */
    public function get_active_plugins()
    {

        $active_plugins = setting_item('active_plugins');

        if(empty($active_plugins))
        {
            return array();
        }

        $plugins = unserialize($active_plugins);//反序列化
        return $plugins ? (is_array($plugins) ? $plugins : array($plugins)) : array();//返回对象

    }

    /**
     * 检查博客状态
     */
    public function check_blog_status()
    {
        if(setting_item('blog_status'))
        {
            if('off' == setting_item('blog_status'))
            {
                $title = sprintf('%s - Site Close Notice', setting_item('blog_title'));
                $heading = sprintf('%s is closed by ites administrator TEMPORARILY.', setting_item('blog_title'));
                $message = sprintf('Reason: %s', setting_item('offline_reason') ? setting_item('offline_reason') : 'n/a');

                echo <<<EOT
<html xmlns="http://www.w3.org/1999/xhtml" > <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><title>{$title}</title><style type="text/css">body{padding-right: 32px;margin-top: 40px;padding-left: 32px;font-size: 13px;background: #eee;padding-bottom: 32px;color: #000;padding-top: 32px;font-family:Verdana;}#main{border-right: #bbb 1px solid;border-top: #bbb 1px solid;background: #fff;padding-bottom: 32px;border-left: #bbb 1px solid;width: 550px;padding-top: 20px;border-bottom: #bbb 1px solid;text-align:left;padding-left:60px;padding-right:50px;}div#heading{padding-right: 0px;padding-left: 0px;font-weight: bold;font-size: 120%;padding-bottom: 15px;margin: 0px;color: #904;padding-top: 0px;font-family: arial;}h2{padding-right: 0px;padding-left: 0px;font-weight: bold;font-size: 105%;padding-bottom: 0px;margin: 0px 0px 8px;text-transform: uppercase;color: #999;padding-top: 0px;border-bottom: #ddd 1px solid;font-family: "trebuchet ms" , "" lucida grande "" , verdana, arial, sans-serif;}p{padding-right: 0px;padding-left: 0px;padding-bottom: 6px;margin: 0px;padding-top: 6px;}a:link{color: #002c99;font-size: 12px;}a:visited{color: #002c99;font-size: 12px;}a:hover{color: #cc0066;background-color: #f5f5f5;text-decoration: underline;font-size: 12px;}</style> </head> <body> <div style="width:100%;"><div align="center"> <div id="main"><div id="heading">{$heading}</div>{$message}</div></div> </div> </body></html>
EOT;
                exit();


            }
        }
    }

    /**
     * 检查PHP版本
     */
    public function  check_compatibility()
    {
        if(version_compare(PHP_VERSION, '5.0.0', '<'))
        {
            die('Sorry, STBlog is for PHP5 and above ONLY.  The PHP version installed on your server is lower than that.  Time to upgrade?');
        }
    }

    /**
     * 清除缓存文件
     */
    public function clear_file_cache()
    {
        $this->_CI->load->helper('file');

        $path = $this->_CI->config->item('cache_path');

        delete_files($path);

        @copy( APPPATH. 'index.html', $this->_CI->config->item('cache_path').'/index.html');
    }

    /**
     * 清除数据库缓存
     */
    public function clear_db_cahce()
    {
        $this->_CI->load->helper('file');

        delete_files(APPPATH . 'dbcache' . DIRECTORY_SEPARATOR , TRUE);

        @copy(APPPATH . 'index.html', APPPATH . 'dbcache/'.'index.html');
    }

}

/* End of file Utiliy.php*/