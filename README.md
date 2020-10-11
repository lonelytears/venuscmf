源于：https://gitee.com/potatozhi/venuscmf_tp5

## venuscmf_tp5 基于 thinkphp5.0.11，是一个基本的后台内容管理系统。
*基本模型的使用，ueditor引入，layer、laypage、laydate的使用，webuploader 上传图片，远程下载图片等等，模块思路清晰，非常适合新手的学习入门。

## 实现的功能有：

* 管理员用户管理
* 后台菜单管理
* 角色管理
* rbac 权限管理
* 分类管理
* 文章管理
* 幻灯片分类
* 幻灯片管理


## 2017-09-26
* 上线保存到 oschina

## 后台地址
* 你的域名/admin
* 用户名、密码相同 admins

## 关于后台用户密码生成
* 表 users 中两个字段
* encrypt_salt 为加密盐
* passwd 为密码
* 加密原理：
* 生成一个随机数，md5 加密作为加密盐，同时保存到数据表中
* 加密盐与原始密码一起再 md5 加密一次，然后再 md5 再加密一次
* 请看 app/admin/common.php 中的函数 manager_password()


