<?php
/**
 * Created by PhpStorm.
 * User: fren
 * Date: 2015/7/7
 * Time: 21:34
 */

class Login extends CI_Controller
{
    //传递到对应视图的数据
    private $_data;
    //Refer
    public $referrer;

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();

        $this->load->library('auth');
        $this->load->library('form_validation');
        $this->load->model('users_model', 'users');

        $this->_check_referrer();

        $this->_data['page_title'] = '登录';
    }

    /**
     * 检查referrer
     */
    public function _check_referrer()
    {
        $ref = $this->input->get('ref', TRUE);
        $this->referrer = (!empty($ref)) ? $ref : '/admin/dashboard';
    }

    /**
     *登录首页
     */
    public function index()
    {
        if($this->auth->hasLogin())
        {
            redirect($this->referrer);
        }

        $this->form_validation->set_rules('name', '用户名', 'required|min_length[2]|trim');
        $this->form_validation->set_rules('password', '密码', 'required|trim');
        $this->form_validation->set_error_delimiters('<span class="label alert radius">', '</span>');

        $this->form_validation->set_message('required', '%s不能为空');
        $this->form_validation->set_message('min_length', '%s长度至少2两个字符');

        if($this->form_validation->run() === FALSE)
        {
            $this->load->view('admin/login', $this->_data);
        }
        else
        {
            $user = $this->users->validate_user(
                $this->input->post('name', TRUE),
                $this->input->post('password', TRUE)
            );

            //用户信息不为空
            if(!empty($user))
            {
                if($this->auth->process_login($user))
                {
                    redirect($this->referrer);
                }
            }
            else//用户信息为空
            {
                sleep(1);

                $this->session->set_flashdata('login_error', 'TRUE');
                //$this->form_validation->error_string = '用户名或密码无效';
                $this->load->view('admin/login', $this->_data);
            }

        }
    }

    /**
     * 登出
     */
    public function logout()
    {
        $this->auth->process_logout();
    }
}

/* End of file login.php*/