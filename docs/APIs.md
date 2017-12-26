# __接口设计__

本文档为前后端交互的接口设计，在本文档中只定义了 __API__ 的url，忽略了静态页面的url。前端资源表示为 __xxx/xxx__ 时，返回html文件，在html文件中，通过 `location.href`获取当前的 __url__，然后决定访问哪个接口。如url为 __project/1__ 时，应返回显示项目信息的 __html__ 文件，之后通过`location.href`获取url，判断当前需获取 __project_id为1__ 的项目的内容，向 __api/project/1__，发送请求，之后将结果填入预留的空间中。

| method | url                                                                                             | 描述                                             |
| :----- | :---------------------------------------------------------------------------------------------- | :----------------------------------------------- |
| POST   | [api/user](#postapiuser)                                                                        | 通过表单提交啊，用户注册                         |
| GET    | [api/user/notification](#getapiusernotification)                                                | 获取用户通知信息                                 |
| GET    | [api/user/todo](#getapiusertodo)                                                                | 查看todo list                                    |
| GET    | [api/user/(:user_name)](#getapiuseruser_name)                                                   | 获取其他用户信息                                 |
| POST   | [api/session](#postapisession)                                                                  | 通过表单提交，用户登陆                           |
| GET    | [api/session/user](#getapisessionuser)                                                          | 获取自己的信息                                   |
| DELETE | [api/session](#deleteapisession)                                                                | 用户退出登陆                                     |
| POST   | [api/project](#postapiproject)                                                                  | 表单提交，新建项目                               |
| PUT    | [api/project/(:project_id)](#putapiprojectproject_id)                                           | 通过表单提交，修改项目描述信息，标签，权限       |
| GET    | [api/project/(:project_id)](#getapiprojectproject_id)                                           | 查看描述信息                                     |
| POST   | [api/project/(:project_id)/file](#postapiprojectproject_idfile)                                 | 表单提交，上传文件                               |
| GET    | [api/project/(:project_id)/file](#getapiprojectproject_idfile)                                  | 下载打包后的项目文件                             |
| POST   | [api/project/(:project_id)/issue](#postapiprojectproject_idissue)                               | 新建issue，表单提交                              |
| GET    | [api/project/(:project_id)/issue](#getapiprojectproject_idissue)                                | 列出project中的issue                             |
| GET    | [api/issue/(:issue\_id)](#getapiissueissue_id)           | 列出project中的某个issue                         |
| POST   | [api/issue/(:issue\_id)/update](#putapiprojectproject_idissueissue_id)           | 修改issue                                        |
| DELETE | [api/project/(:project_id)/issue/(:issue\_id)](#deleteapiprojectproject_idissueissue_id)        | 关闭issue                                        |
| GET    | [api/issue?keyword=]                                                                            | 搜索lable，milestone，title，open state搜索issue |
| GET    | [api/project?name=&label=&owner=]                                                               | 根据name，label，owner搜索project                |
| POST   | [api/project/(:project\_id)/issue/(:issue\_id)/comment](#postapiproject_idissueissue_idcomment) | 回复issue                                        |
| POST   | [api/project/(:project_id)/todo](#postapiprojectproject_idtodo)                                 | 从issue中新建todo，隐式表单提交                  |
| GET    | [api/project/(:project_id)/download](#getapiprojectproject_iddownload) | 下载project的文件|
| GET    | [api/project/search?keyword=xxx&page=1]                        | 搜索项目

## POST:/api/user
Vancior done

功能:
通过表单提交，完成用户注册。

后端逻辑:
根据表单内容，完成填表，修改 __session__ 中的内容，返回用户的信息(可能不太需要)

表单内容:
__username:str__ , __email:str__ , __password:str__

返回内容:

```javascript
// 成功时 state 为 200, 返回 json ,以便实现自动跳转
// 或者跳转到主页，通过 GET /api/session/user
{
  "username":"用户名"，
  "email":"用户的邮箱"
}
// 失败时 state 为 400
{
  "status":"failed",
  "message":"some error message"
}
```

## GET:/api/user/notification
Vancior done

功能：
通过session中储存的 __user_id__ 获取用户的被@的通知，自己的issue收到的回复，项目中新的issue。

注意：
被@的通知和自己issue的回复可能重复。

Vancior: 可以改成获得自己的项目下未close的issue，和在自己的issue下的comment
comment中的owner为issue的所属者

返回内容:

```json
{
  "issue_list":
  [
    {
      "issue_id":213131,
      "title":"",
      "create_time":"",
      "description":"",
      "milestone":"",
      "state":1,
      "is_read":0,
      "sponsor":1,
      "sponsor_name":"",
      "project":1,
      "project_name":""
    }
  ],
  "comment_list":
  [
    {
      "comment_id":12,
      "content":"",
      "comment_time":"",
      "issue":1,
      "project":1,
      "project_name":"",
      "sponsor":1,
      "sponsor_name":"",
      "owner":1,
      "is_read":0
    }
  ]
}
```

## GET:/api/user/todo
Vancior done

功能:
通过sesstion中储存的 __user_id__ 获取得到用户的todolist。

返回内容:

```javascript
[
  {
    "project_id":0000,
    "todolist":
    [
      {
        "todo_id":0000,
        "title":"todolist1",
        "content":"完成文档",
        "issue_id":0000
      },
      {
        "todo_id":0001,
        "title":"todolist2",
        "content":"完成页面",
        "issue_id":0001
      }
    ]
  }
]
```

## GET:/api/user/(:user_id)

功能:
通过 __url__ 中的 __user_id__ ，获取目标用户的信息。

返回内容:

```javascript
// 成功时 state 为 200
{
  "status":"success",
  "username":"test1",
  "email":"test1@test.com",
  "profile":"test account",
  "icon":"www.test.com/img/0001.png"
}
// 失败时 
{
  "status": "failed",
  "message": "",
}
```

## POST:/api/session
Vancior done

功能:
通过表单提交，完成用户登陆。

表单内容为:

__email(str)__ , __password(str)__

前端逻辑:
正则表达式判断是否为邮箱，是则直接使用，否则通过 GET /user/(:user\_name) 的返回结果，获取 email。

后端逻辑:
进行登陆验证，成功时为 __session__ 中加入用户信息。

返回内容:

```javascript
// 成功时 state 为 200, 返回 json ,以便实现自动跳转
// 或者跳转到主页，通过 GET /api/session/user
{
  "username":"用户名"，
  "email":"用户的邮箱"
}
// 失败时 state 为 400
{
  "status":"failed",
  "message":"some error message"
}
```

## GET:/api/session/user
Vancior done

功能:
获取个人信息，

返回内容:

```javascript
{
  "username":"xxx",
  "email":"xxx@xxx.com",
  "profile":"xxx",
  "icon":"xxx/xxx.jpg",
  "project_list":[
    {
      "project_id":1312,
      "title":"project name"
    }
  ]
}
```

## POST:/api/project
Vancior done

功能:
通过表单提交，完成创建项目

表单内容为:

__title(str)__ ， __description(str)__ ， __label(str)__

前端逻辑:
将多个 __label__ 用 __+__ 连接成一个字符串(?)

后端逻辑：
通过 __POST__ 的内容，和 __session__ 中的信息，完成表插入的操作

返回内容:

```json
// 成功时。返回json，其中包含 peoject_id 以便完成自动跳转
{
  "project_id":11132
}
// 失败时
{
  "status":"failed",
  "message":"some error message"
}
```

## PUT:/api/project/(:project_id)

功能:
通过表单提交，修改项目描述信息，标签，权限

## GET:/api/project/(:project_id)

功能:
查看项目的信息

## POST:/api/project/(:project_id)/issue
Vancior done

功能:
通过表单提交title description label，完成提交 __issue__

## GET:/api/project/(:project_id)/issue
Vancior done

功能:
通过 __url__ 中的 __project\_id__ 获取其所有的 __issue__。

返回内容

```json
{
  "project_id":1231231,
  "title":"project name 1",
  "issue_list":[
    {
      "issue_id":1,
      "title":"test_issue1",
      "description":"a test issue",
      "create_time":"2017-12-31 06:24:05",
      "close_time":"2017-12-31 06:24:06",
      "lable":"test",
      "statu":1
    }
  ]
}
```

## GET:/api/issue/(:issue\_id)

功能:
通过 __url__ 中的 __project\_id__ 和 __issue\_id__ 获取所有的 __comment__及issue本身的信息。

返回内容

```json
{
  "issue_id":1,
  "title": "",
  "milestone": "",
  "description": "",
  "create_time": "",
  "close_time":"",
  "label":"",
  "sponsor":1,
  "project":1,
  "owner":2,
  "state": 1,
  "is_read":0,
  "comment_list": [{
    "comment_id": 1,
    "content":"test_comment1",
    "comment_time":"2017-12-31 06:24:06",
    "username":"test1",
    "sponsor":1
  }]
}
```

## PUT:/api/project/(:project_id)/issue/(:issue\_id)

功能:
通过表单提交，完成修改 __issue__

## DELETE:/api/project/(:project_id)/issue/(:issue\_id)

功能:
通过表单提交，完成关闭 __issue__

## POST:/api/project/(:project\_id)/issue/(:issue\_id)/comment

功能:
通过表单提交，完成回复issue。

表单内容为:
__content(str)__

后端逻辑：
通过 POST中的内容和__sesstion__ 中的用户信息，完成表插入.

返回内容:

```javascript
// 成功时 state 为 200，返回 json 以便自动跳转
{
  "project_id":12312,
  "issue_id":1
}
// 失败时 state 为 400
{
  "status":"failed",
  "message":"some error message"
}
```

## POST:/api/project/(:project\_id)/todo

功能:
通过表单提交，完成从 __comment__ 创建 __todolist__。

表单内容:
__title(str)__ ， __content(str)__。

前端逻辑:
将 __issue__ 、 __comment__ 中的内容自动填入到 __content__ 中。

## GET:/api/project/(:project_id)/download

功能：
通过project_id下载所属的文件

## GET:/api/project/search?keyword=xxx&page=1

功能：
通过GET请求搜索满足条件的project

```json
[
  {
    "project_id": 1,
    "title":"",
    "description":"",
    "label":"",
    "create_time":"",
    "owner":"",
    "username":"",
    "file_name":"",
    "version":"",
    "stars":12
  }
]
```