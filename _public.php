<?php
# -- BEGIN LICENSE BLOCK ---------------------------------------
#
# This file is part of Dotclear 2.
#
# Copyright (c) 2003-2011 Olivier Meunier & Association Dotclear
# Licensed under the GPL version 2.0 license.
# See LICENSE file or
# http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
#
# -- END LICENSE BLOCK -----------------------------------------
if (!defined('DC_RC_PATH')) { return; }

# Breadcrumb template functions
$core->tpl->addValue('Breadcrumb',array('tplBreadcrumb','breadrumb'));

class tplBreadcrumb
{
	# Template function
	public static function breadcrumb($attr)
	{
		$class = isset($attr['separator']) ? trim($attr['separator']) : '';
		
		return '<?php echo tplBreadcrumb::displayBreadcrumb('.
				"'".addslashes($separator)."'".
			'); ?>';
	}
	
	public static function displayBreadcrumb($separator = ' &rsaquo; ')
	{
		$ret = '';

		switch ($core->url->type) {

			case 'default':
			case 'default-page':
				// Home
				$ret = __('Home');
				break;

			case 'category':
				// Category
				$ret = '<a href="'.$core->blog->url.'">'.__('Home').'</a>';
				$categories = $core->blog->getCategoryParents($_ctx->categories->cat_id);
				while ($categories->fetch()) {
					$ret .= $separator.'<a href="'.$core->blog->url.$core->url->getBase('category')."/".$categories->cat_url.'">'.$categories->cat_title.'</a>';
				}
				$ret .= $separator.$_ctx->categories->cat_title;
				break;

			case 'post':
				// Post
				$ret = '<a href="'.$core->blog->url.'">'.__('Home').'</a>';
				$categories = $core->blog->getCategoryParents($_ctx->posts->cat_id);
				while ($categories->fetch()) {
					$ret .= $separator.'<a href="'.$core->blog->url.$core->url->getBase('category')."/".$categories->cat_url.'">'.$categories->cat_title.'</a>';
				}
				$ret .= $separator.$_ctx->posts->post_title;
				break;
				
			default:
				$special = '';
				# --BEHAVIOR-- publicBreadcrumb
				# Should return $special filled with specific breadcrumb, will be added after home page url
				$core->callBehavior('publicBreadcrumb',$core->url->type,$separator,$special);
				if ($special) {
					$ret = $ret = '<a href="'.$core->blog->url.'">'.__('Home').'</a>'.$separator.$special;
				}
				break;
		}
		
		if ($ret != '') {
			$ret = '<p id="breadcrumb">'.$ret.'</p>';
		} else {
			$ret = '<p id="breadcrumb">'.$core->url->type.'</p>';
		}
		
		return $ret;
	}
}

?>