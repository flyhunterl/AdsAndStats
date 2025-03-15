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

1. 使用手动广告单元时，请先在Google Adsense后台禁用自动广告功能，否则可能会导致广告重叠显示
2. 请勿在同一页面放置过多广告，以免影响用户体验
3. 确保遵守Google Adsense的广告放置政策

## 版本历史

### 1.0.2
- 新增手动广告单元功能
- 支持多重广告单元和展示广告单元
- 新增多个广告位置选项
- 优化使用说明和提示

### 1.0.1
- 支持Google Analytics统计
- 支持百度统计
- 支持Google Adsense自动广告

## 作者信息

- 作者：flyhunterl
- 网站：https://www.llingfei.com
- 如有问题或建议，欢迎联系作者
- 
## 打赏
**您的打赏能让我在下一顿的泡面里加上一根火腿肠。**
![20250314_125818_133_copy](https://github.com/user-attachments/assets/33df0129-c322-4b14-8c41-9dc78618e220)

## 开源协议

本插件采用MIT开源协议
