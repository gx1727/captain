<?php
/**
 * code类使用
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/2/28
 * Time: 14:34
 */

namespace captain\system;
defined('CAPTAIN') OR exit('No direct script access allowed');

use \captain\core\Model;
use \captain\core\Ret;

class codeModel extends Model
{
    function __construct()
    {
        parent::__construct(__NAMESPACE__, 'system');
        $this->table_name = CAPTAIN_CODE_DEFINITION;
        $this->key_id = 'cd_name';

        /**
         * 对模块的返回值进行扩展
         */
        $this->return_status[1] = "编码名称不存在";
        $this->return_status[2] = "编码不存在";
        $this->return_status[3] = "编码没有找到";
    }

    /**
     * 从数据库获取流水编码数据
     * @param $cd_name 编码名称
     * @param $ci_usercode 用户编码 SYS:系统编码
     * @return bool
     */
    function get_code_info($cd_name, $ci_usercode)
    {
        $sql = "SELECT * FROM " . CAPTAIN_CODE_INFO . " WHERE cd_name = ? AND ci_usercode = ?";
        return $this->db->rawQueryOne($sql, array($cd_name, $ci_usercode));
    }

    /**
     * 流水编码
     * 更新数据
     */
    function update_code_info($ci_id, $data)
    {
        $this->edit($ci_id, $data, CAPTAIN_CODE_INFO, 'ci_id');
    }

    /**
     * 流水编码  插入新数据
     * @param $data
     */
    function insert_code_info($data)
    {
        $this->add($data, CAPTAIN_CODE_INFO);
    }

    /////////////////////////////////////////////////////
    /// 检索编码表
    /**
     * 获取编码数据
     * @param $codeName
     * @return Ret
     */
    public function get_code_date($codeName, $where, $param)
    {
        $ret = new Ret($this->return_status);

        $tableName = CAPTAIN_MATERIAL . '_' . strtolower($codeName);
        $sql = "SELECT * FROM " . $tableName . " WHERE " . $where;
        $row = $this->db->rawQueryOne($sql, $param);
        if ($row) {
            $ret->set_code(0);
            $ret->set_data($row);
        } else {
            $ret->set_code(3); //编码没有找到
        }
        return $ret;
    }

    /**
     * 索引编码中使用
     * @param $codeName
     * @param $nodeName
     * @param $val
     * @return mixed
     */
    function get_code_node($codeName, $nodeName, $val)
    {
        $ret = new Ret($this->return_status);

        $tableName = strtolower(CAPTAIN_MATERIAL . '_' . $codeName . '_' . $nodeName);
        $sql = "SELECT * FROM $tableName WHERE synonym LIKE '%{[" . $val . "]}%'";
        $row = $this->db->rawQueryOne($sql);
        if ($row) {
            $ret->set_code(0);
            $ret->set_data($row);
        } else {
            $sql = "SELECT * FROM $tableName WHERE status = 0 ORDER BY node_id";
            $row = $this->db->rawQueryOne($sql);
            if ($row) {
                $synonym = '{[' . $val . ']}';
                $sql = "UPDATE $tableName SET value = '$val', synonym = '$synonym', status = 1 WHERE node_id = " . $row['node_id'];
                $this->db->query($sql);

                return $this->get_code_node($codeName, $nodeName, $val);
            }
        }

        return $ret;
    }

