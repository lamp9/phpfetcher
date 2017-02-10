# phpfetcher

phpfetcher是一个通用数据后台，可十分方便地查询、关联、进行CURD管理数据库，而不需要写任何php和前端文件，而需要实现扩展的需求进行二次开发，本系统也提供了相应的前后端接口。


###菜单功能
1. 系统管理
	1. 权限(菜单)树控制
		1. 菜单树
			1. 新增
			2. 修改
	2. 缓存管理
		1. 权限缓存
	3. 管理员属组		
		1. 权限管理
	4. 管理员
2. 日志
	1. 管理日志
3. 个人设置
	1. 密码修改
4. 数据管理
	1. 数据库
		1. 数据库记录配置
		2. 数据库表关联管理
	2. 数据管理模型
		1. CURD

###具体实现
1. 权限(菜单)
	1. 权限菜单树管理，可管理全局权限	
	2. 控制器绑定各个控制器
	3. 管理员属组可单独配置权限，优先级比全局权限低
2. 缓存管理
	1. 可清除全局权限，包括管理员属组权限
3. 日志
	1. 可记录管理员的各个后台操作，记录包括数据模型具体数据属性，访问来路，URL，管理员ID，时间，操作类型
4. 修改管理员个人密码
5. 数据管理
	1. 控制器
		1. CURD调用模型
		2. 权限检查
		3. csrf检查
		4. 页面HTML渲染输出
	2. 数据模型
		1. 实现对数据搜索查询
		2. 分页
		3. 数据表关联
		4. 数据过滤验证
		5. HTML渲染配置(列表、创建、更新、删除、各按钮操作)
		6. 自定义HTML输出
		7. Insert/Update/Delete
	3. 自定义widget
		1. 渲染公共搜索
		2. 渲染公共列表
		3. 渲染公共分页
		4. 渲染新增、更新页面
		5. 使用Vue.js对页面进行数据二次渲染
	4. 通过extends Phpfetcher\logic\BaseController的class是可进行二次开发的Controller类以实现更复杂的任务分发需求
	5. 通过extends Phpfetcher\logic\model\BaseModel的class是可进行二次开发的Model类以实现更复杂的数据处理需求
###部署
1. 安装composer
2. 安装Composer Asset插件
	1. 命令行输入:composer global require "fxp/composer-asset-plugin:1.0.0"
3. 安装yii2高级应用程序模板
	1. cd到安装路径
	2. 命令行输入:composer create-project yiisoft/yii2-app-advanced DIRNAME 2.0.4
	3. DIRNAME位安装的文件夹名称
4. 下载lamp9/phpfetcher仓库
	1. cd到安装路径
	2. 命令行输入:git clone https://github.com/lamp9/phpfetcher.git
5. 修改程序配置
	1. 修改程序web/index.php中$framework_dir变量为yii2框架根目录
	2. 修改config/main.php中db的配置
	3. 导入admin.sql到mysql数据库
	4. cd到yii2程序根目录，修改composer.json,psr-4元素中增加本程序的web目录的决定路径，并在命令行运行:composer update
	5. 如本项目运行在nginx下，则设置为<br>
		location / {<br>
			if (!-e $request_filename) {<br>
				rewrite ^/([\w-]+)/([\w-]+)\?[\w-]+$ /index.php?r=$1/$2&$query_string last;<br>
				rewrite ^/([\w-]+)/([\w-]+)$ /index.php?r=$1/$2 last;<br>
			}<br>
	    	}<br>
	6. 如本项目运行在apache下(本项目已默认设置.htaccess)，则设置为<br>
		<IfModule mod_rewrite.c><br>
			RewriteEngine On<br>
			RewriteCond %{REQUEST_FILENAME} !-d<br>
			RewriteCond %{REQUEST_FILENAME} !-f<br>
			RewriteRule ^([\w-]+)/([\w-]+)$ index.php?r=$1/$2 [QSA,L]<br>
		</IfModule><br>
	7. 部署完成后输入用户名:root,密码:123则可使用本系统
