# 如何使用本地以及远程策略

首先，其实本地策略与远程策略的实现是同样的方法，因为已经有了远程存储策略，将远程服务端放程序本地，便有了本地存储策略。所以实现是一模一样的，本地存储其实也就是远程存储，所以它们的存储类型是一样的，只不过一个在远程一个在本地。

## 那如何使用？

**本地**

我将服务端文件已经放到了根目录remote中，所以1.3.6是默认支持本地的存储的。

即便如此，你也需要一个简单的配置，才能使用。

在/remote/config.php中配置**$access_key**越复杂越好，此时你便有了一个ak，然后去程序的系统设置中，添加一个新的策略，策略类型选择 远程/本地，然后填入各项值，domian填：你的当前程序域名/remote  即可，此时你就能上传文件到本地了。

**远程**

将你的remote目录下的文件上传到远程的php服务器上，在config.php中配置**$access_key** 越复杂越好。

然后去程序的系统设置中，添加一个新的策略，策略类型选择 远程/本地，然后填入各项值，domian填：你的远程服务器域名/index.php 即可，此时你就能上传文件到远程了。

## 安全问题

由于可能存在的安全问题，所以需要配置一些东西，防止直接通过目录访问你的远程/本地文件，文件或默认存储在upload目录下，所以要禁止直接访问upload目录

**nignx配置**
本地
`location ^~ /remote/upload {
	deny all;
}`
远程
`location ^~ /upload {
	deny all;
}`

**Apache配置**
`<Directory />
Options None
AllowOverride None
Order allow,deny
Allow from all
</Directory>`