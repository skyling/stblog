<?php
/**
 * 元数据操作Model
 * User: fren
 * Date: 2015/7/8
 * Time: 22:45
 */

class Metas_model extends CI_Model{
    const TBL_METAS = 'metas';
    const TBL_RELATIONSHIPS = 'relationships';
    const TBL_POST = 'posts';

    //内容类型: 分类/标签
    private $_type = array('category', 'tag');
    //文章元数据
    public $metas = NULL;

    function __construct()
    {
        parent::__construct();
        $this->load->database();

        log_message('debug', 'SKBLOG: Metas Model Class Initialized');
    }

    /**
     * 根据post id获取元数据列表
     * 本函数的目的是一次性独处文章所有的metas,然后通过$this->_metas_model->metas['YOUR_KEY']读取对应的meta，比如category
     * @param int $pid 内容id
     * @param bool $return 是否为返回模式
     * @return array
     */
    public function get_metas($pid = 0, $return = FALSE)
    {
        //清空metas数组
        $this->metas = NULL;

        $metas = array();
        //读取DB
        if(!empty($pid))
        {
            $this->db->select(self::TBL_METAS.'.*,'.self::TBL_RELATIONSHIPS.'.*');
            $this->db->join(self::TBL_RELATIONSHIPS, self::TBL_RELATIONSHIPS.'.mid = '.self::TBL_METAS . '.mid AND '. self::TBL_RELATIONSHIPS.'.pid='.intval($pid), 'INNER');
        }

        $query = $this->db->get(self::TBL_METAS);

        if($query->num_rows() > 0)
        {
            $metas = $query->result_array();
        }

        $query->free_result();
        //如果为返回模式
        if($return)
        {
            return $metas;
        }
        //初始化metas数组
        foreach($this->_type as $type)
        {
            $this->metas[$type] = array();
        }

        if(!empty($metas))
        {
            //根据不同的metas类型自动push对应的数组
            foreach($metas as $meta)
            {
                foreach($this->_type as $type)
                {
                    if($type == $meta['type'])
                    {
                        array_push($this->metas[$type], $meta);
                    }
                }
            }
        }
    }
}