<?php
/**
 * 博客公共类库
 * User: fren
 * Date: 2015/7/5
 * Time: 16:30
 */
class Common {
    //默认不解析的标签
    const LOCKED_HTML_TAG = 'code|pre|script';
    //需要去除内部换行的标签
    const ESCAPE_HTML_TAG = 'div|blockquote|object|pre|table|fieldset|tr|th|td|li|ol|ul|h[1-6]';
    //元素标签
    const ELEMENT_HTML_TAG = 'div|blockquote|pre|td|li';
    //布局标签
    const GRID_HTML_TAG = 'div|blockquote|pre|code|script|table|ol|ul';
    //独立段落标签
    const PARAGRAPH_HTML_TAG = 'div|blockquote|pre|code|script|table|fieldset|ol|ul|h[1-6]';

    //锁定的代码块
    private static $_lockedBlocks = array('<p></p>' => '');

    /**
     * 锁定标签回调函数
     * @param array $matches 匹配的值
     * @return string
     */
    public static function __lock_html(array $matches)
    {
        $guid = '<code>' . uniqid(time()) . '</code>';
        self::$_lockedBlocks[$guid] = $matches[0];

        return $guid;
    }

    public static function split_by_count($count)
    {
        $sizes = func_get_args();
        array_shift($sizes);

        foreach($sizes as $size)
        {
            if($count < $size)
            {
                return $size;
            }
        }
        return 0;
    }

    public static function do_hash($string, $salt = NULL)
    {
        if(NULL === $salt)
        {
            $salt = substr(md5(uniqid(rand(), true)), 0 , ST_SALT_LENGTH);
        }
        else
        {
            $salt = substr($salt, 0, ST_SALT_LENGTH);
        }

        return $salt . sha1($salt . $string);
    }

    /**
     * 判断hash值是否相等
     *
     * @param string $source 源字符串
     * @param string $target 目标字符串
     * @return bool
     */
    public static function hash_Validate($source, $target)
    {
        return (self::do_hash($source, $target) == $target);

    }
}
/**
 * 获取用户配置--从setting数据表中
 * @return array
 */
function & get_settings()
{
    static $user_settings;
    if(!isset($user_stings))
    {
        $CI = & get_instance();

        $CI->load->library('stcache');

        $settings = $CI->stcache->get('settings');

        if(FALSE == $settings)
        {
            $query = $CI->db->get('settings');

            foreach($query->result() as $row)
            {
                $settings[$row->name] = $row->value;
            }

            $query->free_result();

            $CI->stcache->set('settings', $settings);
        }

        $user_settings[0] = &$settings;
    }

    return $user_settings[0];
}

/**
 * 获取一个选项
 * @param $item
 * @return bool
 */
function setting_item($item)
{
    static $setting_item = array();

    if(!isset($setting_item[$item]))
    {
        $settings = &get_settings();

        if(!isset($settings[$item]))
        {
            return FALSE;
        }

        $setting_item[$item] = $settings[$item];
    }

    return $setting_item[$item];
}