    /**
     * 处理检索编码的数据表
     * @param $code_definition
     */
    public function manage_code_table($code_definition)
    {
        $attributeset = json_decode($code_definition['cd_attributeset'], true);
        $table_name = strtolower(CAPTAIN_MATERIAL . '_' . $code_definition['cd_name']);

        $field = array();
        foreach ($attributeset as $k => $node) {
            if ($node['type'] == 'PREFIX') {
                continue;
            }
            //创建表
            $nodeTableName = strtolower($table_name . "_" . $node['name']);
            $sql = "
                CREATE TABLE IF NOT EXISTS `$nodeTableName` (
                  `node_id` int(10) unsigned NOT NULL auto_increment,
                  `code` varchar(8) NOT NULL default '' COMMENT '子属性编码',
                  `value` varchar(128) NOT NULL default '' COMMENT '子属性值',
                  `synonym` varchar(1024) NOT NULL default '' COMMENT '子属性值同义词，用<>括起',
                  `remark` varchar(1024) NOT NULL default '' COMMENT '子属性说明',
                  `status` tinyint(2) NOT NULL default '0' COMMENT '子属性状态 0:未使用  1:已使用',
                  PRIMARY KEY  (`node_id`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='编码子属性表' AUTO_INCREMENT=1 ;
                ";
            $this->db->query($sql);

            //清空表数据
            $this->clean_db($nodeTableName);

            //初始表数据
            $this->init_code_table($node, $nodeTableName);

            $field[] = "`" . $node['name'] . "` varchar(128) NULL default '' COMMENT '" . strtolower($node['name']) . "'";
        }


        //创建编码表
        $sql = "
        CREATE TABLE IF NOT EXISTS `$table_name` (
          `code_id` int(10) unsigned NOT NULL auto_increment,
          `code` varchar(32) NOT NULL default '' COMMENT '编码 12位',
          `real_code` varchar(32) NOT NULL default '' COMMENT '指向真正的编码 12位',
          " . implode(',', $field) . ",
          `status` tinyint(2) NOT NULL default '0' COMMENT '编码状态 0:临时 1:正常 2:索引 3:永久临时',
          PRIMARY KEY  (`code_id`),
          UNIQUE KEY `code_a` (`code`)
        ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='编码表' AUTO_INCREMENT=1 ;
        ";
        $this->db->query($sql);

        //清空表数据
        $this->clean_db($table_name);
    }

    /**
     * @param $codeName
     * @param $data
     */
    function insert_code_node($codeName, $data)
    {
        $tableName = strtolower(CAPTAIN_MATERIAL . '_' . $codeName);

        $sql = "SELECT * FROM " . $tableName . " WHERE code = ?";
        $query = $this->db->rawQueryOne($sql, array($data['code']));
        if (!$query) {
            $this->add($data, $tableName);
        }
    }

    /**
     * 初始表数据
     * 索引编码中，创建原始数据
     * @param $node
     * @param $nodeTableName
     */
    private function init_code_table($node, $nodeTableName)
    {
        if ($node['type'] == "HASH") {
            $node_code = "";
            $this->insert_node_table($node['length'] - 1, $node_code, $node['base'], $nodeTableName);
        } else if ($node['type'] == "SEQUENCE") {
            $index = $node['base'];
            $max = pow(10, $node['length']);

            while ($index < $max) {
                $code_node = $this->get_serial_number($index, $node['length']);
                $sql = "insert into $nodeTableName values (null, '$code_node', '', '', '', 0)";
                $this->db->query($sql);
                $index++;
            }
        }
    }

    /**
     * 索引编码中,穷举所以组合
     * @param $count
     * @param $node_code
     * @param $baseChar
     * @param $nodeTableName
     */
    private function insert_node_table($count, $node_code, $baseChar, $nodeTableName)
    {
        for ($i = 0; $i < strlen($baseChar); $i++) {
            $node = $node_code . $baseChar[$i];
            if ($count > 0) {
                $this->insert_node_table($count - 1, $node, $baseChar, $nodeTableName);
            } else {
                $sql = "insert into $nodeTableName values (null, '$node', '', '', '', 0)";
                $this->db->query($sql);
            }
        }
    }

    /**
     * 清空数据库表中的数据
     * 如果表不存在
     * @param $tableName
     */
    private function clean_db($tableName)
    {
        $sql = "TRUNCATE TABLE `$tableName";
        $this->db->query($sql);
    }

    /**
     * 获取补0的流水字符串
     * @param $v
     * @param $len
     * @return string
     */
    private function get_serial_number($v, $len)
    {
        $serial = "0000000000000000000000000000000000000000000000000" . $v;
        return substr($serial, -$len);
    }
}