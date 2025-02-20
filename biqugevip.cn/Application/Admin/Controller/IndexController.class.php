<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;
use Think\Controller;
class IndexController extends AdminController
{
    public function index()
    {
        $xzv_150 = file_get_contents(CONF_PATH . 'ver.txt');
        if (I('action') == 'getupdate') {
            $xzv_33 = file_get_contents('http://vip.0rg.pw/version/' . NOW_TIME);
            $xzv_60 = json_decode($xzv_33, true);
            $xzv_72 = $xzv_60['ver'];
            if ($xzv_72 && $xzv_72 != $xzv_150) {
                echo htmlspecialchars_trans($xzv_60['url'], 'pick') . '#from=' . $xzv_72 . '&to=' . $xzv_150;
                die;
            } else {
                die;
            }
        }
        $xzv_71 = basestat();
        $xzv_118 = array('ver' => $xzv_150, 'os' => explode(' ', php_uname()), 'soft' => $_SERVER['SERVER_SOFTWARE'], 'php' => PHP_VERSION);
        $this->assign('counts', $xzv_71);
        $this->assign('serverinfo', $xzv_118);
        $this->display();
    }
    public function logout()
    {
        session('adminname', null);
        session('adminpwd', null);
        $this->success('成功退出，1秒后转向登录页面！', U('login'));
    }
    public function login()
    {
        if (!checkadmin()) {
            $xzv_67 = I('param.');
            $xzv_18 = $xzv_67['action'];
            if ($xzv_18 == 'login') {
                $xzv_51 = F('setting');
                if (strlen($xzv_67['adminname']) > 30 || strlen($xzv_67['password']) > 30) {
                    $this->error('用户名或密码不能超过30位！');
                }
                if (!$xzv_67['password'] || !$xzv_67['adminname']) {
                    $this->error('用户名或密码不能为空！');
                }
                $xzv_130 = M('settingmeta');
                $xzv_122 = $xzv_130->where("meta_key='adminname'")->getField('meta_value');
                $xzv_15 = $xzv_130->where("meta_key='adminpwd'")->getField('meta_value');
                if (substr(md5($xzv_67['password']), 4, 18) == $xzv_15 && $xzv_67['adminname'] == $xzv_122) {
                    session('adminname', $xzv_67['adminname']);
                    session('adminpwd', substr(md5($xzv_67['password']), 4, 18));
                    $this->success('成功登录，1秒后转向管理主页！', U('index'));
                } else {
                    $this->error('用户名或密码错误!!!');
                }
            } else {
                C('LAYOUT_ON', false);
                $this->display();
            }
        } else {
            $this->redirect(U('index'));
        }
    }
    public function setting()
    {
        $xzv_129 = $this->setting;
        $xzv_49 = htmlspecialchars_trans(F('flink'), 'decode');
        $xzv_123 = htmlspecialchars_trans(F('flink_wap'), 'decode');
        $this->assign('flink', $xzv_49);
        $this->assign('flink_wap', $xzv_123);
        $xzv_48 = I('param.');
        $xzv_47 = $xzv_48['action'];
        if (!$xzv_47) {
            $xzv_55 = M('settingmeta');
            $xzv_125 = $xzv_55->where("meta_key='adminname'")->getField('meta_value');
            $xzv_129['seo']['weburl'] = $xzv_129['seo']['weburl'] ? $xzv_129['seo']['weburl'] : '/';
            $xzv_129['seo']['core_filter'] = htmlspecialchars_trans($xzv_129['seo']['core_filter'], 'decode');
            $xzv_129['seo']['repick_sign'] = htmlspecialchars_trans($xzv_129['seo']['repick_sign'], 'decode');
            $this->assign('setting', $xzv_129);
            $this->assign('adminname', $xzv_125);
            $this->display();
        } elseif ($xzv_47 == 'admin') {
            if (!$xzv_48['password'] || !$xzv_48['adminname']) {
                $this->error('用户名或密码不能为空！');
            }
            $xzv_55 = M('settingmeta');
            $xzv_125 = $xzv_48['adminname'];
            $xzv_9 = substr(md5($xzv_48['password']), 4, 18);
            $xzv_55->where("meta_key='adminname'")->setField('meta_value', $xzv_125);
            $xzv_55->where("meta_key='adminpwd'")->setField('meta_value', $xzv_9);
            session('adminname', null);
            session('adminpwd', null);
            $this->success('管理员信息更新成功，请重新登录！', U('login'));
        } elseif ($xzv_47 == 'flink') {
            $xzv_88 = htmlspecialchars_trans($xzv_48['flink'], 'encode');
            F('flink', $xzv_88);
            $xzv_7 = htmlspecialchars_trans($xzv_48['flink_wap'], 'encode');
            F('flink_wap', $xzv_7);
            $this->success('友情链接更新成功！');
        } elseif ($xzv_47 == 'save') {
            $xzv_48['statcode'] = I('post.statcode', '', 'htmlspecialchars_decode');
            $xzv_48['core_filter'] = htmlspecialchars_trans(I('post.core_filter', '', 'htmlspecialchars_decode'));
            $xzv_48['repick_sign'] = htmlspecialchars_trans(I('post.repick_sign', '', 'htmlspecialchars_decode'));
            $xzv_129['seo'] = array('debug' => $xzv_48['debug'], 'webdir' => $xzv_48['webdir'], 'pcdomain' => $xzv_48['pcdomain'], 'mobiledomain' => $xzv_48['mobiledomain'], 'pctheme' => $xzv_48['pctheme'], 'waptheme' => $xzv_48['waptheme'], 'webname' => $xzv_48['webname'],'huandeng1' => $xzv_48['huandeng1'],'huandeng2' => $xzv_48['huandeng2'],'huandeng3' => $xzv_48['huandeng3'], 'huandeng4' => $xzv_48['huandeng4'],'huandeng5' => $xzv_48['huandeng5'],'gonggao1' => $xzv_48['gonggao1'],'gonggao2' => $xzv_48['gonggao2'],'gonggao3' => $xzv_48['gonggao3'],'gonggao4' => $xzv_48['gonggao4'],'gonggao5' => $xzv_48['gonggao5'], 'disjump' => $xzv_48['disjump'], 'piclocal_type' => $xzv_48['piclocal_type'], 'indextitle' => $xzv_48['indextitle'], 'indexkw' => $xzv_48['indexkw'], 'indexdes' => $xzv_48['indexdes'], 'sitemap_url' => $xzv_48['sitemap_url'], 'virtviews' => $xzv_48['virtviews'], 'advertise' => $xzv_48['advertise'], 'searchlimit' => intval($xzv_48['searchlimit']), 'tag_url' => $xzv_48['tag_url'], 'tagtitle' => $xzv_48['tagtitle'], 'tagkw' => $xzv_48['tagkw'], 'tagdes' => $xzv_48['tagdes'], 'author_url' => $xzv_48['author_url'], 'authortitle' => $xzv_48['authortitle'], 'authorkw' => $xzv_48['authorkw'], 'authordes' => $xzv_48['authordes'], 'seoword_url' => $xzv_48['seoword_url'], 'listtitle' => $xzv_48['listtitle'], 'listkw' => $xzv_48['listkw'], 'listdes' => $xzv_48['listdes'], 'listurl' => $xzv_48['listurl'], 'viewtitle' => $xzv_48['viewtitle'], 'viewkw' => $xzv_48['viewkw'], 'viewdes' => $xzv_48['viewdes'], 'viewurl' => $xzv_48['viewurl'], 'idrule' => intval($xzv_48['idrule']), 'chaptertitle' => $xzv_48['chaptertitle'], 'chapterkw' => $xzv_48['chapterkw'], 'chapterdes' => $xzv_48['chapterdes'], 'chapterurl' => $xzv_48['chapterurl'], 'cidrule' => intval($xzv_48['cidrule']), 'chapterload' => $xzv_48['chapterload'], 'chapterlimit' => intval($xzv_48['chapterlimit']), 'toptitle' => $xzv_48['toptitle'], 'topurl' => $xzv_48['topurl'], 'fulltitle' => $xzv_48['fulltitle'], 'fullurl' => $xzv_48['fullurl'], 'alltitle' => $xzv_48['alltitle'], 'allurl' => $xzv_48['allurl'], 'core_filter' => $xzv_48['core_filter'], 'repick_sign' => $xzv_48['repick_sign'], 'blackiplist' => $xzv_48['blackiplist'], 'blackbooklist' => $xzv_48['blackbooklist'], 'lazyload' => $xzv_48['lazyload'], 'znsearch' => $xzv_48['znsearch'], 'znsid' => $xzv_48['znsid'], 'pushapi' => $xzv_48['pushapi'], 'statcode' => $xzv_48['statcode']);
            if ($xzv_48['debug'] > 0) {
                cookie('ygbook_debug', 1);
            } else {
                cookie('ygbook_debug', null);
            }
            F('setting', $xzv_129);
            $this->success('参数更新成功！');
        } elseif ($xzv_47 == 'extra') {
            $xzv_129['extra'] = array('listcachetime' => intval($xzv_48['listcachetime']), 'chaptercachetime' => intval($xzv_48['chaptercachetime']), 'wlist_domain' => $xzv_48['wlist_domain'], 'html_option' => implode($xzv_48['html_option'], ','), 'pick_proxy' => $xzv_48['pick_proxy'], 'qiniu_bucket' => $xzv_48['qiniu_bucket'], 'qiniu_accesskey' => $xzv_48['qiniu_accesskey'], 'qiniu_secretkey' => $xzv_48['qiniu_secretkey'], 'qiniu_domian' => $xzv_48['qiniu_domian']);
            F('setting', $xzv_129);
            $this->success('参数更新成功！');
        } elseif ($xzv_47 == 'test_proxy') {
            header('Content-Type:text/html;charset=utf-8');
            G('begin');
            $xzv_87 = I('proxy');
            $xzv_5 = new \Org\Util\Caiji($xzv_87);
            $xzv_4 = $xzv_5->get_url('http://1212.ip138.com/ic.asp', true);
            G('end');
            $xzv_5 = new \Org\Util\Caiji();
            $xzv_114 = $xzv_5->get_url('http://1212.ip138.com/ic.asp', true);
            $xzv_112 = G('begin', 'end');
            echo '使用代理：' . $xzv_87 . '，响应时间: ' . $xzv_112 . 's，如结果不同，则表明代理成功' . '






真实IP：' . g2u($xzv_114) . '






代理IP：' . g2u($xzv_4);
        } elseif ($xzv_47 == 'showpush') {
            header('Content-Type:text/html;charset=utf-8');
            if (!$this->setting['seo']['pushapi']) {
                die('请填写百度主动推送API');
            }
            $xzv_128 = (is_HTTPS() ? 'https://' : 'http://') . $this->setting['seo']['pcdomain'];
            $xzv_107 = htmlspecialchars_trans($this->setting['seo']['pushapi'], 'pick');
            $xzv_81 = push_curl($xzv_107, $xzv_128);
            $xzv_53 = json_decode($xzv_81, true);
            if ($xzv_53['success']) {
                echo '该功能会占用推送名额，请勿频繁使用！！！<br><br><br>今日推送余额：' . $xzv_53['remain'] . '，详情：' . $xzv_81;
            } else {
                echo '该功能会占用推送名额，请勿频繁使用！！！<br><br><br>推送出错，可能超额，或者api不对，详情：' . $xzv_81;
            }
        }
    }
    public function pick()
    {
        $xzv_106 = M('articles');
        $xzv_80 = $this->category;
        $xzv_78 = F('pick');
        $xzv_131 = I('param.');
        $xzv_102 = $xzv_131['action'];
        if ($xzv_102 == 'edit') {
            $xzv_127 = $xzv_131['name'];
            $xzv_78[$xzv_127]['urlreplace'] = htmlspecialchars_trans($xzv_78[$xzv_127]['urlreplace'], 'decode');
            $xzv_78[$xzv_127]['list_selector_prefilter'] = htmlspecialchars_trans($xzv_78[$xzv_127]['list_selector_prefilter'], 'decode');
            $xzv_78[$xzv_127]['list_url_extra'] = htmlspecialchars_trans($xzv_78[$xzv_127]['list_url_extra'], 'decode');
            $xzv_78[$xzv_127]['view_selector_prefilter'] = htmlspecialchars_trans($xzv_78[$xzv_127]['view_selector_prefilter'], 'decode');
            $xzv_78[$xzv_127]['chapter_selector_prefilter'] = htmlspecialchars_trans($xzv_78[$xzv_127]['chapter_selector_prefilter'], 'decode');
            $xzv_78[$xzv_127]['chapter_regx'] = htmlspecialchars_trans($xzv_78[$xzv_127]['chapter_regx'], 'decode');
            $xzv_78[$xzv_127]['chaptercont_prefilter'] = htmlspecialchars_trans($xzv_78[$xzv_127]['chaptercont_prefilter'], 'decode');
            $this->assign('name', $xzv_127);
            $this->assign('pick', $xzv_78);
            if (I('post.do') == 'save') {
                $xzv_131['urlreplace'] = htmlspecialchars_trans(I('post.urlreplace', '', 'htmlspecialchars_decode'));
                $xzv_131['list_selector_prefilter'] = htmlspecialchars_trans(I('post.list_selector_prefilter', '', 'htmlspecialchars_decode'));
                $xzv_131['list_url_extra'] = htmlspecialchars_trans($xzv_131['list_url_extra']);
                $xzv_131['view_selector_prefilter'] = htmlspecialchars_trans(I('post.view_selector_prefilter', '', 'htmlspecialchars_decode'));
                $xzv_131['chapter_selector_prefilter'] = htmlspecialchars_trans(I('post.chapter_selector_prefilter', '', 'htmlspecialchars_decode'));
                $xzv_131['chapter_regx'] = htmlspecialchars_trans(I('post.chapter_regx', '', 'htmlspecialchars_decode'));
                $xzv_131['chaptercont_prefilter'] = htmlspecialchars_trans(I('post.chaptercont_prefilter', '', 'htmlspecialchars_decode'));
                foreach ($xzv_131['list_cate'] as $xzv_77 => $xzv_57) {
                    if ($xzv_57 && $xzv_131['list_cate'][$xzv_77]['list_ocate']) {
                        $xzv_100 = $xzv_131['list_maxpage'][$xzv_77] ? $xzv_131['list_maxpage'][$xzv_77] : 1;
                        $xzv_143['k_' . $xzv_57] = array('cate' => $xzv_57, 'ocate' => $xzv_131['list_ocate'][$xzv_77], 'maxpage' => $xzv_100);
                    }
                }
                $xzv_78[$xzv_127] = array('open' => $xzv_131['open'], 'breakpick' => $xzv_131['breakpick'], 'proxy' => $xzv_131['proxy'], 'piclocal' => $xzv_131['piclocal'], 'picattr' => $xzv_131['picattr'], 'name' => $xzv_127, 'cate' => $xzv_131['cate'], 'domain' => $xzv_131['domain'], 'urlreplace' => $xzv_131['urlreplace'], 'charset' => $xzv_131['charset'], 'list_url' => $xzv_131['list_url'], 'list_url_extra' => $xzv_131['list_url_extra'], 'list_page' => $xzv_131['list_page'], 'list_cate' => $xzv_143, 'list_maxpage' => $xzv_131['list_maxpage'], 'nothumb_sign' => $xzv_131['nothumb_sign'], 'list_selector_prefilter' => $xzv_131['list_selector_prefilter'], 'list_selector' => $xzv_131['list_selector'], 'list_title_selector' => $xzv_131['list_title_selector'], 'list_thumb_selector' => $xzv_131['list_thumb_selector'], 'list_author_selector' => $xzv_131['list_author_selector'], 'view_selector_prefilter' => $xzv_131['view_selector_prefilter'], 'viewtitle_selector' => $xzv_131['viewtitle_selector'], 'viewauthor_selector' => $xzv_131['viewauthor_selector'], 'viewcate_selector' => $xzv_131['viewcate_selector'], 'view_selector' => $xzv_131['view_selector'], 'viewthumb_selector' => $xzv_131['viewthumb_selector'], 'isfull_sign' => $xzv_131['isfull_sign'], 'viewchapter_selector' => $xzv_131['viewchapter_selector'], 'chapter_selector_prefilter' => $xzv_131['chapter_selector_prefilter'], 'chapterarea_selector' => $xzv_131['chapterarea_selector'], 'chapter_regx' => $xzv_131['chapter_regx'], 'chapter_order' => $xzv_131['chapter_order'], 'chapter_ordernum' => $xzv_131['chapter_ordernum'], 'chaptercont_prefilter' => $xzv_131['chaptercont_prefilter'], 'chaptercont_selector' => $xzv_131['chaptercont_selector'], 'chaptercont_pagesign' => $xzv_131['chaptercont_pagesign'], 'chaptercont_page' => $xzv_131['chaptercont_page'], 'chaptercont_par' => $xzv_131['chaptercont_par']);
                F('pick', $xzv_78);
                $this->success('采集点参数更新成功！', U('pick'));
            } else {
                $this->assign('category', $xzv_80);
                $this->assign('action', $xzv_102);
                $this->display();
            }
        } elseif ($xzv_102 == 'test') {
            $xzv_101 = I('param.name');
            $xzv_103 = I('param.step', '1', 'intval');
            if ($xzv_103 == 1) {
                $xzv_104 = pickrun('list', $xzv_101, 'test');
            } elseif ($xzv_103 == 2) {
                $xzv_148 = I('param.articleurl');
                $xzv_105 = pickrun('content', $xzv_101, $xzv_148);
            } elseif ($xzv_103 == 3) {
                $xzv_133 = I('param.chapterurl');
                $xzv_146 = pickrun('chapter', $xzv_101, $xzv_133);
                $xzv_23 = fillurl($xzv_133, $xzv_146['chapterlist'][0]['link']);
                $xzv_146 = serialize($xzv_146);
                $this->assign('curl', $xzv_23);
            } else {
                $xzv_133 = I('param.chapterurl');
                $xzv_146 = pickrun('chaptercontent', $xzv_101, $xzv_133);
                $xzv_89 = $xzv_146['content'];
                $this->assign('chaptercont', $xzv_89);
            }
            $this->assign('name', $xzv_101);
            $this->assign('listdata', $xzv_104);
            $this->assign('articledb', $xzv_105);
            $this->assign('chapterdb', $xzv_146);
            $this->assign('action', $xzv_102);
            $this->assign('step', $xzv_103);
            $this->display();
        } elseif ($xzv_102 == 'export') {
            $xzv_101 = I('param.name');
            $xzv_145 = $xzv_78[$xzv_101];
            $xzv_26 = base64_encode(serialize($xzv_145));
            $this->assign('action', $xzv_102);
            $this->assign('exportcode', $xzv_26);
            $this->assign('pname', $xzv_101);
            $this->display();
        } elseif ($xzv_102 == 'import') {
            $xzv_16 = I('param.code');
            $xzv_24 = I('param.pickname');
            $xzv_27 = I('param.cate');
            if (I('param.do') == 'save') {
                if (!$xzv_16) {
                    $this->error('请输入规则代码后再进行导入，否则会导致规则清空！');
                }
                $xzv_25 = unserialize(base64_decode($xzv_16));
                $xzv_25['name'] = $xzv_101 = $xzv_24 ? $xzv_24 : ($xzv_78[$xzv_25['name']] ? $xzv_25['name'] . '_' . mt_rand(100, 999) : $xzv_25['name']);
                $xzv_25['cate'] = $xzv_27;
                $xzv_78[$xzv_101] = $xzv_25;
                F('pick', $xzv_78);
                $this->success('采集规则' . $xzv_101 . '导入成功！', U('pick'));
            } else {
                $this->assign('pick', $xzv_78);
                $this->assign('category', $xzv_80);
                $this->assign('action', $xzv_102);
                $this->display();
            }
        } elseif ($xzv_102 == 'save') {
            foreach ($xzv_131['popen'] as $xzv_108 => $xzv_109) {
                $xzv_27 = $xzv_131['pcate'][$xzv_108];
                $xzv_127 = $xzv_131['pname'][$xzv_108];
                if (!$xzv_109 || !$xzv_27 || !$xzv_127 || $xzv_109 == 'deleted') {
                    unset($xzv_78[$xzv_127]);
                } else {
                    $xzv_78[$xzv_127]['open'] = $xzv_109;
                    $xzv_78[$xzv_127]['name'] = $xzv_127;
                    $xzv_78[$xzv_127]['cate'] = $xzv_131['pcate'][$xzv_108];
                }
            }
            $xzv_110 = 0;
            foreach ($xzv_78 as $xzv_77 => $xzv_57) {
                if (!$xzv_131['popen'][$xzv_110] || !$xzv_57) {
                    unset($xzv_78[$xzv_77]);
                }
                $xzv_110++;
            }
            if (is_array($xzv_131['nname'])) {
                foreach ($xzv_131['nname'] as $xzv_108 => $xzv_109) {
                    if ($xzv_109 && $xzv_78[$xzv_109]['cate'] != '') {
                        $this->error('已存在相同名称的采集点！');
                    }
                    if (!$xzv_109 || !$xzv_131['ncate'][$xzv_108]) {
                        unset($xzv_78[$xzv_109]);
                    } else {
                        $xzv_78[$xzv_109] = array('open' => $xzv_131['nopen'][$xzv_108], 'name' => $xzv_109, 'cate' => $xzv_131['ncate'][$xzv_108]);
                    }
                }
            }
            F('pick', $xzv_78);
            $this->success('参数更新成功！');
        } elseif ($xzv_102 == 'runpick') {
            $xzv_3 = I('param.id');
            $xzv_111 = I('get.nownum', '', 'intval');
            $xzv_82 = I('get.maxnum', '', 'intval');
            if ($xzv_111 > 0 && $xzv_111 >= $xzv_82) {
                $this->success('批量采集完毕！', U('pick'));
                die;
            }
            if ($xzv_111 > 0) {
                foreach ($xzv_78 as $xzv_77 => $xzv_57) {
                    if ($xzv_57['open'] == 'yes') {
                        $xzv_13[] = $xzv_57;
                    }
                }
                $xzv_145 = unique_array($xzv_13, 1, false);
                $xzv_145 = $xzv_145[0];
                $xzv_3 = $xzv_145['name'];
            }
            $xzv_83 = pickrun('list', $xzv_3, 'admin');
            $xzv_75['status'] = 'success_' . $xzv_83;
            if ($xzv_111) {
                $this->success('第' . $xzv_111 . '次采集: 节点' . $xzv_3 . '，页码' . $xzv_83 . '，即将进行下一次！', U('pick', array('action' => 'runpick', 'oid' => $xzv_3, 'nownum' => $xzv_111 + 1, 'maxnum' => $xzv_82)));
                die;
            } else {
                die(json_encode($xzv_75));
            }
        } elseif ($xzv_102 == 'batchpick') {
            $xzv_144 = I('get.oid', 0, 'intval');
            $xzv_106 = M('articles');
            $xzv_113 = "lastcid = 0 and id>'{$xzv_144}'";
            $xzv_30 = $xzv_106->where($xzv_113)->order('id asc')->find();
            $xzv_3 = $xzv_30['id'];
            if (!$xzv_3) {
                $this->success('更新完成！', U('pick'));
                die;
            }
            $xzv_142 = floor($xzv_3 / 1000);
            $xzv_105 = F('view/book/' . $xzv_142 . '/' . $xzv_3);
            if (!isset($xzv_105['content']) || !$xzv_105['thumb']) {
                $this->pickinfo($xzv_30);
            } else {
                $xzv_43 = F('pick');
                $xzv_31 = $xzv_43[$xzv_30['pid']];
                if ($xzv_105['thumb'] && $xzv_31['piclocal'] == 'yes' && substr($xzv_105['thumb'], 0, 9) != '/uploads/') {
                    $xzv_75['thumb'] = deimg($xzv_105['thumb'], $xzv_3, $xzv_105['url'], true, true);
                }
                $xzv_105['thumb'] = $xzv_75['thumb'] ? $xzv_75['thumb'] : ($xzv_105['thumb'] ? $xzv_105['thumb'] : '/Public/images/nocover.jpg');
                $xzv_106->where("id = '{$xzv_3}'")->save($xzv_75);
                F('view/book/' . $xzv_142 . '/' . $xzv_3, $xzv_105);
            }
            $this->success('ID为' . $xzv_3 . '的文章信息更新成功，即将更新下一篇！', U('pick', array('action' => 'batchpick', 'oid' => $xzv_3)));
        } elseif ($xzv_102 == 'multitoggle') {
            $xzv_127 = I('get.name');
            $xzv_73 = I('get.toname');
            if (!$xzv_127 || !$xzv_73) {
                $this->error('节点选择有误');
            }
            $xzv_144 = I('get.oid', 0, 'intval');
            $xzv_30 = $xzv_106->where("pid='%s' and id > %d", $xzv_127, $xzv_144)->order('id asc')->find();
            $xzv_3 = $xzv_30['id'];
            if (!$xzv_3) {
                $this->success('转换完成！', U('pick'));
                die;
            }
            $xzv_32 = M('article_pickers')->where("aid='%d' and pid='%s'", $xzv_3, $xzv_73)->find();
            if ($xzv_32['url']) {
                M('articles')->where('id=%d', $xzv_3)->setField(array('pid' => $xzv_73, 'url' => $xzv_32['url'], 'update' => 1));
                delhtml($xzv_3);
                $this->success('ID为' . $xzv_3 . '的文章转换成功，即将转换下一篇！', U('pick', array('action' => 'multitoggle', 'oid' => $xzv_3, 'name' => $xzv_127, 'toname' => $xzv_73)));
            } else {
                $this->success('ID为' . $xzv_3 . '的文章无对应节点记录，已忽略，您可稍后转换至其他节点！', U('pick', array('action' => 'multitoggle', 'oid' => $xzv_3, 'name' => $xzv_127, 'toname' => $xzv_73)));
            }
        } elseif ($xzv_102 == 'singletoggle') {
            $xzv_3 = I('get.id', 0, 'intval');
            if (!$xzv_3) {
                $this->error('ID有误');
            }
            $xzv_73 = I('get.toname');
            $xzv_32 = M('article_pickers')->where("aid='%d' and pid='%s'", $xzv_3, $xzv_73)->find();
            if ($xzv_32['url']) {
                M('articles')->where('id=%d', $xzv_3)->setField(array('pid' => $xzv_73, 'url' => $xzv_32['url'], 'update' => 1));
                $this->success('转换成功！', U('article'));
            } else {
                $this->error('该节点无此小说记录，请更换其他节点');
            }
        } elseif ($xzv_102 == 'signpick') {
            $xzv_115 = I('post.sign_pid');
            $xzv_116 = I('post.sign_url');
            $xzv_28 = I('post.sign_ids');
            $xzv_126 = I('post.sign_cate');
            if (!$xzv_28) {
                $xzv_135 = F('signpick');
                $xzv_12 = I('get.maxcount');
                $xzv_124 = I('get.nowindex');
                $xzv_141 = $xzv_135['ids'][$xzv_124];
                if (!$xzv_141) {
                    F('signpick', null);
                    $this->success('采集完毕！', U('pick'));
                    die;
                }
                $xzv_142 = floor($xzv_141 / 1000);
                $xzv_36 = str_replace(array('[id]', '[subid]'), array($xzv_141, $xzv_142), $xzv_135['url']);
                $xzv_27 = $this->category[$xzv_135['cate']]['dir'];
                if (!M('articles')->where("url='%s'", $xzv_36)->find()) {
                    $xzv_75 = array('title' => '[title]', 'url' => $xzv_36, 'cate' => $xzv_135['cate'], 'pid' => $xzv_135['pid'], 'posttime' => NOW_TIME, 'updatetime' => NOW_TIME);
                    $xzv_75['id'] = M('articles')->add($xzv_75);
                    $this->pickinfo($xzv_75, 'signpick');
                    $xzv_63 = '节点:' . $xzv_135['pid'] . '，小说ID:' . $xzv_141 . '采集完毕';
                } else {
                    $xzv_63 = '节点:' . $xzv_135['pid'] . '，小说ID:' . $xzv_141 . '已存在，跳过采集';
                }
                $this->success($xzv_63 . '，转入下一本采集', U('pick', array('action' => 'signpick', 'nowindex' => $xzv_124 + 1, 'maxcount' => $xzv_12)));
            } else {
                if (!$xzv_115 || !$xzv_78[$xzv_115]) {
                    $this->error('节点ID有误，请重新尝试！');
                }
                $xzv_135['pid'] = $xzv_115;
                if (!$xzv_116) {
                    $this->error('URL有误，请重新尝试！');
                }
                if (!strexists($xzv_116, $xzv_78[$xzv_115]['domain'])) {
                    $this->error('URL必须包含指定节点的域名！');
                }
                if (strexists($xzv_28, '-')) {
                    list($xzv_121, $xzv_58) = explode('-', $xzv_28);
                    if ($xzv_121 >= $xzv_58 || $xzv_58 - $xzv_121 > 5000) {
                        $this->error('文章ID有误，请重新尝试！每次批量采集数量不能超过5000');
                    }
                    for ($xzv_110 = $xzv_121; $xzv_110 <= $xzv_58; $xzv_110++) {
                        $xzv_135['ids'][] = $xzv_110;
                    }
                } elseif (strexists($xzv_28, ',')) {
                    $xzv_135['ids'] = explode(',', $xzv_28);
                } else {
                    $xzv_135['ids'][0] = $xzv_28;
                }
                $xzv_135['url'] = $xzv_116;
                $xzv_135['cate'] = $xzv_126;
                F('signpick', $xzv_135);
                $xzv_12 = count($xzv_135['ids']);
                $this->success('初始化完毕，开始采集！', U('pick', array('action' => 'signpick', 'nowindex' => 0, 'maxcount' => $xzv_12)));
            }
        } elseif ($xzv_102 == 'picker_list') {
            $xzv_3 = I('get.id', '', 'intval');
            if (!$xzv_3) {
                $xzv_83['status'] = 'error';
                $this->ajaxReturn($xzv_83);
            }
            $xzv_83['status'] = 'success';
            $xzv_83['picker_list'] = M('article_pickers')->where('aid=%d', $xzv_3)->select();
            $this->ajaxReturn($xzv_83);
        } else {
            $this->assign('pick', $xzv_78);
            $this->assign('category', $xzv_80);
            $this->display();
        }
    }
    public function article()
    {
        $xzv_120 = M('articles');
        $xzv_137 = I('param.action');
        $xzv_66 = F('pick');
        if (!$xzv_137) {
            $xzv_119 = 50;
            $xzv_40 = I('get.p', 1, 'intval');
            $xzv_136 = I('get.cate');
            $xzv_70 = I('get.picker');
            $xzv_41 = $xzv_136 == 'default' ? $this->defaultdir : $xzv_136;
            $xzv_64 = I('param.q');
            $_GET['q'] = $xzv_64;
            $xzv_117 = I('get.order', 'id');
            if (!in_array($xzv_117, array('id', 'views', 'monthviews', 'weekviews', 'posttime', 'updatetime', 'full', 'original', 'push'))) {
                $this->error('请选择正确排序！');
            }
            foreach ($this->category as $xzv_42 => $xzv_59) {
                $xzv_44 .= "'" . $xzv_59['dir'] . "',";
            }
            if ($xzv_136) {
                if ($xzv_136 == 'nocover') {
                    $xzv_99 = "a.thumb like '%nocover%'";
                } elseif ($xzv_136 == 'nocate') {
                    $xzv_99 = 'a.cate not in (' . $xzv_44 . "'default')";
                } else {
                    $xzv_99 = "a.cate='{$xzv_41}'";
                }
            }
            if ($xzv_70) {
                $xzv_99 = "a.pid='{$xzv_70}'";
            }
            if (strexists($xzv_117, 'views')) {
                if ($xzv_117 == 'weekviews') {
                    $xzv_21 = date('W', NOW_TIME);
                    $xzv_99 .= ($xzv_99 ? 'and ' : '') . " av.weekkey='{$xzv_21}'";
                } elseif ($xzv_117 == 'monthviews') {
                    $xzv_147 = date('n', NOW_TIME);
                    $xzv_99 .= ($xzv_99 ? 'and ' : '') . " av.monthkey='{$xzv_147}'";
                }
                $xzv_22 = 'av.' . $xzv_117 . ' desc';
            } else {
                $xzv_22 = 'a.' . $xzv_117 . ' desc';
            }
            if ($xzv_64) {
                $xzv_99 .= ($xzv_99 ? 'and ' : '') . "(a.title like '%{$xzv_64}%' OR a.author like '%{$xzv_64}%')";
            }
            $xzv_69 = $xzv_120->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where($xzv_99)->order($xzv_22)->limit(($xzv_40 - 1) * $xzv_119, $xzv_119)->field('a.*,av.views,av.monthviews,av.weekviews')->select();
            foreach ($xzv_69 as $xzv_42 => $xzv_59) {
                $xzv_39 = $xzv_59['cate'] == $this->defaultdir ? 'default' : $xzv_59['cate'];
                $xzv_69[$xzv_42]['catename'] = $this->category[$xzv_39]['name'];
                $xzv_69[$xzv_42]['posttime'] = date('Y-m-d H:i', $xzv_69[$xzv_42]['posttime']);
                $xzv_69[$xzv_42]['views'] = intval($xzv_59['views']);
                $xzv_69[$xzv_42]['monthviews'] = intval($xzv_59['monthviews']);
                $xzv_69[$xzv_42]['weekviews'] = intval($xzv_59['weekviews']);
                unset($xzv_39);
            }
            $xzv_38 = $xzv_120->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where($xzv_99)->Count();
            $xzv_138 = pagelist_thinkphp($xzv_38, $xzv_119);
            $this->assign('articlelist', $xzv_69);
            $this->assign('cate', $xzv_136);
            $this->assign('picker', $xzv_70);
            $this->assign('q', $xzv_64);
            $this->assign('pagehtml', $xzv_138);
            $this->assign('orderby', $xzv_117);
        } elseif ($xzv_137 == 'edit' || $xzv_137 == 'add') {
            $xzv_65 = I('param.id', '', 'intval');
            if (I('post.do') != 'save') {
                if ($xzv_137 == 'edit') {
                    if (!$xzv_65) {
                        $this->error('数据读取出错！');
                    }
                    $xzv_37 = $xzv_120->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where("id='{$xzv_65}'")->find();
                    $xzv_139 = floor($xzv_65 / 1000);
                    $xzv_140 = F('view/book/' . $xzv_139 . '/' . $xzv_65);
                    $xzv_37['description'] = $xzv_140['description'];
                    $xzv_37['content'] = $xzv_140['content'];
                    $xzv_37['seotitle'] = $xzv_140['seotitle'];
                    $xzv_37['seokeyword'] = $xzv_140['seokeyword'];
                    $xzv_37['seodescription'] = $xzv_140['seodescription'];
                } else {
                    $xzv_37 = null;
                }
                $this->assign('articledb', $xzv_37);
            } else {
                if (!I('post.title', '', 'htmlspecialchars')) {
                    $this->error('文章标题不能为空！');
                }
                $xzv_62 = I('post.pid');
                $xzv_136 = I('post.cate', '', 'htmlspecialchars');
                $xzv_35 = I('post.thumb', '', 'htmlspecialchars');
                $xzv_94 = array('title' => I('post.title', '', 'htmlspecialchars'), 'cate' => $xzv_136, 'thumb' => $xzv_35, 'pid' => $xzv_62, 'full' => I('post.full', '', 'intval'), 'update' => I('post.update', '', 'intval'), 'original' => I('post.original', '', 'intval'), 'posttime' => NOW_TIME, 'updatetime' => NOW_TIME);
                $xzv_34 = array('views' => I('post.views', '', 'intval'), 'weekviews' => I('post.views', '', 'intval'), 'monthviews' => I('post.views', '', 'intval'));
                if ($xzv_137 == 'add') {
                    $xzv_94['author'] = I('post.author', '', 'htmlspecialchars');
                    $xzv_94['url'] = I('post.url');
                    $xzv_68 = $xzv_120->where("url = '{$xzv_94['url']}'")->find();
                    if (!$xzv_68['id']) {
                        $xzv_74 = $xzv_66[$xzv_62];
                        $xzv_93 = $xzv_120->where("title = '{$xzv_94['title']}'")->find();
                        if ($xzv_93['id'] && $xzv_93['author'] == $xzv_94['author']) {
                            $this->error('已存在同名同作者的文章！');
                        }
                        $xzv_85 = $xzv_74['piclocal'] == 'yes' ? true : false;
                        $xzv_65 = $xzv_120->add($xzv_94);
                        $xzv_84['thumb'] = deimg($xzv_35, $xzv_65, $xzv_94['url'], true, $xzv_85);
                        $xzv_120->where("id='{$xzv_65}'")->save($xzv_84);
                        $xzv_34['aid'] = $xzv_65;
                        if ($xzv_34['views'] > 0) {
                            M('article_views')->add($xzv_34);
                        }
                        if ($xzv_94['original'] == 1) {
                            pushapi($xzv_65);
                        }
                        $this->success('文章添加成功！', U('article'));
                    } else {
                        $this->error('文章已存在，请勿重复添加！如是原创文章，请填写任意不可能重复的url');
                    }
                } else {
                    $xzv_29 = $xzv_120->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->where("id='{$xzv_65}'")->find();
                    $xzv_94['url'] = I('post.url');
                    $xzv_94['author'] = I('post.author', '', 'htmlspecialchars');
                    $xzv_94['info'] = mb_substr(cleanHtml(I('post.description', '', 'htmlspecialchars')), 0, 120, 'utf-8');
                    $xzv_74 = $xzv_66[$xzv_62];
                    $xzv_85 = $xzv_74['piclocal'] == 'yes' ? true : false;
                    if (strexists($xzv_35, '://') && $xzv_85) {
                        $xzv_94['thumb'] = deimg($xzv_35, $xzv_65, $xzv_94['url'], true, $xzv_85);
                    }
                    $xzv_120->where("id='{$xzv_65}'")->save($xzv_94);
                    if (!M('article_views')->where("aid='{$xzv_65}'")->find()) {
                        $xzv_34['aid'] = $xzv_65;
                        M('article_views')->add($xzv_34);
                    } else {
                        M('article_views')->where("aid='{$xzv_65}'")->save($xzv_34);
                    }
                    $xzv_139 = floor($xzv_65 / 1000);
                    $xzv_140 = F('view/book/' . $xzv_139 . '/' . $xzv_65);
                    if ($xzv_29['url'] != $xzv_94['url']) {
                        S('chaptercache_' . $xzv_65, null);
                        F('view/book/' . $xzv_139 . '/' . $xzv_65, null);
                        F('view/chapter/' . $xzv_139 . '/' . $xzv_65, null);
                        F('view/newchapter/' . $xzv_139 . '/' . $xzv_65, null);
                    } else {
                        $xzv_37 = array_merge($xzv_140, $xzv_94);
                        if (!I('post.description', '', 'htmlspecialchars')) {
                            $xzv_37['description'] = mb_substr(cleanHtml(I('post.content', '', 'htmlspecialchars_decode')), 0, 120, 'utf-8');
                        } else {
                            $xzv_37['description'] = mb_substr(cleanHtml(I('post.description', '', 'htmlspecialchars')), 0, 120, 'utf-8');
                        }
                        $xzv_37['content'] = I('post.content', '', 'htmlspecialchars_decode');
                        if ($xzv_140['chapterurl'] == $xzv_140['url'] || !$xzv_140['chapterurl']) {
                            $xzv_37['chapterurl'] = $xzv_94['url'];
                        }
                        $xzv_37['title'] = $xzv_94['title'];
                        $xzv_37['url'] = $xzv_94['url'];
                        $xzv_37['full'] = $xzv_94['full'];
                        $xzv_37['original'] = $xzv_94['original'];
                        $xzv_37['cate'] = $xzv_136;
                        $xzv_37['catename'] = $xzv_136 == $this->defaultdir ? $this->category['default']['name'] : $this->category[$xzv_136]['name'];
                        $xzv_37['time'] = date('Y-m-d H:i:s', NOW_TIME);
                        $xzv_37['views'] = I('post.views', 1, 'intval');
                        $xzv_37['author'] = $xzv_94['author'];
                        $xzv_139 = floor($xzv_65 / 1000);
                        $xzv_37['thumb'] = showcover($xzv_94['thumb']);
                        if (strlen(I('post.seotitle')) > 0) {
                            $xzv_37['seotitle'] = I('post.seotitle');
                            $xzv_37['seokeyword'] = I('post.seokeyword');
                            $xzv_37['seodescription'] = I('post.seodescription');
                        }
                        F('view/book/' . $xzv_139 . '/' . $xzv_65, $xzv_37);
                    }
                    delhtml($xzv_65);
                    $this->success('文章更新成功！', U('article'));
                }
                die;
            }
        } elseif ($xzv_137 == 'del') {
            $xzv_65 = I('get.id', '', 'intval');
            !$xzv_65 && $this->error('出错啦！');
            $xzv_120->where("id='{$xzv_65}'")->delete();
            $xzv_139 = floor($xzv_65 / 1000);
            delhtml($xzv_65);
            F('view/book/' . $xzv_139 . '/' . $xzv_65, null);
            F('view/chapter/' . $xzv_139 . '/' . $xzv_65, null);
            F('view/newchapter/' . $xzv_139 . '/' . $xzv_65, null);
            S('chaptercache_' . $xzv_65, null);
            $xzv_10 = DATA_PATH . 'view/chaptercont/' . $xzv_139 . '/' . $xzv_65;
            clearfile($xzv_10);
            $this->success('文章删除成功！');
            die;
        } elseif ($xzv_137 == 'settype') {
            $xzv_65 = I('get.id', '', 'intval');
            !$xzv_65 && $this->error('出错啦！');
            $xzv_79 = I('get.type');
            $xzv_139 = floor($xzv_65 / 1000);
            $xzv_11 = $xzv_120->where("id='{$xzv_65}'")->find();
            if (!$xzv_11) {
                $this->error('小说不存在！');
            }
            switch ($xzv_79) {
                case 'cfull':
                    if ($xzv_11['full'] == 1) {
                        $xzv_94['full'] = 0;
                        $xzv_19 = '文章已调整为连载';
                    } else {
                        $xzv_94['full'] = 1;
                        $xzv_19 = '文章已调整为完结';
                    }
                    $xzv_120->where("id='{$xzv_65}'")->save($xzv_94);
                    $xzv_37 = F('view/book/' . $xzv_139 . '/' . $xzv_65);
                    $xzv_37['full'] = $xzv_94['full'];
                    F('view/book/' . $xzv_139 . '/' . $xzv_65, $xzv_37);
                    break;
                case 'update':
                    if ($xzv_11['update'] == 1) {
                        $xzv_94['update'] = 0;
                        $xzv_19 = '文章将在下次阅读时强制重新采集';
                    } else {
                        $xzv_94['update'] = 1;
                        $xzv_19 = '文章已取消强制采集';
                    }
                    $xzv_120->where("id='{$xzv_65}'")->save($xzv_94);
                    break;
                case 'original':
                    if ($xzv_11['original'] == 1) {
                        $xzv_94['original'] = 0;
                        $xzv_19 = '文章已调整为采集类型，将自动采集更新';
                    } else {
                        $xzv_94['original'] = 1;
                        $xzv_19 = '文章已调整为原创类型，将不再自动更新';
                    }
                    $xzv_120->where("id='{$xzv_65}'")->save($xzv_94);
                    break;
                default:
                    break;
            }
            $this->success($xzv_19);
            die;
        } elseif ($xzv_137 == 'multi-delete') {
            $xzv_76 = implode(',', I('post.ids'));
            $xzv_97['id'] = array('in', $xzv_76);
            $xzv_120->where($xzv_97)->delete();
            foreach (I('post.ids') as $xzv_65) {
                $xzv_139 = floor($xzv_65 / 1000);
                delhtml($xzv_65);
                F('view/book/' . $xzv_139 . '/' . $xzv_65, null);
                F('view/chapter/' . $xzv_139 . '/' . $xzv_65, null);
                F('view/newchapter/' . $xzv_139 . '/' . $xzv_65, null);
                S('chaptercache_' . $xzv_65, null);
                $xzv_10 = DATA_PATH . 'view/chaptercont/' . $xzv_139 . '/' . $xzv_65;
                clearfile($xzv_10);
            }
            $this->success('文章已删除！');
            die;
        } elseif ($xzv_137 == 'multi-move') {
            $xzv_76 = implode(',', I('post.ids'));
            $xzv_0 = I('post.newfid');
            if (!$xzv_0) {
                $this->error('请选择正确的栏目');
            }
            foreach (I('post.ids') as $xzv_65) {
                $xzv_139 = floor($xzv_65 / 1000);
                $xzv_37 = F('view/book/' . $xzv_139 . '/' . $xzv_65);
                if ($xzv_37) {
                    $xzv_37['cate'] = $xzv_0;
                    $xzv_37['catename'] = $this->category[$xzv_0]['name'];
                    F('view/book/' . $xzv_139 . '/' . $xzv_65, $xzv_37);
                }
            }
            $xzv_97['id'] = array('in', $xzv_76);
            $xzv_120->where($xzv_97)->setField('cate', $xzv_0);
            $this->success('文章移动成功！');
            die;
        } elseif ($xzv_137 == 'push') {
            $xzv_65 = I('get.id');
            !$xzv_65 && $this->error('出错啦！');
            if (!$this->setting['seo']['pushapi']) {
                $this->error('请填写百度主动推送API');
            }
            $xzv_6 = pushapi($xzv_65, true);
            if ($xzv_6['status']) {
                $this->success('文章推送成功！');
                die;
            } else {
                $this->error('文章推送失败！' . json_encode($xzv_6['info']));
                die;
            }
        }
        $this->assign('action', $xzv_137);
        $this->assign('category', $this->category);
        $this->assign('cate', $xzv_136);
        $this->assign('pick', $xzv_66);
        $this->display();
    }
    protected function pickinfo($xzv_45, $xzv_96 = 'batchpick')
    {
        $xzv_149 = $xzv_45;
        $xzv_54 = $xzv_149['id'];
        $xzv_95 = floor($xzv_54 / 1000);
        if ($xzv_149['original'] == 1) {
            return;
        }
        $xzv_134 = $xzv_149['cate'];
        $xzv_56 = new \Org\Util\Pick();
        $xzv_14 = $xzv_56->pickcont($xzv_149['url'], $xzv_149['pid']);
        if ($xzv_96 == 'signpick') {
            if (!$xzv_14['title']) {
                M('articles')->delete($xzv_54);
                return;
            } else {
                $xzv_46['title'] = $xzv_14['title'];
            }
        }
        $xzv_52 = F('pick');
        $xzv_8 = $xzv_52[$xzv_149['pid']];
        if ($xzv_14['thumb']) {
            if (strexists($xzv_14['thumb'], $xzv_8['nothumb_sign']) || !$xzv_14['thumb'] && !$xzv_149['thumb']) {
                $xzv_46['thumb'] = '/Public/images/nocover.jpg';
            } else {
                $xzv_1 = $xzv_8['piclocal'] == 'yes' ? true : false;
                $xzv_46['thumb'] = deimg($xzv_14['thumb'], $xzv_54, $xzv_149['url'], true, $xzv_1);
            }
        }
        if ($xzv_14['cate']) {
            $xzv_46['cate'] = $xzv_134 = $xzv_14['cate'];
        }
        $xzv_149['title'] = $xzv_14['title'];
        $xzv_149['content'] = $xzv_14['content'];
        $xzv_46['full'] = $xzv_149['full'] = $xzv_14['isfull'] == 'full' ? 1 : 0;
        $xzv_46['info'] = $xzv_149['description'] = mb_substr(cleanHtml($xzv_149['content']), 0, 120, 'utf-8');
        $xzv_149['keyword'] = $xzv_14['keyword'];
        $xzv_149['cate'] = $xzv_134;
        $xzv_149['catename'] = $xzv_134 == $this->defaultdir ? $this->category['default']['name'] : $this->category[$xzv_134]['name'];
        $xzv_149['time'] = date('Y-m-d H:i:s', $xzv_149['updatetime'] ? $xzv_149['updatetime'] : NOW_TIME);
        if ($xzv_14['author']) {
            $xzv_46['author'] = $xzv_149['author'] = $xzv_14['author'];
        }
        if ($xzv_14['thumb']) {
            if (strexists($xzv_14['thumb'], $xzv_8['nothumb_sign']) || !$xzv_14['thumb'] && !$xzv_149['thumb']) {
                $xzv_46['thumb'] = '/Public/images/nocover.jpg';
            } else {
                $xzv_1 = $xzv_8['piclocal'] == 'yes' ? true : false;
                $xzv_46['thumb'] = deimg($xzv_14['thumb'], $xzv_54, $xzv_149['url'], true, $xzv_1);
            }
        }
        if ($this->setting['seo']['piclocal_type'] == 'tocdn') {
            if ($xzv_149['thumb'] && $xzv_8['piclocal'] == 'yes' && strexists($xzv_149['thumb'], '://')) {
                $xzv_46['thumb'] = deimg($xzv_149['thumb'], $xzv_54, $xzv_149['url'], true, true);
            }
        } else {
            if ($xzv_149['thumb'] && $xzv_8['piclocal'] == 'yes' && substr($xzv_149['thumb'], 0, 9) != '/uploads/') {
                $xzv_46['thumb'] = deimg($xzv_149['thumb'], $xzv_54, $xzv_149['url'], true, true);
            }
        }
        $xzv_149['thumb'] = $xzv_46['thumb'] ? $xzv_46['thumb'] : ($xzv_149['thumb'] ? $xzv_149['thumb'] : '/Public/images/nocover.jpg');
        $xzv_149['chapterurl'] = $xzv_14['chapterurl'];
        if ($xzv_14['chapterdb']) {
            $xzv_92 = $xzv_14['chapterdb'];
        } else {
            $xzv_92 = $xzv_56->pickchapter($xzv_149['chapterurl'], $xzv_149['pid']);
        }
        $xzv_2 = $xzv_92['chapterlist'];
        if ($xzv_92) {
            $xzv_46['lastchapter'] = $xzv_149['lastchapter'] = $xzv_92['lastchapter']['title'];
            $xzv_46['lastcid'] = $xzv_149['lastcid'] = $xzv_92['lastchapter']['cid'];
            foreach ($xzv_2 as $xzv_86 => $xzv_91) {
                $xzv_2[$xzv_86]['id'] = $xzv_54;
                $xzv_2[$xzv_86]['cid'] = $xzv_86;
                $xzv_2[$xzv_86]['cate'] = $xzv_149['cate'];
                $xzv_2[$xzv_86]['title'] = trim($xzv_91['title']);
            }
            $xzv_61 = 0;
            foreach (array_reverse($xzv_2, true) as $xzv_86 => $xzv_91) {
                $xzv_90[$xzv_61] = $xzv_91;
                $xzv_61++;
                if ($xzv_61 > 11) {
                    break;
                }
            }
            F('view/chapter/' . $xzv_95 . '/' . $xzv_54, $xzv_2);
            F('view/newchapter/' . $xzv_95 . '/' . $xzv_54, $xzv_90);
            S('chaptercache_' . $xzv_54, NOW_TIME, array('temp' => TEMP_PATH . 'chaptercache/' . $xzv_95 . '/', 'expire' => $this->setting['extra']['chaptercachetime']));
        }
        if ($xzv_46['lastchapter']) {
            $xzv_149['updatetime'] = $xzv_46['updatetime'] = NOW_TIME;
            $xzv_149['time'] = date('Y-m-d H:i:s', NOW_TIME);
        }
        M('articles')->where("id = '{$xzv_54}'")->save($xzv_46);
        $xzv_50 = M('article_views')->where('aid = %d', $xzv_54)->find();
        list($xzv_17, $xzv_132) = explode(',', $this->setting['seo']['virtviews']);
        if (!$xzv_17 || !$xzv_132 || $xzv_132 < $xzv_17) {
            $xzv_17 = 1;
            $xzv_132 = 3;
        }
        $xzv_20 = mt_rand($xzv_17, $xzv_132);
        if (!$xzv_50['aid']) {
            $xzv_149['weekviews'] = $xzv_149['monthviews'] = $xzv_149['views'] = $xzv_20;
            $xzv_98 = array('aid' => $xzv_54, 'weekviews' => $xzv_20, 'monthviews' => $xzv_20, 'views' => $xzv_20, 'weekkey' => date('W', NOW_TIME), 'monthkey' => date('n', NOW_TIME));
            M('article_views')->add($xzv_98);
        }
        F('view/book/' . $xzv_95 . '/' . $xzv_54, $xzv_149);
        pushapi($xzv_54);
    }
}