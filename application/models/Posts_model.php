<?php
/**
 *  内容操作Model
 * User: fren
 * Date: 2015/7/8
 * Time: 22:09
 */

class Posts_model extends CI_Model{
    const TBL_POSTS = 'posts';
    const TBL_MEATS = 'metas';
    const TBL_RELATIONSHIPS = 'relationships';
    const TBL_CONMMETS = 'comments';

    //内容类型 日志/附件/独立页面
    private $_post_type = array('post', 'attachment', 'page');
    //内容状态 发布/草稿/未归档/等待审核
    private $_post_status = array('public', 'draft', 'unattached', 'attached', 'waiting');
    //内容的唯一栏 pid/slug
    private $_post_unique_field = array('pid', 'slug');

    /**
     * 构造函数
     */
    function __construct()
    {
        parent::__construct();
        $this->load->database();
        log_message('debug', 'SKBLOG: Posts Model Class Initialized');
    }

    /**
     * 获取内容列表
     * @param string $type 内容类型
     * @param string $status 内容状态
     * @param int $author_id 作者ID
     * @param int $limit 条数
     * @param int $offset 偏移量
     * @param int $category_filter 需要过滤的栏目ID
     * @param string $title_filter 需要过滤的标题关键字
     * @param bool $feed_filer 是否显示在feed里面
     * @return mixed 内容列表信息
     */
    public function get_posts($type = 'post', $status = 'public', $author_id = NULL, $limit = NULL,  $offset = NULL, $category_filter = 0, $title_filter = '', $feed_filer = FALSE)
    {
        $this->db->select('post.*, users.screenName');
        $this->db->join('users', 'users.uid = posts.authorId');

        if($type && in_array($type, $this->_post_type))
        {
            $this->db->where('posts.type', $type);
        }

        if($status && in_array($status, $this->_post_status))
        {
            $this->db->where('posts.status', $status);
        }

        if(!empty($author_id))
        {
            $this->db->where('post.authorId', intval($author_id));
        }

        if(!empty($category_filter))
        {
            $this->db->join('relationships', 'posts.pid = relationships.pid', 'left' );
            $this->db->where('relationships.mid', intval($category_filter));
        }

        if(!empty($title_filter))
        {
            $this->db->like('posts.title', $title_filter);
        }

        if($feed_filer)
        {
            $this->db->where('allowFeed', 1);
        }

        $this->db->order_by('posts.created', 'DESC');

        if($limit && is_numeric($limit))
        {
            $this->db->limit($limit);
        }

        if($offset && is_numeric($offset))
        {
            $this->db->offset(intval($offset));
        }

        return $this->db->get(self::TBL_POSTS);
    }

    public function get_post_max_id()
    {

    }
}