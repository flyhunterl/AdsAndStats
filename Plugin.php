<?php
/**
 * AdsAndStats Plugin for Typecho
 * 
 * 功能说明：
 * 1. 支持Google Analytics和百度统计
 * 2. 支持Google Adsense自动广告
 * 3. 支持Google Adsense手动广告单元（多重广告单元和展示广告单元）
 * 
 * 注意事项：
 * 1. 使用手动广告单元时，请先在Google Adsense后台禁用自动广告功能
 * 2. 请勿在同一页面放置过多广告，以免影响用户体验
 * 
 * @package AdsAndStats 
 * @author flyhunterl
 * @version 1.0.2
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
        Typecho_Plugin::factory('Widget_Archive')->beforeRender = array('AdsAndStats_Plugin', 'renderBeforeContent');
        Typecho_Plugin::factory('Widget_Archive')->afterRender = array('AdsAndStats_Plugin', 'renderAfterContent');
        Typecho_Plugin::factory('Widget_Archive')->beforeComment = array('AdsAndStats_Plugin', 'renderBeforeComment');
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
        // 添加使用说明
        echo '<div style="background-color: #f9f9f9; padding: 10px; margin-bottom: 15px; border-left: 4px solid #3354aa;">
            <h3>广告功能使用说明：</h3>
            <p>1. <strong>自动广告</strong>：开启后会自动在合适位置展示广告</p>
            <p>2. <strong>手动广告单元</strong>：可以在指定位置展示特定的广告单元</p>
            <p style="color: #e74c3c;"><strong>重要提示</strong>：使用手动广告单元时，请先在Google Adsense后台禁用自动广告功能，否则可能会导致广告重叠显示！</p>
        </div>';

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
            '1', _t('启用广告功能'), 
            _t('选择是否启用 Google Adsense 广告。<br/>注意：如果使用手动广告单元，建议在Adsense后台关闭自动广告'));
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

        // 添加多重广告单元设置
        $multipleAdsCode = new Typecho_Widget_Helper_Form_Element_Textarea('multipleAdsCode', NULL, NULL,
            _t('多重广告单元代码'),
            _t('粘贴Google Adsense多重广告单元的完整代码。<br/>使用前请确保已在Adsense后台禁用自动广告'));
        $form->addInput($multipleAdsCode);

        // 添加展示广告单元设置
        $displayAdsCode = new Typecho_Widget_Helper_Form_Element_Textarea('displayAdsCode', NULL, NULL,
            _t('展示广告单元代码'),
            _t('粘贴Google Adsense展示广告单元的完整代码。<br/>使用前请确保已在Adsense后台禁用自动广告'));
        $form->addInput($displayAdsCode);

        // 广告单元插入位置设置
        $adsUnitLocations = new Typecho_Widget_Helper_Form_Element_Checkbox('adsUnitLocations', 
            array(
                'beforePost' => _t('文章内容前'),
                'afterPost' => _t('文章内容后'),
                'beforeComment' => _t('评论区前'),
                'sidebarTop' => _t('侧边栏顶部'),
                'sidebarBottom' => _t('侧边栏底部')
            ), 
            array('afterPost'), _t('广告单元插入位置'),
            _t('选择手动广告单元的显示位置'));
        $form->addInput($adsUnitLocations);
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

    /**
     * 渲染多重广告单元
     */
    private static function renderMultipleAds($settings)
    {
        if (!empty($settings->multipleAdsCode)) {
            echo "<!-- Multiple Ads Unit -->\n";
            echo $settings->multipleAdsCode;
        }
    }

    /**
     * 渲染展示广告单元
     */
    private static function renderDisplayAds($settings)
    {
        if (!empty($settings->displayAdsCode)) {
            echo "<!-- Display Ads Unit -->\n";
            echo $settings->displayAdsCode;
        }
    }

    /**
     * 在文章内容前渲染广告
     */
    public static function renderBeforeContent($content)
    {
        $options = Helper::options();
        $settings = $options->plugin('AdsAndStats');
        $locations = $settings->adsUnitLocations ?: array();
        
        if (in_array('beforePost', $locations)) {
            self::renderDisplayAds($settings);
        }
        
        return $content;
    }

    /**
     * 在文章内容后渲染广告
     */
    public static function renderAfterContent($content)
    {
        $options = Helper::options();
        $settings = $options->plugin('AdsAndStats');
        $locations = $settings->adsUnitLocations ?: array();
        
        if (in_array('afterPost', $locations)) {
            self::renderMultipleAds($settings);
        }
        
        return $content;
    }

    /**
     * 在评论区前渲染广告
     */
    public static function renderBeforeComment()
    {
        $options = Helper::options();
        $settings = $options->plugin('AdsAndStats');
        $locations = $settings->adsUnitLocations ?: array();
        
        if (in_array('beforeComment', $locations)) {
            self::renderDisplayAds($settings);
        }
    }

    /**
     * 提供给主题使用的静态方法
     */
    public static function renderSidebarTopAds()
    {
        $options = Helper::options();
        $settings = $options->plugin('AdsAndStats');
        $locations = $settings->adsUnitLocations ?: array();
        
        if (in_array('sidebarTop', $locations)) {
            self::renderDisplayAds($settings);
        }
    }

    public static function renderSidebarBottomAds()
    {
        $options = Helper::options();
        $settings = $options->plugin('AdsAndStats');
        $locations = $settings->adsUnitLocations ?: array();
        
        if (in_array('sidebarBottom', $locations)) {
            self::renderMultipleAds($settings);
        }
    }
}
