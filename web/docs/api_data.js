define({ "api": [
  {
    "type": "post",
    "url": "/v1/auth/get",
    "title": "获取登录信息",
    "description": "<p>获取登录信息</p>",
    "name": "_auth_auth_get",
    "group": "auth",
    "version": "3.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_token",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_lang",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_type",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_version",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"app_token\": \"wVNq2Fcg-zXVcKDYRy_vLq7niv-36As3\",\n  \"app_lang\": \"zh\",\n  \"app_type\": \"smt_client\",\n  \"app_version\": \"2.4.7\",\n}",
          "type": "string"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/v1/auth/get"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<ol start=\"200\"> <li></li> </ol>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data.user",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "data.user.id",
            "description": "<p>用户编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.username",
            "description": "<p>登录账户</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.name",
            "description": "<p>用户姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.sex",
            "description": "<p>性别 1女 2 男 0 未知</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.factory_name",
            "description": "<p>厂编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.dept_id",
            "description": "<p>厂编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user_token",
            "description": "<p>用户登录令牌</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "正确实例:",
          "content": "\n{\n\"code\": 200,\n\"data\": {\n\"user\":{\n  \"username\":\"\",\n  \"name\":\"\",\n  \"sex\":1\n  \"dept_id\":1,\n  \"factory_name\":\"\",\n},\n'user_token':''\n},\n\"msg\": \"请求成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>错误码<br> 0：系统错误<br></p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>错误消息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "错误实例:",
          "content": "{\n\"code\": \"0\",\n\"msg\": \"服务繁忙\",\n\"data\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "modules/v1/controllers/AuthController.php",
    "groupTitle": "auth"
  },
  {
    "type": "post",
    "url": "/v1/auth/get-open-info",
    "title": "获取openinfo",
    "description": "<p>获取openinfo</p>",
    "name": "_auth_auth_get_open_info",
    "group": "auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>微信code</p>"
          }
        ]
      }
    },
    "version": "3.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_token",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_lang",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_type",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_version",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"app_token\": \"wVNq2Fcg-zXVcKDYRy_vLq7niv-36As3\",\n  \"app_lang\": \"zh\",\n  \"app_type\": \"smt_client\",\n  \"app_version\": \"2.4.7\",\n}",
          "type": "string"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/v1/auth/get-open-info"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<ol start=\"200\"> <li></li> </ol>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data.openid",
            "description": "<p>小程序openid</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "正确实例:",
          "content": "\n{\n\"code\": 200,\n\"data\": {\n'open_id':''\n},\n\"msg\": \"请求成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>错误码<br> 0：系统错误<br></p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>错误消息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "错误实例:",
          "content": "{\n\"code\": \"0\",\n\"msg\": \"服务繁忙\",\n\"data\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "modules/v1/controllers/AuthController.php",
    "groupTitle": "auth"
  },
  {
    "type": "post",
    "url": "/v1/auth/auto-login",
    "title": "自动登录",
    "description": "<p>登录</p>",
    "name": "_v1_auth_auto_login",
    "group": "auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "openid",
            "description": "<p>第三方编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "appid",
            "description": "<p>小程序APPID</p>"
          }
        ]
      }
    },
    "version": "3.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_token",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_lang",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_type",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_version",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"app_token\": \"wVNq2Fcg-zXVcKDYRy_vLq7niv-36As3\",\n  \"app_lang\": \"zh\",\n  \"app_type\": \"smt_client\",\n  \"app_version\": \"2.4.7\",\n}",
          "type": "string"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/v1/auth/auto-login"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<ol start=\"200\"> <li></li> </ol>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data.user",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "data.user.id",
            "description": "<p>用户编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.username",
            "description": "<p>登录账户</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.name",
            "description": "<p>用户姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.sex",
            "description": "<p>性别 1女 2 男 0 未知</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.factory_name",
            "description": "<p>厂编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.dept_id",
            "description": "<p>厂编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user_token",
            "description": "<p>用户登录令牌</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "正确实例:",
          "content": "{\n\"code\": 200,\n\"data\": {\n\"user\":{\n  \"username\":\"\",\n  \"name\":\"\",\n  \"sex\":1\n  \"dept_id\":1,\n  \"factory_name\":\"\",\n},\n 'user_token':''\n},\n\"msg\": \"请求成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>错误码<br> 0：系统错误<br></p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>错误消息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "错误实例:",
          "content": "{\n\"code\": \"0\",\n\"msg\": \"服务繁忙\",\n\"data\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "modules/v1/controllers/AuthController.php",
    "groupTitle": "auth"
  },
  {
    "type": "post",
    "url": "/v1/auth/login",
    "title": "登录",
    "description": "<p>登录</p>",
    "name": "_v1_auth_login",
    "group": "auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "username",
            "description": "<p>手机号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "password",
            "description": "<p>密码</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "openid",
            "description": "<p>第三方编号</p>"
          },
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "appid",
            "description": "<p>小程序APPID</p>"
          }
        ]
      }
    },
    "version": "3.1.0",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_token",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_lang",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_type",
            "description": ""
          },
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "app_version",
            "description": ""
          }
        ]
      },
      "examples": [
        {
          "title": "Header-Example:",
          "content": "{\n  \"app_token\": \"wVNq2Fcg-zXVcKDYRy_vLq7niv-36As3\",\n  \"app_lang\": \"zh\",\n  \"app_type\": \"smt_client\",\n  \"app_version\": \"2.4.7\",\n}",
          "type": "string"
        }
      ]
    },
    "sampleRequest": [
      {
        "url": "/v1/auth/login"
      }
    ],
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<ol start=\"200\"> <li></li> </ol>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>消息</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "json",
            "optional": false,
            "field": "data.user",
            "description": "<p>数据</p>"
          },
          {
            "group": "Success 200",
            "type": "int",
            "optional": false,
            "field": "data.user.id",
            "description": "<p>用户编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.username",
            "description": "<p>登录账户</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.name",
            "description": "<p>用户姓名</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.sex",
            "description": "<p>性别 1女 2 男 0 未知</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.factory_name",
            "description": "<p>厂编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user.dept_id",
            "description": "<p>厂编号</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "data.user_token",
            "description": "<p>用户登录令牌</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "正确实例:",
          "content": "{\n\"code\": 200,\n\"data\": {\n\"user\":{\n  \"username\":\"\",\n  \"name\":\"\",\n  \"sex\":1\n  \"dept_id\":1,\n  \"factory_name\":\"\",\n},\n 'user_token':''\n},\n\"msg\": \"请求成功\"\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "fields": {
        "Error 4xx": [
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "code",
            "description": "<p>错误码<br> 0：系统错误<br></p>"
          },
          {
            "group": "Error 4xx",
            "type": "String",
            "optional": false,
            "field": "msg",
            "description": "<p>错误消息</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "错误实例:",
          "content": "{\n\"code\": \"0\",\n\"msg\": \"服务繁忙\",\n\"data\": []\n}",
          "type": "json"
        }
      ]
    },
    "filename": "modules/v1/controllers/AuthController.php",
    "groupTitle": "auth"
  }
] });
