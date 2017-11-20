# Vlite
Vlite Framework - a lightweight PHP framework

## Features/特点

## Installation/安装

推荐环境：[xampp](https://www.apachefriends.org/zh_cn/index.html)

软件依赖：[composer](https://getcomposer.org/download/) [composer中国镜像](http://www.phpcomposer.com/)

将项目`git clone`到xampp目录下的`htdocs`中，再在项目根目录下执行`composer install`。

## Usage/使用

### Configuration/配置

配置文件位于`config`文件下，`database.php`为连接数据库配置，`routes.php`为路由配置，使用`noahbuscher/macaw`组件。使用`Macaw::get`和`Macaw::post`分发不同类型的请求，在`route`参数为匹配pattern，可以使用`(:all), (:any), (:num)`匹配部分内容，其正则规则为`':any' => '[^/]+', ':num' => '[0-9]+', ':all' => '.*'`，匹配到的内容将作为参数传入callback函数中。

### Structure/结构

`app`目录为PHP开发MVC目录，其中`controllers`为控制器文件夹，`models`为模型文件夹，`views`为模板文件夹（在开发api的过程中无需使用）。

`config`为配置文件夹。

`html`为纯html页面文件夹。

`framework`为框架核心文件夹。

`vendor`为composer依赖包文件夹，gitignore。

### Model/模型

模型可以理解为一个数据对象，通过在`models`文件夹中新建一个PHP类文件建立。该类继承自`\Vlite\Model`，并在其构造函数中执行`parent::__construct($table_name)`，参见`ProjectModel`示例。

`Model`的函数分为两种，条件设置函数和执行函数。条件设置函数有`field(), where(), order(), join(), on(), limit(), page()`，执行函数有`select(), delete(), insert(), insertAll()`。只有执行函数会进行数据库操作并返回执行结果，条件设置函数只是为执行函数做了条件设置。使用方式类似`$model->field('*')->where(['id' => 1])->select()`。

#### field()

不使用该函数默认为`'*'`，使用方式为`field('id'), field('id, value')`。

#### where()

where的设计思路参考[Medoo](https://medoo.in)。

方式一，纯字符串。`where('id = 1')`

方式二，数组形式，`=> value`表示等于条件，`=> array`表示IN条件。`where(['id' => 1, 'value' => [1,2,3]])`为`WHERE id = 1 AND value IN (1, 2, 3)`，其实相当于隐藏了AND条件，即`where(['AND' => ['id' => 1, 'value' => [1, 2, 3]]])`。

方式三，AND OR形式。`where(['OR' => ['id' => 1, 'value' => [1, 2, 3]]])`。AND和OR是可以嵌套的，例如`where(['OR' => ['id' => 1, 'AND' => ['value' => 2, 'value2' => 3]]])`。

比较运算符。`'value[>]' => 1, 'value[<]' => 1, 'value[>=]' => 1, 'value[<=]' => 1, 'value[!]' => 1`意思不言而喻。`'value[<>]' => [1, 5]`表示`value BETWEEN 1 AND 5`，`'value[><]' => [1, 5]`表示`value NOT BETWEEN 1 AND 5`。

#### order()

`order('id asc'), order('id asc, value desc')`

#### join() on()

```php
$model = new Model('test1');
$result = $model->join('test2')->on('test1.id = test2.id')->select();
```

#### 
