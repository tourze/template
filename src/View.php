<?php

namespace tourze\ViewDiscuz;

use Exception;
use tourze\Base\Helper\Arr;

/**
 * 继承原有的view处理,解析模板后再继续原有的解析流程
 *
 * @package tourze\ViewDiscuz
 */
class View extends \tourze\View\View
{

    /**
     * @var array 默认配置
     */
    public static $defaultOptions = [
        'template_dir'   => 'template/', //指定模板文件存放目录
        'template_ext'   => '.html',
        'cache_dir'      => 'cache', //指定缓存文件存放目录
        'auto_update'    => true, //当模板文件有改动时重新生成缓存 [关闭该项会快一些]
        'cache_lifetime' => 1, //缓存生命周期(分钟)，为 0 表示永久 [设置为 0 会快一些]
    ];

    /**
     * @var array
     */
    protected $_templateOptions = [];

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();
        $this->_templateOptions = Arr::merge($this->_templateOptions, self::$defaultOptions);
    }

    /**
     * 设置模板解析选项
     *
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->_templateOptions = Arr::merge($this->_templateOptions, $options);
    }

    /**
     * 获取视图的最终输入
     *
     * @param  string $viewFilename 文件名
     * @param  array  $viewData     变量
     * @return string
     * @throws Exception
     */
    protected function capture($viewFilename, array $viewData)
    {
        //使用单件模式实例化模板类
        $template = Template::getInstance();
        $template->setOptions($this->_templateOptions);

        // 附加模板后缀
        $viewFilename .= $this->_templateOptions['template_ext'];

        // 获取模板解析后的完整路径
        $file = $template->getFile($viewFilename);
        return parent::capture($file, $viewData);
    }
}
