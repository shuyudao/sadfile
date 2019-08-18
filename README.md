# SadFile

版本：2.0


### 更新记录

2019-8-18 2.0 全新版本

**改**：

- 结合流行趋势+MDUI重构前端、前后分离、合并了大部分页面为一个主页面
- 重构了部分后端代码、优化了数据库连接操作、前端接口响应、去除部分臃肿无意义代码，提高了程序效率
- 修改了登录验证方式（Cookie->Session），免去了前版本的每次操作都需要查询数据库的愚蠢操作
- 修复了删除文件时，远程/本地文件无法删除的问题
- 修复优化若干问题...


**增**：

- 新增音频在线预览，支持mp3、m4a、wav、flac、aac格式音频
- 新增对flv格式视频的预览支持
- 新增对pdf/word/ppt/excel文档的在线预览
- 新增对txt以及常见代码（php、sql、java、python、js、css等）的在线预览
- 新增同类型文件的分类显示
- 新增离线下载功能（Aria2+AriaNg），本地/远程 策略支持该功能
- 新增登录日志记录
- 新增支持多文件分享
- 新增多文件队列分片上传


需要说明的是：PC端：文件夹支持Ctrl多选、右键操作文件 // 移动端：长按文件操作文件

[更多更新日志](https://github.com/shuyudao/sadfile/wiki/%E6%9B%B4%E6%96%B0%E6%97%A5%E5%BF%97)

------



### 一、安装

这个程序安装需求很简单。

**安装环境**： php  5.4以上  mysql 5.5及以上

**安装步骤**：访问 域名/install.html 即可 支持虚拟主机安装


### 二、程序功能

1. 基本的文件、文件夹管理（删除、移动、创建、重命名、文件搜索等）
2. 部分文件支持在线预览（如图片、视频、音频、文档、代码等）
3. 多文件的分享功能
4. 文件分享管理（取消分享、下载次数等）
5. 支持文件离线下载（Aria2+AriaNG）本地/远程 策略支持该功能
6. 登录日志记录
7. 支持 远程/本地/七牛云 三种不同存储策略类型，策略数量可无上限，不同上传策略之间无缝切换
8. 多文件队列分片上传、断点续传
9. 移动端全站响应式布局
10. ...

### 三、文档/说明

[如何使用本地以及远程策略以及注意事项](https://github.com/shuyudao/sadfile/wiki/%E8%BF%9C%E7%A8%8B%E7%AD%96%E7%95%A5%E7%9A%84%E4%BD%BF%E7%94%A8%E6%B3%A8%E6%84%8F%E4%BA%8B%E9%A1%B9)

[离线下载的使用及注意事项](https://github.com/shuyudao/sadfile/wiki/%E7%A6%BB%E7%BA%BF%E4%B8%8B%E8%BD%BD%E7%9A%84%E4%BD%BF%E7%94%A8%E5%8F%8A%E6%B3%A8%E6%84%8F%E4%BA%8B%E9%A1%B9)

### 四、图片预览

![image.png](https://i.loli.net/2019/08/18/XUfg9lBomChVOde.png)

![image.png](https://i.loli.net/2019/08/18/CLlV8DsxwRnoNa5.png)

![image.png](https://i.loli.net/2019/08/18/V6iYz94j1deKFH8.png)

![image.png](https://i.loli.net/2019/08/18/RxVAto5MiLk7jdy.png)
