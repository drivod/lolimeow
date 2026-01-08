<?php
/**
 * @link https://www.boxmoe.com
 * @package lolimeow
 */

//boxmoe.com===安全设置=阻止直接访问主题文件
if(!defined('ABSPATH')){
    echo'Look your sister';
    exit;
}
//时区设置
#date_default_timezone_set('Asia/Shanghai');


//boxmoe.com===加载面板
define( 'OPTIONS_FRAMEWORK_DIRECTORY', get_template_directory_uri() . '/core/panel/' );
require_once dirname( __FILE__ ) . '/core/panel/options-framework.php';
require_once dirname( __FILE__ ) . '/options.php';
require_once dirname( __FILE__ ) . '/core/panel/options-framework-js.php';
//boxmoe.com===功能模块
require_once  get_stylesheet_directory() . '/core/module/fun-basis.php';
require_once  get_stylesheet_directory() . '/core/module/fun-admin.php';
require_once  get_stylesheet_directory() . '/core/module/fun-optimize.php';
require_once  get_stylesheet_directory() . '/core/module/fun-gravatar.php';
require_once  get_stylesheet_directory() . '/core/module/fun-navwalker.php';
require_once  get_stylesheet_directory() . '/core/module/fun-user.php';
require_once  get_stylesheet_directory() . '/core/module/fun-user-center.php';
require_once  get_stylesheet_directory() . '/core/module/fun-comments.php';
require_once  get_stylesheet_directory() . '/core/module/fun-seo.php';
require_once  get_stylesheet_directory() . '/core/module/fun-article.php';
require_once  get_stylesheet_directory() . '/core/module/fun-smtp.php';
require_once  get_stylesheet_directory() . '/core/module/fun-msg.php';
require_once  get_stylesheet_directory() . '/core/module/fun-no-category.php';
require_once  get_stylesheet_directory() . '/core/module/fun-shortcode.php';
//boxmoe.com===自定义代码


/**
 * 精准控度修复：只移除插件多套的转义，保留代码本身的转义实体
 * 解决代码内容被过度解码、篡改的问题
 */
function fix_githuber_md_precise_escape_only( $content ) {
    // 匹配所有<pre><code>代码块（保留所有标签属性）
    $pattern = '/(<pre\s*[^>]*?>)\s*<code\s*[^>]*?>(.*?)<\/code>\s*(<\/pre>)/is';
    
    $content = preg_replace_callback( $pattern, function( $matches ) {
        $pre_tag = $matches[1];
        $code_content = $matches[2];
        $pre_close = $matches[3];

        // 核心：只解码「插件多套的那一层转义」，保留代码本身的转义实体
        // 只处理 &amp;amp; → &amp;  |  &amp;#039; → &#039;  |  &amp;quot; → &quot;
        $code_content = str_replace(
            array('&amp;amp;', '&amp;#039;', '&amp;quot;', '&amp;lt;', '&amp;gt;'),
            array('&amp;',    '&#039;',    '&quot;',    '&lt;',    '&gt;'),
            $code_content
        );

        // 重新拼接：保留所有标签属性 + 还原后的代码内容
        return $pre_tag . '<code>' . $code_content . '</code>' . $pre_close;
    }, $content );
    
    return $content;
}

// 优先级20：确保在插件处理完后执行
add_filter( 'the_content', 'fix_githuber_md_precise_escape_only', 20 );
add_filter( 'the_excerpt', 'fix_githuber_md_precise_escape_only', 20 );


