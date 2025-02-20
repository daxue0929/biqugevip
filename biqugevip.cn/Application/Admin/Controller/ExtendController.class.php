<?php

namespace Admin\Controller;

use Admin\Controller\BaseController;
use Think\Controller;
class ExtendController extends AdminController
{
    public function category()
    {
        $xzv_0 = $this->setting;
        $xzv_36 = $this->category;
        $xzv_25 = F('pick');
        $xzv_69 = I('param.');
        $xzv_130 = $xzv_69['action'];
        if ($xzv_130 == 'save') {
            $xzv_36['default']['dir'] = $xzv_69['ddir'];
            $xzv_36['default']['name'] = $xzv_69['dname'];
            $xzv_36['default']['order'] = $xzv_69['dorder'];
            foreach ($xzv_69['copen'] as $xzv_119 => $xzv_129) {
                $xzv_108 = $xzv_69['cdir'][$xzv_119];
                $xzv_134 = $xzv_69['cname'][$xzv_119];
                $xzv_117 = intval($xzv_69['corder'][$xzv_119]);
                if (!$xzv_129 || !$xzv_108 || !$xzv_134 || $xzv_129 == 'deleted') {
                    unset($xzv_36[$xzv_108]);
                } else {
                    $xzv_36[$xzv_108]['open'] = $xzv_129;
                    $xzv_36[$xzv_108]['name'] = $xzv_134;
                    $xzv_36[$xzv_108]['dir'] = $xzv_108;
                    $xzv_36[$xzv_108]['order'] = $xzv_117;
                }
            }
            $xzv_133 = 0;
            foreach ($xzv_36 as $xzv_23 => $xzv_80) {
                if (!$xzv_80) {
                    unset($xzv_36[$xzv_23]);
                }
                $xzv_133++;
            }
            if (is_array($xzv_69['ndir'])) {
                foreach ($xzv_69['ndir'] as $xzv_119 => $xzv_129) {
                    if ($xzv_129 && $xzv_36[$xzv_129]['name'] != '') {
                        $this->error('已存在相同dir的栏目！');
                    }
                    if (!$xzv_129 || !$xzv_69['nname'][$xzv_119]) {
                        unset($xzv_36[$xzv_129]);
                    } else {
                        $xzv_36[$xzv_129] = array('open' => $xzv_69['nopen'][$xzv_119], 'name' => $xzv_69['nname'][$xzv_119], 'dir' => $xzv_129, 'order' => intval($xzv_69['norder'][$xzv_119]));
                    }
                }
            }
            $xzv_33 = array('direction' => 'SORT_DESC', 'field' => 'order');
            $xzv_127 = array();
            foreach ($xzv_36 as $xzv_24 => $xzv_89) {
                foreach ($xzv_89 as $xzv_23 => $xzv_80) {
                    $xzv_127[$xzv_23][$xzv_24] = $xzv_80;
                }
            }
            if ($xzv_33['direction']) {
                array_multisort($xzv_127[$xzv_33['field']], constant($xzv_33['direction']), $xzv_36);
            }
            F('category', $xzv_36);
            $this->success('栏目更新成功！');
        } elseif ($xzv_130 == 'edit') {
            $xzv_108 = $xzv_69['dir'];
            if (I('post.do') == 'save') {
                $xzv_126 = $xzv_69['dir'] == 'default' ? 'default' : $xzv_69['newdir'];
                $xzv_36[$xzv_126] = array('open' => $xzv_69['open'], 'name' => $xzv_36[$xzv_108]['name'], 'dir' => $xzv_69['newdir'], 'order' => $xzv_36[$xzv_108]['order'], 'listtitle' => $xzv_69['listtitle'], 'listkw' => $xzv_69['listkw'], 'listdes' => $xzv_69['listdes'], 'viewtitle' => $xzv_69['viewtitle'], 'viewkw' => $xzv_69['viewkw'], 'viewdes' => $xzv_69['viewdes'], 'chaptertitle' => $xzv_69['chaptertitle'], 'chapterkw' => $xzv_69['chapterkw'], 'chapterdes' => $xzv_69['chapterdes']);
                F('category', $xzv_36);
                $this->success('栏目设置更新成功！');
            } else {
                $this->assign('action', $xzv_130);
                $this->assign('category', $xzv_36);
                $this->assign('dir', $xzv_108);
                $this->display();
            }
        } else {
            $this->assign('category', $xzv_36);
            $this->assign('setting', $xzv_0);
            $this->display();
        }
    }
    public function chapter()
    {
        $xzv_46 = I('param.action');
        $xzv_74 = I('param.id', '', 'intval');
        $xzv_128 = I('param.cid', '', 'intval');
        if (!$xzv_74) {
            $this->error('小说ID有误');
        }
        $xzv_21 = floor($xzv_74 / 1000);
        $this->assign('action', $xzv_46);
        $this->assign('id', $xzv_74);
        $this->assign('cid', $xzv_128);
        $xzv_29 = F('view/chapter/' . $xzv_21 . '/' . $xzv_74);
        $xzv_132 = M('articles')->where('id=%d', $xzv_74)->find();
        if (!$xzv_29) {
            if ($xzv_132['original'] == 1) {
                F('view/chapter/' . $xzv_21 . '/' . $xzv_74, array());
                $xzv_45 = F('view/book/' . $xzv_21 . '/' . $xzv_74);
                if (!$xzv_45) {
                    $this->error('请先编辑小说，填写完善简介信息后才能增加章节', U('article', array('action' => 'edit', 'id' => $xzv_74)));
                }
            } else {
                $this->error('该小说没有章节列表');
            }
        }
        if ($xzv_46 == 'list') {
            $this->assign('articledb', $xzv_132);
            $this->assign('chapterlist', $xzv_29);
            $this->display();
        } elseif ($xzv_46 == 'edit') {
            $xzv_132 = F('view/book/' . $xzv_21 . '/' . $xzv_74);
            $xzv_50 = F('view/chaptercont/' . $xzv_21 . '/' . $xzv_74 . '/' . $xzv_128);
            if (I('post.step') == 'save') {
                $xzv_66 = $xzv_50['title'];
                $xzv_50['title'] = I('post.title');
                $xzv_50['content'] = I('post.content', '', 'htmlspecialchars_decode');
                $xzv_50['link'] = I('post.link');
                if (!$xzv_50['nextcid']) {
                    $xzv_50['rewriteurl'] = reurl('chapter', array('id' => $xzv_74, 'cate' => $xzv_132['cate'], 'cid' => $xzv_128));
                    $xzv_50['nextcid'] = $xzv_128 < count($xzv_29) - 1 ? $xzv_128 + 1 : -1;
                    $xzv_50['prevcid'] = $xzv_128 > 0 ? $xzv_128 - 1 : -1;
                }
                F('view/chaptercont/' . $xzv_21 . '/' . $xzv_74 . '/' . $xzv_128, $xzv_50);
                if ($xzv_66 != $xzv_50['title']) {
                    $xzv_29 = F('view/chapter/' . $xzv_21 . '/' . $xzv_74);
                    $xzv_111 = F('view/newchapter/' . $xzv_21 . '/' . $xzv_74);
                    if (is_array($xzv_29[$xzv_128])) {
                        $xzv_29[$xzv_128]['title'] = $xzv_50['title'];
                        $xzv_29[$xzv_128]['link'] = $xzv_50['link'];
                        F('view/chapter/' . $xzv_21 . '/' . $xzv_74, $xzv_29);
                    }
                    if (is_array($xzv_111[$xzv_128])) {
                        $xzv_111[$xzv_128]['title'] = $xzv_50['title'];
                        $xzv_111[$xzv_128]['link'] = $xzv_50['link'];
                        F('view/newchapter/' . $xzv_21 . '/' . $xzv_74, $xzv_111);
                    }
                }
                delchapter($xzv_74, $xzv_128);
                delhtml($xzv_74);
                $this->success('章节更新成功！');
            } else {
                $this->assign('articledb', $xzv_132);
                $this->assign('chapter', $xzv_50);
                $this->display();
            }
        } elseif ($xzv_46 == 'delete') {
            unset($xzv_29[$xzv_128]);
            delchapter($xzv_74, $xzv_128);
            delhtml($xzv_74);
            $xzv_63 = DATA_PATH . 'view/chaptercont/' . $xzv_21 . '/' . $xzv_74;
            clearfile($xzv_63);
            F('view/chapter/' . $xzv_21 . '/' . $xzv_74, $xzv_29);
            $this->success('章节删除成功！');
        } elseif ($xzv_46 == 'add') {
            if (I('post.step') == 'save') {
                $xzv_128 = count($xzv_29);
                $xzv_50['title'] = I('post.title');
                $xzv_50['content'] = I('post.content', '', 'htmlspecialchars_decode');
                $xzv_50['link'] = I('post.link');
                $xzv_50['rewriteurl'] = reurl('chapter', array('id' => $xzv_74, 'cate' => $xzv_132['cate'], 'cid' => $xzv_128));
                $xzv_50['nextcid'] = -1;
                $xzv_50['prevcid'] = $xzv_128 - 1;
                F('view/chaptercont/' . $xzv_21 . '/' . $xzv_74 . '/' . $xzv_128, $xzv_50);
                $xzv_29[] = array('link' => '', 'title' => $xzv_50['title'], 'id' => $xzv_74, 'cid' => $xzv_128, 'cate' => $xzv_132['cate']);
                F('view/chapter/' . $xzv_21 . '/' . $xzv_74, $xzv_29);
                foreach (array_reverse($xzv_29, true) as $xzv_56 => $xzv_51) {
                    $xzv_59[$xzv_56] = $xzv_47[$xzv_116] = $xzv_51;
                    $xzv_116++;
                    if ($xzv_116 > 11) {
                        break;
                    }
                }
                if (count($xzv_59) > 0) {
                    F('view/newchapter/' . $xzv_21 . '/' . $xzv_74, $xzv_59);
                }
                $xzv_132 = F('view/book/' . $xzv_21 . '/' . $xzv_74);
                $xzv_132['lastchapter'] = $xzv_50['title'];
                $xzv_132['lastcid'] = $xzv_128;
                F('view/book/' . $xzv_21 . '/' . $xzv_74, $xzv_132);
                M('articles')->where('id=%d', $xzv_74)->setField(array('lastchapter' => $xzv_50['title'], 'lastcid' => $xzv_128));
                delhtml($xzv_74);
                $this->success('章节新增成功！');
            } else {
                $xzv_132 = F('view/book/' . $xzv_21 . '/' . $xzv_74);
                $this->assign('articledb', $xzv_132);
                $this->display();
            }
        }
    }
    public function dataarea()
    {
        $xzv_73 = I('param.action');
        $xzv_19 = $this->dataarea;
        $xzv_90 = I('param.');
        $xzv_48 = I('param.domain', 'default');
        $this->assign('action', $xzv_73);
        $this->assign('nowdomain', $xzv_48);
        if ($xzv_73 == 'edit') {
            $xzv_137 = I('param.did');
            if (!$xzv_48) {
                $this->error('没有指定区块domain');
            }
            $xzv_19 = F('dataarea/' . $xzv_48);
            if (I('post.do') == 'save') {
                $xzv_19[$xzv_137] = array('open' => $xzv_90['open'], 'did' => $xzv_137, 'cate' => $xzv_90['cate'], 'ids' => $xzv_90['ids'], 'orderby' => $xzv_90['orderby'], 'orderway' => $xzv_90['orderway'], 'hasthumb' => $xzv_90['hasthumb'], 'hasinfo' => $xzv_90['hasinfo'], 'isfull' => $xzv_90['isfull'], 'limit' => intval($xzv_90['limit']), 'infolen' => intval($xzv_90['infolen']), 'dateformat' => $xzv_90['dateformat'], 'expirehour' => intval($xzv_90['expirehour']));
                F('dataarea/' . $xzv_48, $xzv_19);
                $this->success('区块设置更新成功！', U('dataarea', array('domain' => $xzv_48)));
                die;
            } else {
                $xzv_52 = <<<EOT
<foreach name="dataarea_list.{$xzv_137}" item="v">
<li>
封面：<a href="{&#36;v.rewriteurl}"><img src="{&#36;v.thumb}" /></a><br>
书名：<a href="{&#36;v.rewriteurl}">{&#36;v.title}</a><br>
作者：{&#36;v.author}<br>
分类：<a href="{&#36;v.cateurl}">{&#36;v.catename}</a><br>
发表时间：{&#36;v.posttime}<br>
更新时间：{&#36;v.updatetime}<br>
人气：{&#36;v.views}<br>
连载/完成：{&#36;v.status}<br>
周人气：{&#36;v.weekviews}<br>
月人气：{&#36;v.monthviews}<br>
简介：{&#36;v.description}<br>
最新章节:<a href="{&#36;v.lastchapterurl}">{&#36;v.lastchapter}</a>
</li>
</foreach>
EOT;
                $this->assign('did', $xzv_137);
                $this->assign('democode', $xzv_52);
                $this->assign('dataarea', $xzv_19);
                $this->assign('category', $this->category);
            }
            $this->display();
        } elseif ($xzv_73 == 'save') {
            if (!$xzv_48) {
                $this->error('没有指定区块domain');
            }
            $xzv_54 = F('dataarea/' . $xzv_48);
            foreach ($xzv_90['copen'] as $xzv_79 => $xzv_77) {
                $xzv_137 = $xzv_90['cdid'][$xzv_79];
                if (!$xzv_77 || $xzv_77 == 'deleted') {
                    unset($xzv_54[$xzv_137]);
                } else {
                    $xzv_54[$xzv_137]['open'] = $xzv_77;
                    $xzv_54[$xzv_137]['did'] = $xzv_137;
                }
            }
            foreach ($xzv_54 as $xzv_49 => $xzv_2) {
                if (!$xzv_2) {
                    unset($xzv_54[$xzv_49]);
                }
            }
            if (is_array($xzv_90['ndid'])) {
                foreach ($xzv_90['ndid'] as $xzv_79 => $xzv_77) {
                    if ($xzv_77 && $xzv_54[$xzv_77]['did'] != '') {
                        $this->error('已存在相同id的区块！');
                    }
                    if (!$xzv_77) {
                        unset($xzv_54[$xzv_77]);
                    } else {
                        $xzv_54[$xzv_77] = array('open' => $xzv_90['nopen'][$xzv_79], 'did' => $xzv_77);
                    }
                }
            }
            F('dataarea/' . $xzv_48, $xzv_54);
            $this->success('数据区块更新成功！');
            die;
        } elseif ($xzv_73 == 'initialize') {
            if (!$xzv_48) {
                $this->error('没有指定区块domain');
            }
            if ($xzv_48 == 'default' || $xzv_48 == 'wap') {
                $xzv_125 = 'default';
            }
            $xzv_19 = F('dataarea/' . $xzv_48);
            if (!$xzv_125 || !$xzv_19) {
                $this->error('没有找到主domain或无区块数据');
            }
            $xzv_109 = M('articles');
            foreach ($xzv_19 as $xzv_49 => $xzv_2) {
                if ($xzv_2['open'] != 'yes') {
                    continue;
                }
                $xzv_91 = 'dataarea_' . $xzv_2['did'];
                $xzv_4 = in_array(strtolower($xzv_2['orderby']), array('id', 'views', 'weekviews', 'monthviews', 'posttime', 'updatetime')) ? strtolower($xzv_2['orderby']) : 'id';
                $xzv_110 = in_array(strtolower($xzv_2['orderway']), array('desc', 'asc')) ? strtolower($xzv_2['orderway']) : 'desc';
                $xzv_37 = in_array(strtolower($xzv_2['hasthumb']), array('yes', 'no')) ? strtolower($xzv_2['hasthumb']) : 'no';
                $xzv_84 = in_array(strtolower($xzv_2['hasinfo']), array('yes', 'no')) ? strtolower($xzv_2['hasinfo']) : 'no';
                $xzv_83 = in_array(strtolower($xzv_2['isfull']), array('yes', 'no')) ? strtolower($xzv_2['isfull']) : 'no';
                $xzv_20 = intval($xzv_2['limit']) ? intval($xzv_2['limit']) : 10;
                $xzv_75 = intval($xzv_2['infolen']) ? intval($xzv_2['infolen']) : 40;
                $xzv_112 = intval($xzv_2['expirehour']) ? intval($xzv_2['expirehour']) : 40;
                $xzv_93 = $xzv_2['dateformat'] ? $xzv_2['dateformat'] : 'Y-m-d H:i:s';
                if ($xzv_2['ids']) {
                    $xzv_76 = explode(',', $xzv_2['ids']);
                    $xzv_153 = count($xzv_76) == 1 ? true : false;
                    $xzv_76 = "'" . implode("','", $xzv_76) . "'";
                    $xzv_78 = "a.id in({$xzv_76})";
                } else {
                    $xzv_2['cate'] = $xzv_2['cate'] == 'default' ? $this->defaultdir : $xzv_2['cate'];
                    $xzv_113 = $xzv_2['cate'] ? $xzv_2['cate'] : 'all';
                    $xzv_78 = $xzv_113 != 'all' ? "a.cate='{$xzv_113}'" : '1';
                    if ($xzv_37 == 'yes') {
                        $xzv_78 .= ' and a.thumb is not null';
                    }
                    if ($xzv_84 == 'yes') {
                        $xzv_78 .= ' and a.info is not null';
                    }
                    if ($xzv_83 == 'yes') {
                        $xzv_78 .= ' and a.full=1';
                    }
                }
                if (strexists($xzv_4, 'views')) {
                    if ($xzv_4 == 'weekviews') {
                        $xzv_81 = date('W', NOW_TIME);
                        $xzv_78 .= " and av.weekkey='{$xzv_81}'";
                    } elseif ($xzv_4 == 'monthviews') {
                        $xzv_114 = date('n', NOW_TIME);
                        $xzv_78 .= " and av.monthkey='{$xzv_114}'";
                    }
                    $xzv_4 = 'av.' . $xzv_4;
                } else {
                    $xzv_4 = 'a.' . $xzv_4;
                }
                $xzv_44 = $xzv_4 . ' ' . $xzv_110;
                $xzv_6 = $xzv_109->alias('a')->join('LEFT JOIN ' . C('DB_PREFIX') . 'article_views av ON a.id=av.aid')->field('a.id,a.title,a.thumb,a.cate,a.info,a.posttime,a.updatetime,a.lastchapter,a.lastcid,a.author,a.full,av.views,av.weekviews,av.monthviews')->where($xzv_78)->order($xzv_44)->limit(0, $xzv_20)->select();
                $xzv_30 = $this->category;
                foreach ($xzv_6 as $xzv_149 => $xzv_87) {
                    $xzv_87['subid'] = floor($xzv_87['id'] / 1000);
                    $xzv_115[$xzv_149]['title'] = $xzv_87['title'];
                    $xzv_115[$xzv_149]['rewriteurl'] = reurl('view', $xzv_87, $xzv_125);
                    $xzv_115[$xzv_149]['cateurl'] = reurl('cate', $xzv_87['cate'], $xzv_125);
                    if ($xzv_87['cate'] == $this->defaultdir || !$xzv_87['cate']) {
                        $xzv_148 = 'default';
                    } else {
                        $xzv_148 = $xzv_87['cate'];
                    }
                    $xzv_115[$xzv_149]['description'] = $xzv_87['info'] ? mb_substr($xzv_87['info'], 0, $xzv_75, 'utf-8') : $xzv_87['title'];
                    $xzv_115[$xzv_149]['catename'] = $xzv_30[$xzv_148]['name'];
                    $xzv_115[$xzv_149]['catename_short'] = mb_substr($xzv_115[$xzv_149]['catename'], 0, 2, 'utf-8');
                    $xzv_115[$xzv_149]['thumb'] = showcover($xzv_87['thumb']);
                    $xzv_115[$xzv_149]['posttime'] = date($xzv_87['posttime'], $xzv_93);
                    $xzv_115[$xzv_149]['updatetime'] = date($xzv_87['updatetime'], $xzv_93);
                    $xzv_115[$xzv_149]['lastchapter'] = $xzv_87['lastchapter'] ? $xzv_87['lastchapter'] : '最新一章';
                    $xzv_115[$xzv_149]['lastchapterurl'] = $xzv_87['lastcid'] ? reurl('chapter', array('id' => $xzv_87['id'], 'cate' => $xzv_87['cate'], 'cid' => $xzv_87['lastcid']), $xzv_125) : $xzv_115[$xzv_49]['rewriteurl'];
                    $xzv_115[$xzv_149]['author'] = $xzv_87['author'];
                    $xzv_115[$xzv_149]['views'] = intval($xzv_87['views']);
                    $xzv_115[$xzv_149]['weekviews'] = intval($xzv_87['weekviews']);
                    $xzv_115[$xzv_149]['monthviews'] = intval($xzv_87['monthviews']);
                    $xzv_115[$xzv_149]['status'] = $xzv_87['full'] > 0 ? '完成' : '连载';
                    if ($xzv_153) {
                        $xzv_115[$xzv_149]['articledb'] = F('view/book/' . $xzv_87['subid'] . '/' . $xzv_87['id']);
                        $xzv_115[$xzv_149]['newchapter'] = F('view/newchapter/' . $xzv_87['subid'] . '/' . $xzv_87['id']);
                        $xzv_153 = false;
                        break;
                    }
                }
                S($xzv_125 . '_' . $xzv_91, NOW_TIME, array('temp' => TEMP_PATH . 'dataarea/', 'expire' => $xzv_112 * 3600));
                F('dataarea/' . $xzv_48 . '/' . $xzv_91, $xzv_115);
                unset($xzv_115);
            }
            $this->success('数据更新成功！', U('dataarea'));
            die;
        } elseif ($xzv_73 == 'export') {
            if (!$xzv_48) {
                $this->error('只允许指定域名');
            }
            $xzv_147 = F('dataarea/' . $xzv_48);
            if (!$xzv_147) {
                $this->error('无相关数据');
            }
            $xzv_7 = base64_encode(serialize($xzv_147));
            $this->assign('action', $xzv_73);
            $this->assign('exportcode', $xzv_7);
            $this->display();
        } elseif ($xzv_73 == 'import') {
            if (!$xzv_48) {
                $this->error('只允许指定域名');
            }
            if (I('post.do') == 'save') {
                $xzv_92 = unserialize(base64_decode(I('post.code')));
                if ($xzv_48) {
                    F('dataarea/' . $xzv_48, $xzv_92);
                    $this->success('站点：' . $xzv_48 . '数据导入成功！', U('dataarea'));
                } else {
                    $this->error('数据导入失败！');
                }
            } else {
                $this->assign('nowdomain', $xzv_48);
                $this->assign('action', $xzv_73);
                $this->display();
            }
        } else {
            $xzv_19 = $xzv_48 ? F('dataarea/' . $xzv_48) : null;
            $this->assign('dataarea', $xzv_19);
            $this->display();
        }
    }
    public function tags()
    {
        $xzv_145 = I('param.action');
        if (!$xzv_145) {
            $xzv_1 = 50;
            $xzv_34 = I('get.p', 1, 'intval');
            $xzv_72 = M('tags');
            $xzv_106 = $xzv_72->limit(($xzv_34 - 1) * $xzv_1, $xzv_1)->order('num desc')->select();
            foreach ($xzv_106 as $xzv_143 => $xzv_118) {
                $xzv_106[$xzv_143]['tagurl'] = reurl('tag', $xzv_118);
            }
            $xzv_58 = $xzv_72->Count();
            $xzv_43 = pagelist_thinkphp($xzv_58, $xzv_1);
            $this->assign('taglist', $xzv_106);
            $this->assign('tagnum', $xzv_58);
            $this->assign('pagehtml', $xzv_43);
        } elseif ($xzv_145 == 'view') {
            $xzv_124 = I('get.tid', '', 'intval');
            $xzv_9 = M('tagdatas');
            $xzv_11 = M('articles');
            $xzv_141 = $xzv_9->where("tid='{$xzv_124}'")->select();
            $xzv_123 = $xzv_9->where("tid='{$xzv_124}'")->Count();
            foreach ($xzv_141 as $xzv_143 => $xzv_118) {
                $xzv_140 = $xzv_140 ? $xzv_140 . ',' . $xzv_118['aid'] : $xzv_118['aid'];
            }
            $xzv_40['id'] = array('in', $xzv_140);
            $xzv_12 = $xzv_11->where($xzv_40)->order('id desc')->limit(50)->select();
            foreach ($xzv_12 as $xzv_143 => $xzv_118) {
                $xzv_122 = $xzv_118['cate'] == $this->defaultdir ? 'default' : $xzv_118['cate'];
                $xzv_12[$xzv_143]['catename'] = $this->category[$xzv_122]['name'];
                $xzv_12[$xzv_143]['posttime'] = date('Y-m-d H:i', $xzv_118['posttime']);
                $xzv_12[$xzv_143]['rewriteurl'] = reurl('view', $xzv_118);
            }
            $this->assign('arclist', $xzv_12);
            $this->assign('arcnum', $xzv_123);
        }
        $this->assign('action', $xzv_145);
        $this->display();
    }
    public function spider()
    {
        $xzv_121 = I('param.action');
        if ($xzv_121 == 'clearall') {
            $xzv_13 = 'TRUNCATE `' . C('DB_PREFIX') . 'spiderlogs`';
            M()->execute($xzv_13);
            $this->success('蜘蛛历史记录已清空！', 'Spider');
        } else {
            $xzv_42 = I('param.domain');
            $xzv_65 = M('spiderlogs');
            $xzv_120 = M('settingmeta');
            $xzv_67 = $xzv_42 ? "domain='{$xzv_42}'" : '1';
            $xzv_14 = intval($xzv_120->where("meta_key='spider_day'")->getField('meta_value'));
            $xzv_139 = intval($xzv_120->where("meta_key='spider_lastday'")->getField('meta_value'));
            $xzv_16 = 100;
            $xzv_17 = I('get.p', 1, 'intval');
            $xzv_18 = $xzv_65->where($xzv_67)->order('id desc')->limit(($xzv_17 - 1) * $xzv_16, $xzv_16)->select();
            $xzv_26 = $xzv_65->where($xzv_67)->Count();
            $xzv_151 = pagelist_thinkphp($xzv_26, $xzv_16);
            $xzv_71 = strtotime(date('Y-m-d'));
            $xzv_55['google'] = $xzv_65->where("spider='Google' and dateline > '{$xzv_71}' and " . $xzv_67)->Count();
            $xzv_55['baidu'] = $xzv_65->where("spider='Baidu' and dateline > '{$xzv_71}' and " . $xzv_67)->Count();
            $xzv_55['360'] = $xzv_65->where("spider='360搜索' and dateline > '{$xzv_71}' and " . $xzv_67)->Count();
            $xzv_55['sogou'] = $xzv_65->where("spider='Sogou' and dateline > '{$xzv_71}' and " . $xzv_67)->Count();
            $xzv_55['shenma'] = $xzv_65->where("spider='神马' and dateline > '{$xzv_71}' and " . $xzv_67)->Count();
            $this->assign('setting', $this->setting);
            $this->assign('nowdomain', $xzv_42);
            $this->assign('spider_day', $xzv_14);
            $this->assign('spider_lastday', $xzv_139);
            $this->assign('spiderloglist', $xzv_18);
            $this->assign('spider_stat', $xzv_55);
            $this->assign('pagehtml', $xzv_151);
            $this->display();
        }
    }
    public function cache()
    {
        $xzv_5 = I('param.');
        $xzv_136 = $xzv_5['action'];
        if ($xzv_136 == 'clear') {
            if (in_array($xzv_5['id'], array('system', 'index', 'list', 'article', 'pick', 'sitemap', 'seodata'))) {
                switch ($xzv_5['id']) {
                    case 'system':
                        unlink(RUNTIME_PATH . 'common~runtime.php');
                        break;
                    case 'index':
                        $xzv_70 = $xzv_15 ? TEMP_PATH . $xzv_15 . '/index/' : TEMP_PATH . 'index/';
                        clearfile($xzv_70);
                        delhtml('index');
                        break;
                    case 'list':
                        $xzv_70 = $xzv_15 ? TEMP_PATH . $xzv_15 . '/cate/' : TEMP_PATH . 'cate/';
                        clearfile($xzv_70);
                        delhtml('cate');
                        break;
                    case 'article':
                        $xzv_35 = $xzv_5['aid'];
                        $xzv_41 = floor($xzv_35 / 1000);
                        if (!$xzv_35) {
                            $this->error('操作有误！');
                        }
                        delhtml($xzv_35);
                        F('view/book/' . $xzv_41 . '/' . $xzv_35, null);
                        F('view/chapter/' . $xzv_41 . '/' . $xzv_35, null);
                        F('view/newchapter/' . $xzv_41 . '/' . $xzv_35, null);
                        S('chaptercache_' . $xzv_35, null);
                        $xzv_70 = DATA_PATH . 'view/chaptercont/' . $xzv_41 . '/' . $xzv_35;
                        clearfile($xzv_70);
                        break;
                    case 'pick':
                        unlink(RUNTIME_PATH . '~crons.php');
                        break;
                    case 'sitemap':
                        $xzv_96 = $xzv_15 ? TEMP_PATH . $xzv_15 : TEMP_PATH;
                        S('mapinfo', null, array('temp' => $xzv_96));
                        break;
                    case 'seodata':
                        $xzv_96 = $xzv_15 ? TEMP_PATH . $xzv_15 : TEMP_PATH;
                        S('seodata', null, array('temp' => $xzv_96));
                        break;
                    default:
                        break;
                }
                $this->success('缓存清理成功！');
            } else {
                $this->error('操作有误！');
            }
        } else {
            $this->display();
        }
    }
    public function searchlog()
    {
        $xzv_60 = M('searchlog');
        $xzv_99 = 100;
        $xzv_100 = I('get.p', 1, 'intval');
        $xzv_38 = $xzv_60->order('hasresult asc,num desc,id desc')->limit(($xzv_100 - 1) * $xzv_99, $xzv_99)->select();
        $xzv_142 = $xzv_60->Count();
        $xzv_103 = pagelist_thinkphp($xzv_142, $xzv_99);
        $this->assign('searchloglist', $xzv_38);
        $this->assign('pagehtml', $xzv_103);
        $this->display();
    }
    public function seowords()
    {
        $xzv_57 = I('param.');
        $xzv_144 = $xzv_57['action'];
        $this->assign('action', $xzv_144);
        $xzv_138 = M('seowords');
        if ($xzv_144 == 'add' || $xzv_144 == 'edit') {
            if ($xzv_144 == 'edit') {
                $xzv_8 = intval($xzv_57['id']);
                $xzv_102 = $xzv_138->where('id=%d', $xzv_8)->find();
            }
            $this->assign('seodb', $xzv_102);
            if ($xzv_57['do'] == 'save') {
                $xzv_88 = $xzv_57['ename'];
                if ($xzv_144 == 'add') {
                    $xzv_32 = array();
                    $xzv_31 = explode('
', $xzv_57['sitename']);
                    $xzv_82 = explode('
', $xzv_57['ename']);
                    if (count($xzv_31) != count($xzv_82)) {
                        $this->error('站点名称数量和ename数量不一致，必须一一对应');
                    }
                    if (count($xzv_31) > 0) {
                        foreach ($xzv_31 as $xzv_28 => $xzv_150) {
                            if ($xzv_150) {
                                $xzv_27 = trim($xzv_82[$xzv_28]);
                                if (!preg_match('/^[0-9a-zA-Z]{2,50}$/', $xzv_27)) {
                                    $this->error('ename为“' . $xzv_27 . '”只能包含字母和数字，不支持汉字和符号');
                                }
                                $xzv_97 = $xzv_138->where("ename='%s'", $xzv_27)->find();
                                if ($xzv_97) {
                                    $this->error('已存在“' . $xzv_27 . '”的关键词');
                                }
                                $xzv_32[] = array('sitename' => $xzv_150, 'ename' => $xzv_27, 'title' => str_replace('{name}', $xzv_150, $xzv_57['title']), 'keywords' => str_replace('{name}', $xzv_150, $xzv_57['keywords']), 'description' => str_replace('{name}', $xzv_150, $xzv_57['description']));
                            }
                        }
                    } else {
                        $xzv_32[] = array('sitename' => $xzv_57['sitename'], 'ename' => $xzv_57['ename'], 'title' => str_replace('{name}', $xzv_57['sitename'], $xzv_57['title']), 'keywords' => str_replace('{name}', $xzv_57['sitename'], $xzv_57['keywords']), 'description' => str_replace('{name}', $xzv_57['sitename'], $xzv_57['description']));
                    }
                    $xzv_138->addAll($xzv_32);
                } else {
                    $xzv_152 = array('sitename' => $xzv_57['sitename'], 'ename' => $xzv_57['ename'], 'title' => str_replace('{name}', $xzv_57['sitename'], $xzv_57['title']), 'keywords' => str_replace('{name}', $xzv_57['sitename'], $xzv_57['keywords']), 'description' => str_replace('{name}', $xzv_57['sitename'], $xzv_57['description']));
                    $xzv_97 = $xzv_138->where('id=%d', $xzv_57['id'])->find();
                    if (!$xzv_97) {
                        $this->error('出错了，关键词不存在');
                    }
                    $xzv_138->where('id=%d', $xzv_57['id'])->save($xzv_152);
                }
                F('seowords', null);
                $this->success('操作成功', U('seowords'));
            } else {
                $this->display();
            }
        } elseif ($xzv_144 == 'del') {
            $xzv_8 = I('get.id', '', 'intval');
            $xzv_138->where('id=%d', $xzv_8)->delete();
            $this->success('删除成功', U('seowords'));
        } else {
            $xzv_85 = 100;
            $xzv_3 = I('get.p', 1, 'intval');
            $xzv_105 = $xzv_138->order('views desc,id desc')->limit(($xzv_3 - 1) * $xzv_85, $xzv_85)->select();
            $xzv_146 = $xzv_138->Count();
            $xzv_86 = pagelist_thinkphp($xzv_146, $xzv_85);
            $this->assign('seowordlist', $xzv_105);
            $this->assign('pagehtml', $xzv_86);
            $this->display();
        }
    }
    public function advertise()
    {
        $xzv_10 = I('action');
        $xzv_53 = I('post.');
        $xzv_95 = F('advertise');
        $xzv_131 = F('advertise_extend');
        if (!$xzv_10) {
            $this->assign('advcode', $xzv_95);
            $this->assign('advcode_extend', $xzv_131);
            $this->display();
        } else {
            foreach ($xzv_53['id'] as $xzv_104 => $xzv_64) {
                if (!$xzv_64) {
                    continue;
                }
                $xzv_95[$xzv_64] = array('id' => $xzv_64, 'title' => $xzv_53['title'][$xzv_104], 'code' => htmlspecialchars_decode($xzv_53['code'][$xzv_104]), 'code_wap' => htmlspecialchars_decode($xzv_53['code_wap'][$xzv_104]));
            }
            if (is_array($xzv_53['eid'])) {
                foreach ($xzv_53['eid'] as $xzv_98 => $xzv_39) {
                    if (!$xzv_39) {
                        continue;
                    }
                    if ($xzv_53['edel'][$xzv_98]) {
                        unset($xzv_131[$xzv_39]);
                        continue;
                    }
                    $xzv_131[$xzv_39] = array('id' => $xzv_39, 'title' => $xzv_53['etitle'][$xzv_98], 'code' => htmlspecialchars_decode($xzv_53['ecode'][$xzv_98]), 'code_wap' => htmlspecialchars_decode($xzv_53['ecode_wap'][$xzv_98]));
                }
            }
            if (is_array($xzv_53['nid'])) {
                foreach ($xzv_53['nid'] as $xzv_101 => $xzv_62) {
                    if (!$xzv_62) {
                        continue;
                    }
                    if ($xzv_131['extend_' . $xzv_62]) {
                        $this->error('已存在名为：extend_' . $xzv_62 . '的广告位');
                    }
                    $xzv_131['extend_' . $xzv_62] = array('id' => 'extend_' . $xzv_62, 'title' => $xzv_53['ntitle'][$xzv_101], 'code' => htmlspecialchars_decode($xzv_53['ncode'][$xzv_101]), 'code_wap' => htmlspecialchars_decode($xzv_53['ncode_wap'][$xzv_101]));
                }
            }
            F('advertise', $xzv_95);
            F('advertise_extend', $xzv_131);
            $this->success('操作成功');
        }
    }
    public function pickers()
    {
        $xzv_61 = M('article_pickers');
        $xzv_22 = 100;
        $xzv_68 = I('get.p', 1, 'intval');
        $xzv_94 = $xzv_61->alias('p')->join(C('DB_PREFIX') . 'articles a ON a.id = p.aid')->order('p.id desc')->limit(($xzv_68 - 1) * $xzv_22, $xzv_22)->field('p.url,p.pid,a.title,p.updatetime,p.id')->select();
        $xzv_135 = $xzv_61->Count();
        $xzv_107 = pagelist_thinkphp($xzv_135, $xzv_22);
        $this->assign('pickerlist', $xzv_94);
        $this->assign('pagehtml', $xzv_107);
        $this->display();
    }
}