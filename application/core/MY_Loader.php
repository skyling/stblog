<?php
/**
 * Created by PhpStorm.
 * User: fren
 * Date: 2015/7/5
 * Time: 17:34
 */

/**
 * 重写loader库,支持现有框架下的博客皮肤系统
 * Class MY_Loader
 */
class MY_Loader extends CI_Loader{

    public $theme = 'default';//当前系统皮肤

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * 打开皮肤功能
     */
    public function switch_theme_on()
    {
        $this->_ci_view_paths = FCPATH . ST_THEMES_DIR .DIRECTORY_SEPARATOR .$this->theme .DIRECTORY_SEPARATOR;
    }

    /**
     * 关闭皮肤功能
     */
    public function switch_theme_off()
    {
        //DO NOTHING
    }
}