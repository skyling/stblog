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
        //最新日志文章5条
        $my_recent_posts = $this->posts_model->get_post('post', 'public', $this->user->uid, 5, 0);

        if($my_recent_posts->num_rows() > 0)
        {
            foreach ($my_recent_posts->result() as $recent_post)
            {
                $this->metas_model->get_metas($recent_post->pid);
                //TODO format_metas metas
                $recent_post->categories = Common::format_metas($this->metas_model->metas['category']);;
            }

            //最新评论5条 TODO get_conments_by_owner
            $my_recent_comments = $this->comments_model->get_comments_by_owner('comment', 'approved', $this->user->uid, 5, 0);

            if($my_recent_comments->num_rows() > 0)
            {
                foreach($my_recent_comments->result() as $recent_comment)
                {
                    //TODO get_post_by_id
                    $recent_comment->parent_post = $this->posts_model->get_post_by_id('pid', $recent_comment->pid);
                }

                $this->_data['my_recent_posts'] = $my_recent_posts;
                $this->_data['my_recent_comments'] = $my_recent_comments;

                $this->load->vieww('admin/dashboard', $this->_data);
            }
        }
    }
}

/* End of file dashboard.php */