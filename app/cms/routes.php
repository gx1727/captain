<?php
/**
 * Created by PhpStorm.
 * User: gx1727
 * Date: 2018/4/3
 * Time: 9:24
 */

//$needless_context['/test'] = 'cms:captain\cms\Test@index';
$guest['/admin'] = 'cms:captain\cms\Manager@index';

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
$manager['/api/cms/tag/group'] = 'cms:captain\cms\Tag@tag_get_bygroup';

$manager['/api/cms/article/get'] = 'cms:captain\cms\Cms@article_get';
$manager['/api/cms/article/list'] = 'cms:captain\cms\Cms@article_list';
$manager['/api/cms/article/create'] = 'cms:captain\cms\Cms@article_create';
$manager['/api/cms/article/edit'] = 'cms:captain\cms\Cms@article_edit';
$manager['/api/cms/article/publish'] = 'cms:captain\cms\Cms@article_publish';
$manager['/api/cms/article/del'] = 'cms:captain\cms\Cms@article_del';
$manager['/api/cms/article/deldraft'] = 'cms:captain\cms\Cms@article_draft_del';

$manager['/api/cms/article/lanmu'] = 'cms:captain\cms\Sort@lanmu';

// 文章模板
$manager['/api/cms/template/get'] = 'cms:captain\cms\Template@template_get';
$manager['/api/cms/template/list'] = 'cms:captain\cms\Template@template_list';
$manager['/api/cms/template/form'] = 'cms:captain\cms\Template@template_form';
$manager['/api/cms/template/publish'] = 'cms:captain\cms\Template@template_publish';
$manager['/api/cms/template/backup'] = 'cms:captain\cms\Template@template_backup';
$manager['/api/cms/template/del'] = 'cms:captain\cms\Template@template_del';
$manager['/api/cms/template/select'] = 'cms:captain\cms\Template@template_select';

// 专题
$manager['/api/cms/special/get'] = 'cms:captain\cms\Special@special_get';
$manager['/api/cms/special/list'] = 'cms:captain\cms\Special@special_list';
$manager['/api/cms/special/form'] = 'cms:captain\cms\Special@special_form';
$manager['/api/cms/special/del'] = 'cms:captain\cms\Special@special_del';

//编辑人员管理
$admin['/api/cms/editor/get'] = 'cms:captain\cms\Editor@get_editor';
$admin['/api/cms/editor/list'] = 'cms:captain\cms\Editor@alist';
$admin['/api/cms/editor/form'] = 'cms:captain\cms\Editor@form';
$admin['/api/cms/editor/del'] = 'cms:captain\cms\Editor@del';
$admin['/api/cms/editor/auth'] = 'cms:captain\cms\Editor@auth'; // 权限
$admin['/api/cms/editor/info'] = 'cms:captain\cms\Editor@info'; // 编辑人员的文章信息

$admin['/api/rv/brands/list'] = 'cms:captain\cms\Rv@brands_list';
$admin['/api/rv/brands/all'] = 'cms:captain\cms\Rv@brands_all';
$admin['/api/rv/rv/list'] = 'cms:captain\cms\Rv@rv_list';
$admin['/api/rv/rv/brands'] = 'cms:captain\cms\Rv@rv_get_by_brands';
$admin['/api/rv/rv/get'] = 'cms:captain\cms\Rv@rv_get';
$admin['/api/rv/rv/form'] = 'cms:captain\cms\Rv@rv_form';
$admin['/api/rv/rv/del'] = 'cms:captain\cms\Rv@rv_del';
$admin['/api/rv/model/list'] = 'cms:captain\cms\Rv@model_list';
$admin['/api/rv/model/get'] = 'cms:captain\cms\Rv@model_get';
$admin['/api/rv/model/form'] = 'cms:captain\cms\Rv@model_form';

$admin['/preview/*'] =  'cms:captain\cms\Article@preview';
$admin['/preview/template/*'] = 'cms:captain\cms\Template@preview'; // 模板预览
