<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 9:24
 */

// 文章相关
$manager['/api/cms/sort/tree'] = 'cms:captain\cms\Sort@get_sort_tree';
$admin['/api/cms/sort/form'] = 'cms:captain\cms\Sort@form_sort';
$admin['/api/cms/sort/get'] = 'cms:captain\cms\Sort@get_sort';
$admin['/api/cms/sort/del'] = 'cms:captain\cms\Sort@del_sort';

$manager['/api/cms/tag_group/list'] = 'cms:captain\cms\TagGroup@tag_group_list';
$admin['/api/cms/tag_group/add'] = 'cms:captain\cms\TagGroup@tag_group_add';
$admin['/api/cms/tag_group/edit'] = 'cms:captain\cms\TagGroup@tag_group_edit';
$admin['/api/cms/tag_group/del'] = 'cms:captain\cms\TagGroup@tag_group_del';

$manager['/api/cms/tag/list'] = 'cms:captain\cms\Tag@tag_list';
$admin['/api/cms/tag/add'] = 'cms:captain\cms\Tag@tag_add';
$admin['/api/cms/tag/edit'] = 'cms:captain\cms\Tag@tag_edit';
$admin['/api/cms/tag/del'] = 'cms:captain\cms\Tag@tag_del';
$manager['/api/cms/tag/get'] = 'cms:captain\cms\Tag@tag_get';

$manager['/api/cms/article/get'] = 'cms:captain\cms\Cms@article_get';
$manager['/api/cms/article/list'] = 'cms:captain\cms\Cms@article_list';
$manager['/api/cms/article/create'] = 'cms:captain\cms\Cms@article_create';
$manager['/api/cms/article/edit'] = 'cms:captain\cms\Cms@article_edit';
$manager['/api/cms/article/publish'] = 'cms:captain\cms\Cms@article_publish';
$manager['/api/cms/article/del'] = 'cms:captain\cms\Cms@article_del';
