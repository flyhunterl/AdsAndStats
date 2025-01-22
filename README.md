# AdsAndStats Plugin for Typecho

一个用于插入Google和百度统计代码以及Google自动广告代码的Typecho插件

## 功能特性

- 支持Google Analytics统计代码
- 支持百度统计代码
- 支持Google Adsense自动广告
- 统计和广告功能可独立开关
- 可配置广告代码插入位置
- 支持多种页面类型（首页、文章页、独立页面等）

## 安装方法

1. 将插件文件夹重命名为 `AdsAndStats`
2. 将文件夹上传到Typecho的插件目录 `usr/plugins/`
3. 在Typecho后台激活插件

## 配置说明

1. **统计功能开关**
   - 可单独控制Google Analytics和百度统计的启用状态

2. **Google Analytics 跟踪ID**  
   格式：UA-XXXXX-Y  
   在Google Analytics后台获取

3. **百度统计 跟踪ID**  
   格式：xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx  
   在百度统计后台获取

4. **广告功能开关**
   - 控制是否启用Google Adsense广告功能

5. **Google Adsense 发布商ID**  
   格式：ca-pub-xxxxxxxxxxxxxxxx  
   在Google Adsense后台获取

6. **广告插入位置设置**  
   - 所有页面的头部
   - 首页
   - 文章页面
   - 独立页面

## 使用说明

1. 在插件配置页面选择需要启用的功能
2. 填写相应的统计代码和广告代码
3. 选择广告代码插入的位置
4. 保存设置后，代码将自动插入到指定位置
5. 可以通过查看网页源代码确认代码是否正确插入

## 注意事项

- 请确保输入的代码格式正确
- 广告代码可能需要等待一段时间才能生效
- 统计代码可能需要24小时才能看到数据
- 请遵守各平台的使用条款

## 版本历史

- v1.0.1 (2024-01-18)
  - 优化统计代码逻辑，确保全局生效
  - 添加统计和广告功能独立开关
  - 改进配置界面，使用说明更清晰

- v1.0.0 (2023-10-15)
  - 初始版本发布
  - 支持Google Analytics
  - 支持百度统计
  - 支持Google Adsense
  - 支持代码插入位置配置

## 作者信息

- 作者：flyhunterl
- 网站：https://www.llingfei.com
- 如有问题或建议，欢迎联系作者

## 开源协议

本插件采用MIT开源协议
