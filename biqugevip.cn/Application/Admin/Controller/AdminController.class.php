<?php

namespace Admin\Controller;

use Think\Controller;
class AdminController extends Controller
{
    public $setting;
    public $category;
    public $dataarea;
    public $defaultdir;
    public function __construct()
    {
        parent::__construct();
        $xzv_0 = strtolower(ACTION_NAME);
        if (!in_array($xzv_0, array('login', 'logout'))) {
            if (!checkadmin()) {
                $xzv_1 = str_replace(array('extend', 'Extend'), 'index', U('login'));
                header('Location: ' . $xzv_1);
                die;
            }
            if (in_array(strtolower(ACTION_NAME), array('article', 'dataarea', 'index', 'searchlog', 'advertise', 'spider'))) {
                if (!verify_license()) {
                    die('&#116;&#104;&#105;&#115;&#32;&#100;&#111;&#109;&#97;&#105;&#110;&#32;&#105;&#115;&#32;&#110;&#111;&#116;&#32;&#97;&#108;&#108;&#111;&#119;&#101;&#100;&#33;');
                }
            }
        }
        $this->setting = F('setting');
        $this->category = F('category');
        $this->dataarea = F('dataarea');
        $this->defaultdir = $this->category['default']['dir'];
        $xzv_3[$xzv_0] = ' class="am-active"';
        if (in_array(strtolower($xzv_0), array('category', 'dataarea', 'searchlog', 'tags', 'advertise', 'seowords', 'advertise', 'pickers'))) {
            $xzv_2 = 'am-in';
            $this->assign('amin', $xzv_2);
        }
        $this->assign('active', $xzv_3);
    }
}