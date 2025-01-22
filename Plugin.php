<?php
/**
 * AdsAndStats Plugin for Typecho
 * 
 * @package AdsAndStats 
 * @author flyhunterl
 * @version 1.0.1
 * @link https://www.llingfei.com
 */
if (!defined('__TYPECHO_ROOT_DIR__')) exit;

class AdsAndStats_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->header = array('AdsAndStats_Plugin', 'renderHeader');
        Typecho_Plugin::factory('Widget_Archive')->footer = array('AdsAndStats_Plugin', 'renderFooter');
        return _t('插件已激活');
    }

    /**
     * 禁用插件
     */
    public static function deactivate()
    {
        return _t('插件已禁用');
    }

    /**
     * 插件配置面板
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        // 添加统计功能开关
        $enableStats = new Typecho_Widget_Helper_Form_Element_Checkbox('enableStats',
            array(
                'google' => _t('启用 Google Analytics'),
                'baidu' => _t('启用百度统计')
            ),
            array('google', 'baidu'), _t('启用统计功能'));
        $form->addInput($enableStats);

        // Google Analytics
        $gaCode = new Typecho_Widget_Helper_Form_Element_Text('gaCode', NULL, NULL,
            _t('Google Analytics 跟踪ID'),
            _t('格式如：UA-XXXXX-Y'));
        $form->addInput($gaCode);

        // 百度统计
        $baiduCode = new Typecho_Widget_Helper_Form_Element_Text('baiduCode', NULL, NULL,
            _t('百度统计 跟踪ID'),
            _t('格式如：xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx'));
        $form->addInput($baiduCode);

        // 添加广告功能开关
        $enableAds = new Typecho_Widget_Helper_Form_Element_Radio('enableAds',
            array(
                '1' => _t('启用'),
                '0' => _t('禁用')
            ),
            '1', _t('启用广告功能'), _t('选择是否启用 Google Adsense 广告'));
        $form->addInput($enableAds);

        // Google Adsense
        $adsenseCode = new Typecho_Widget_Helper_Form_Element_Text('adsenseCode', NULL, NULL,
            _t('Google Adsense 发布商ID'),
            _t('格式如：ca-pub-xxxxxxxxxxxxxxxx'));
        $form->addInput($adsenseCode);

        // 广告插入位置设置
        $insertLocations = new Typecho_Widget_Helper_Form_Element_Checkbox('insertLocations', 
            array(
                'header' => _t('所有页面的头部'),
                'index' => _t('首页'),
                'post' => _t('文章页面'),
                'page' => _t('独立页面')
            ), array('header'), _t('广告插入位置'));
        $form->addInput($insertLocations);
    }

    /**
     * 个人用户的配置面板
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 在header插入代码
     */
    public static function renderHeader()
    {
        $options = Helper::options();
        $settings = $options->plugin('AdsAndStats');
        $enableStats = $settings->enableStats ? $settings->enableStats : array();
        
        // 根据开关状态渲染统计代码
        if (in_array('google', $enableStats)) {
            self::renderGoogleAnalytics($settings);
        }
        if (in_array('baidu', $enableStats)) {
            self::renderBaiduStats($settings);
        }
    }

    /**
     * 在footer插入代码
     */
    public static function renderFooter()
    {
        $options = Helper::options();
        $settings = $options->plugin('AdsAndStats');
        
        // 如果广告功能被禁用，直接返回
        if (!$settings->enableAds) {
            return;
        }

        $locations = $settings->insertLocations ?: array();
        $widget = Typecho_Widget::widget('Widget_Archive');
        
        if (in_array('index', $locations) && $widget->is('index')) {
            self::renderAdsense($settings);
        }
        
        if (in_array('post', $locations) && $widget->is('post')) {
            self::renderAdsense($settings);
        }
        
        if (in_array('page', $locations) && $widget->is('page')) {
            self::renderAdsense($settings);
        }
    }

    /**
     * 渲染Google Analytics代码
     */
    private static function renderGoogleAnalytics($settings)
    {
        if (!empty($settings->gaCode)) {
            echo <<<HTML
<!-- Google Analytics -->
<script async src="https://www.googletagmanager.com/gtag/js?id={$settings->gaCode}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', '{$settings->gaCode}');
</script>
HTML;
        }
    }

    /**
     * 渲染百度统计代码
     */
    private static function renderBaiduStats($settings)
    {
        if (!empty($settings->baiduCode)) {
            echo <<<HTML
<!-- Baidu Analytics -->
<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "https://hm.baidu.com/hm.js?{$settings->baiduCode}";
  var s = document.getElementsByTagName("script")[0]; 
  s.parentNode.insertBefore(hm, s);
})();
</script>
HTML;
        }
    }

    /**
     * 渲染Google Adsense代码
     */
    private static function renderAdsense($settings)
    {
        if (!empty($settings->adsenseCode)) {
            echo <<<HTML
<!-- Google Adsense -->
<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client={$settings->adsenseCode}"
     crossorigin="anonymous"></script>
HTML;
        }
    }
}
