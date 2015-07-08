<?php
/**
 * key-vavle 文件缓存
 * User: fren
 * Date: 2015/7/5
 * Time: 16:54
 */

class Stcache {
    //缓存路径
    private $_cahce_path;
    //缓存过期时间,单位是秒second
    private $_cache_exprie;

    public function __construct()
    {
        $this->_cahce_path = APPPATH . ST_DB_CACHE_DIR . DIRECTORY_SEPARATOR;
        $this->_cache_exprie = 1200;
    }

    /**
     * 缓存文件名
     * @param $key
     * @return string
     */
    private function _file($key)
    {
        return $this->_cahce_path . md5($key);
    }

    /**
     * 设置缓存
     * @param string $key 缓存的唯一键
     * @param string $data 缓存的内容
     * @return bool
     */
    public function set($key, $data)
    {
        $value = serialize($data);

        $file = $this->_file($key);

        return write_file($file, $value);
    }

    /**
     * 获取缓存文件
     * @param string $key 缓存的唯一键
     * @return bool|mixed
     */
    public function get($key)
    {
        $file = $this->file($key);

        // 文件不存在或者不可写
        if(! file_exists($file) || !is_really_writable($file))
        {
            return FALSE;
        }

        if( time() < (filemtime($file) + $this->_cache_exprie))
        {
            $data = @file_get_contents($file);

            if(FALSE !== $data)
            {
                return unserialize($data);
            }

            return FALSE;
        }

        //缓存文件过期,删除
        @unlink($file);
        return FALSE;
    }
}

/* End of file Stcache.php */