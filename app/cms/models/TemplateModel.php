<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018-04-29
 * Time: 上午 8:19
 */

namespace captain\cms;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class TemplateModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'cms');
        $this->table_name = CMS_TEMPLATE;
        $this->key_id = 't_id';

        $this->return_status[1] = "模板不存在";
        $this->return_status[2] = "模板名已存在，不能新建该模板";
    }

    /**
     * 获到所有TAG分组
     * @param $keyword
     * @return Ret
     */
    public function get_template_list($list_param, $keyword)
    {
        $param_array = array();
        $sql = "from " . $this->table_name . ' where t_status = 0 ';

        if ($keyword !== false) {
            $sql .= ' and (t_name like ?';
            $sql .= ' or t_title like ?)';
            $param_array[] = '%' . $keyword . '%';
            $param_array[] = '%' . $keyword . '%';
        }

        $start = ($list_param['page'] - 1) * $list_param['pagesize'];

        if (!$list_param['orderby']) {
            $list_param['orderby'] = 't_id';
            $list_param['ordertype'] = 'desc';
        }
        return $this->get_page_list($this->return_status, $sql, $param_array, $start, (int)$list_param['pagesize'], $list_param['orderby'], $list_param['ordertype']);
    }

    /**
     * 获取模板
     * @param $t_id
     * @param bool $history 是否需要返回历史数据
     * @return mixed
     */
    public function get_template($t_id, $history = false)
    {
        $sql = 'select * from ' . $this->table_name . ' where t_id = ? and t_status != 1';
        $template = $this->query($sql, array($t_id));
        if ($template && $history) {
            $sql = 'select * from ' . $this->table_name . ' where t_name = ? and t_status > 1 order by t_id desc';
            $history = $this->query($sql, array($template['t_name']), false);
            if ($history) {
                foreach ($history as &$history_item) {
                    $history_item['atime'] = date('Y-m-d H:i:s', $history_item['t_atime']);
                }
                $template['history'] = $history;
            } else {
                $template['history'] = array();
            }

        }
        return $template;
    }

    /**
     * 新增模板
     * @param $t_name
     * @param $t_title
     * @param $t_type
     * @param $t_class
     * @param $t_path
     * @param $t_des
     * @param $t_content
     * @return Ret|mixed
     */
    public function add_template($t_name, $t_title, $t_type, $t_class, $t_path, $t_des, $t_content)
    {
        $template = $this->get($t_name, $this->table_name, 't_name');
        if ($template) {
            return (new Ret($this->return_status, 2));
        } else {
            $data = array(
                't_name' => $t_name,
                't_title' => $t_title,
                't_type' => $t_type,
                't_class' => $t_class,
                't_path' => $t_path,
                't_des' => $t_des,
                't_content' => $t_content,
                't_atime' => time()
            );
            return $this->add($data);
        }
    }

    public function edit_template($t_id, $data)
    {
        $old_template = $this->get($t_id);
        if (isset($data['t_name']) && $old_template['t_name'] != $data['t_name']) { // 模板名有变更，调整所有相关的模板
            $sql = 'update ' . $this->table_name . ' set t_name = \'' . $data['t_name'] . '\' where t_name = \'' . $old_template['t_name'] . '\'';
            $this->query($sql);
        }
        return $this->edit($t_id, $data);
    }

    public function publish_template($t_id)
    {
        $ret = new Ret($this->return_status);
        $template = $this->get($t_id);
        if ($template) {
            if ($template['t_type'] == 'T') {
                $class = $template['t_class'];
                if ($class == 'A') {
                    $class = 'article';
                } else if ($class == 'L') {
                    $class = 'list';
                } else if ($class == 'S') {
                    $class = 'special';
                } else if ($class == 'C') {
                    $class = 'component';
                }
                $template_content = $template['t_content'];
                $cache_article_path = BASEPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cms' .
                    DIRECTORY_SEPARATOR . 'views' . DIRECTORY_SEPARATOR . $class . DIRECTORY_SEPARATOR . $template['t_path'] . '.php';
                file_put_contents($cache_article_path, $template_content);
            } else {
                $type = $template['t_type'];
                if ($type == 'J') {
                    $type = 'js';
                } else if ($type == 'S') {
                    $type = 'css';
                }
                $template_content = $template['t_content'];
                $cache_article_path = BASEPATH . DIRECTORY_SEPARATOR . 'static' . DIRECTORY_SEPARATOR . $type . DIRECTORY_SEPARATOR . $template['t_path'] . '.' . $type;
                file_put_contents($cache_article_path, $template_content);
            }
            $ret->set_code(0);
        } else {
            $ret->set_code(1);
        }

        return $ret;
    }

    /**
     * 备份
     * @param $t_id
     * @return Ret|mixed
     */
    public function backup_template($t_id)
    {
        $ret = new Ret($this->return_status);
        $template = $this->get($t_id);
        if ($template) {
            $data = array(
                't_name' => $template['t_name'],
                't_title' => $template['t_title'],
                't_type' => $template['t_type'],
                't_class' => $template['t_class'],
                't_des' => $template['t_des'],
                't_content' => $template['t_content'],
                't_atime' => time(),
                't_status' => 2
            );
            return $this->add($data);
        } else {
            $ret->set_code(1);
        }

        return $ret;
    }

    public function del_template($t_id)
    {
        return $this->edit_template($t_id, array('t_status' => 1));
    }

    public function get_all_template()
    {
        $sql = 'select * from ' . $this->table_name . ' where t_status = 0 and t_type = \'T\'';
        $templates_ret = $this->query($sql, false, false);
        $templates = array(
            'A' => array(
                'title' => '文章',
                'template' => array()
            ),
            'L' => array(
                'title' => '列表',
                'template' => array()
            ),
            'S' => array(
                'title' => '专有',
                'template' => array()
            )
        );
        foreach ($templates_ret as $templates_item) {
            $templates[$templates_item['t_class']]['template'][] = $templates_item;
        }
        return $templates;
    }
    // ///////////////////////////////

    /**
     * 不要多次调用
     * @return array
     */
    public function get_templates()
    {
        $cache_file_path = BASEPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cms' .
            DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'templates.php';
        if (file_exists($cache_file_path)) {
            $templates = array();
            include $cache_file_path; //防止多次调用时， 返回空值
            return $templates;
        } else {
            $templates = $this->refresh_cache();
            return $templates;
        }
    }

    public function refresh_cache()
    {
        $sql = 'select * from ' . $this->table_name . ' where t_type = \'T\' and t_status = 0';
        $templates_ret = $this->query($sql, false, false);
        $templates = array();
        if ($templates_ret) {
            foreach ($templates_ret as $templates_node) {
                $t_class = '';
                if ($templates_node['t_class'] == 'A') {
                    $t_class = 'article/';
                } else if ($templates_node['t_class'] == 'L') {
                    $t_class = 'list/';
                } else if ($templates_node['t_class'] == 'S') {
                    $t_class = 'special/';
                }
                $templates[$templates_node['t_name']] = $t_class . $templates_node['t_path'];
            }
        }

        $cache_file_path = BASEPATH . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'cms' .
            DIRECTORY_SEPARATOR . 'cache' . DIRECTORY_SEPARATOR . 'templates.php';
        $templates_content = '<?php ';
        $templates_content .= '$templates = array (';
        foreach ($templates as $t_name => $t_path) {
            $templates_content .= '\'' . $t_name . '\' => \'' . $t_path . '\',';
        }
        $templates_content .= '); ';
        file_put_contents($cache_file_path, $templates_content);
        return $templates;
    }
}