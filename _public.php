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
$core->tpl->addValue('Breadcrumb',array('tplBreadcrumb','breadcrumb'));

class tplBreadcrumb
{
	# Template function
	public static function breadcrumb($attr)
	{
		$separator = isset($attr['separator']) ? $attr['separator'] : '';
		
		return '<?php echo tplBreadcrumb::displayBreadcrumb('.
				"'".addslashes($separator)."'".
			'); ?>';
	}
	
	public static function displayBreadcrumb($separator)
	{
		global $core,$_ctx;
		
		$ret = '';
		if ($separator == '') $separator = ' &rsaquo; ';
		
		switch ($core->url->type) {
			
			case 'default':
				// Home (first page only)
				$ret = '<span id="bc-home">'.__('Home').'</span>';
				break;
				
			case 'default-page':
				// Home`(page 2 to n)
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$ret .= $separator.sprintf(__('page %d'),$GLOBALS['_page_number']);
				break;
				
			case 'category':
				// Category
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$categories = $core->blog->getCategoryParents($_ctx->categories->cat_id);
				while ($categories->fetch()) {
					$ret .= $separator.'<a href="'.$core->blog->url.$core->url->getBase('category')."/".$categories->cat_url.'">'.$categories->cat_title.'</a>';
				}
				$ret .= $separator.$_ctx->categories->cat_title;
				break;
				
			case 'post':
				// Post
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				if ($_ctx->posts->cat_id) {
					// Parents cats of post's cat
					$categories = $core->blog->getCategoryParents($_ctx->posts->cat_id);
					while ($categories->fetch()) {
						$ret .= $separator.'<a href="'.$core->blog->url.$core->url->getBase('category')."/".$categories->cat_url.'">'.$categories->cat_title.'</a>';
					}
					// Post's cat
					$categories = $core->blog->getCategory($_ctx->posts->cat_id);
					$ret .= $separator.'<a href="'.$core->blog->url.$core->url->getBase('category')."/".$categories->cat_url.'">'.$categories->cat_title.'</a>';
				}
				$ret .= $separator.$_ctx->posts->post_title;
				break;
				
			case 'lang':
				// Lang
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$langs = l10n::getISOCodes();
				$ret .= $separator.(isset($langs[$_ctx->cur_lang]) ? $langs[$_ctx->cur_lang] : $_ctx->cur_lang);
				break;
				
			case 'archive':
				// Archives
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				if (!$_ctx->archives) {
					// Global archives
					$ret .= $separator.__('Archives');
				} else {
					// Month archive
					$ret .= $separator.'<a href="'.$core->blog->url.$core->url->getBase("archive").'">'.__('Archives').'</a>';
					$ret .= $separator.dt::dt2str('%B %Y',$_ctx->archives->dt);
				}
				break;
				
			case 'pages':
				// Page
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$ret .= $separator.$_ctx->posts->post_title;
				break;
				
			case 'tags':
				// All tags
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$ret .= $separator.__('All tags');
				break;
				
			case 'tag':
				// Tag
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$ret .= $separator.'<a href="'.$core->blog->url.$core->url->getBase("tags").'">'.__('All tags').'</a>';
				$ret .= $separator.$_ctx->meta->meta_id;
				break;

			case 'search':
				// Search
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$ret .= $separator.__('Search:').' '.$GLOBALS['_search'];
				break;
				
			case '404':
				// 404
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				$ret .= $separator.__('404');
				break;
				
			default:
				$ret = '<a id="bc-home" href="'.$core->blog->url.'">'.__('Home').'</a>';
				# --BEHAVIOR-- publicBreadcrumb
				# Should specific breadcrumb if any, will be added after home page url
				$special = $core->callBehavior('publicBreadcrumb',$core->url->type,$separator);
				if ($special) {
					$ret .= $separator.$special;
				}
				break;
		}
		
		return $ret;
	}
}

?>