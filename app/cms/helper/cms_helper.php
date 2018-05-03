<?php defined('CAPTAIN') OR exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/2
 * Time: 16:55
 */

/**
 * 0：否
 * 1：是
 */
if (!function_exists('a_status')) {
    function a_status($k, $op = "show")
    {
        $class = array(
            '0' => '删除',
            '1' => '显示',
            '2' => '未发布，不显示',
            '3' => '草稿中'
        );
        if ($op == "option") {
            foreach ($class as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
            }
        } else if ($op == "class") {
            return $class;
        } else {
            return isset($class[$k]) ? $class[$k] : $k;
        }
    }
}

if (!function_exists('t_type')) {
    function t_type($k, $op = "show")
    {
        $class = array(
            'T' => '显示模板',
            'J' => 'js文件',
            'S' => 'style样式文件'
        );
        if ($op == "option") {
            foreach ($class as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
            }
        } else if ($op == "class") {
            return $class;
        } else {
            return isset($class[$k]) ? $class[$k] : $k;
        }
    }
}

if (!function_exists('t_class')) {
    function t_class($k, $op = "show")
    {
        $class = array(
            'A' => '文章',
            'L' => '列表',
            'S' => '专有',
            'C' => '组件'
        );
        if ($op == "option") {
            foreach ($class as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
            }
        } else if ($op == "class") {
            return $class;
        } else {
            return isset($class[$k]) ? $class[$k] : $k;
        }
    }
}

if (!function_exists('article_flag')) {
    function article_flag($k, $op = "show")
    {
        $class = array(
            'recommend' => '推荐',
            'top' => '置顶',
            'cover' => '封面',
            'special' => '特殊'
        );
        if ($op == "option") {
            foreach ($class as $k => $v) {
                echo '<option value="' . $k . '">' . $v . '</option>';
            }
        } else if ($op == "class") {
            return $class;
        } else {
            return isset($class[$k]) ? $class[$k] : $k;
        }
    }
}

/**
 * 栏目目录
 */
if (!function_exists('lanmu')) {
    function lanmu($lanmu = '')
    {
        echo component('cms:captain\cms\Article@lanmu', array($lanmu));
    }
}

/**
 * 分页
 */
if (!function_exists('paging')) {
    function paging($base_url, $index, $sum)
    {
        echo component('cms:captain\cms\Article@paging', array(
            $base_url, // 基础URL
            $index, // 当前页
            $sum // 总页数
        ));
    }
}

/**
 * 底部
 */
if (!function_exists('footer')) {
    function footer()
    {
        echo component('cms:captain\cms\Article@footer', array());
    }
}


/**
 * top
 */
if (!function_exists('top')) {
    function top($limit, $type = '', $lanmu = '')
    {
        echo component('cms:captain\cms\Article@top', array($limit, $type, $lanmu, 0));
    }
}

if (!function_exists('get_top')) {
    function get_top($limit, $type = '', $lanmu = '')
    {
        return component('cms:captain\cms\Article@top', array($limit, $type, $lanmu, 1));
    }
}

/**
 * 获取单篇文章
 * $code： 文章ID， 可为空
 * $sort：
 * $tag：
 * $flag：
 */
if (!function_exists('get_article')) {
    function get_article($code, $sort = '', $tag = '', $flag = '', $mode = 2, $orderby = 'new', $ordertype = 'desc')
    {
        if (func_num_args() == 1) {
            if ($code) {
                if (is_int($code)) {
                    return component('cms:captain\cms\Article@article', array($code, $sort, $tag, $flag, $mode, $orderby, $ordertype));
                } else {
                    $flag = $code;
                    return component('cms:captain\cms\Article@article', array('', $sort, $tag, $flag, $mode, $orderby, $ordertype));
                }
            }
        } else {
            return component('cms:captain\cms\Article@article', array($code, $sort, $tag, $flag, $mode, $orderby, $ordertype));
        }
    }
}

/**
 * 获取文章列表
 * $count: 数量
 * $sort：
 * $tag：
 * $mode： 0: 简单 1:简单+tags   2:明细
 * $orderby： new:  hot:
 */
if (!function_exists('get_articles')) {
    function get_articles($count, $sort = '', $tag = '', $flag = '', $mode = 0, $orderby = 'new', $ordertype = 'desc')
    {
        return component('cms:captain\cms\Article@articles', array($count, $sort, $tag, $flag, $mode, $orderby, $ordertype));
    }
}

if (!function_exists('get_specials')) {
    function get_specials($count, $flag = '')
    {
        return component('cms:captain\cms\Article@specials', array($count, $flag));
    }
}


/**
 *
 * $articles: 文章列表
 * $start: 开始位置
 * $length: 获取列表长度
 */
if (!function_exists('sub_articles')) {
    function sub_articles($articles, $start, $length)
    {
        $sub_articles = array();
        if (is_array($articles)) {
            foreach ($articles as $index => $node) {
                if ($length > 0 && $index >= ($start - 1)) {
                    $sub_articles[] = $node;
                    $length--;
                    if ($length <= 0) {
                        break;
                    }
                }
            }
        }
        return $sub_articles;
    }
}

/**
 *
 * $articles: 文章列表
 * $start: 开始位置
 * $length: 获取列表长度
 */
if (!function_exists('filter_articles')) {
    function filter_articles($articles, $filter, $length)
    {
        $filter_code = array();
        if ($filter) {
            if (isset($filter['code'])) {
                $filter_code[] = $filter['code'];
            } else {
                foreach ($filter as $filter_node) {
                    $filter_code[] = $filter_node['code'];
                }
            }
        }
        $filter_articles = array();
        if (is_array($articles)) {
            foreach ($articles as $index => $node) {
                if ($length > 0 && !in_array($node['code'], $filter_code)) {
                    $filter_articles[] = $node;
                    $length--;
                    if ($length <= 0) {
                        break;
                    }
                }
            }
        }
        return $filter_articles;
    }
}

