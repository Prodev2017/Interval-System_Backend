define({ "api": [
  {
    "type": "get",
    "url": "/api/pdfview",
    "title": "Download PDF report",
    "name": "GetPDFReport",
    "group": "Report",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "week_id",
            "description": "<p>week id.</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "manager_id",
            "description": "<p>Interval manager id.</p>"
          }
        ]
      }
    },
    "description": "<p>Forms and downloads Time Sheet Reporting for the manager for the week in PDF format</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "pdf_file",
            "optional": false,
            "field": "report-manager_id-week_id",
            "description": "<p>Time Sheet Reporting for the manager for the week in PDF format</p>"
          }
        ]
      }
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/ReportController.php",
    "groupTitle": "Report"
  },
  {
    "type": "post",
    "url": "/api/report",
    "title": "Status of reports",
    "name": "GetReportsStatus",
    "group": "Report",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "startdate",
            "description": "<p>Date of first week. Date format ISO: YYYY-mm-dd. Optional</p>"
          },
          {
            "group": "Parameter",
            "type": "Number",
            "optional": false,
            "field": "client_id",
            "description": "<p>The client id of Interval. Optional</p>"
          }
        ]
      }
    },
    "description": "<p>Get status of reports for four weeks, starting from the specified week</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "approvals",
            "description": "<p>Reporting state data.</p>"
          },
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "approvals.approval_id",
            "description": "<p>The Id of approve.</p>"
          },
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "approvals.status",
            "description": "<p>Status of report.</p>"
          },
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "approvals.user_id",
            "description": "<p>User id of Interval.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "approvals.username",
            "description": "<p>User name.</p>"
          },
          {
            "group": "Success 200",
            "type": "date",
            "optional": false,
            "field": "approvals.week_date_end",
            "description": "<p>Date of reports week end. Date format ISO: YYYY-mm-dd.</p>"
          },
          {
            "group": "Success 200",
            "type": "date",
            "optional": false,
            "field": "approvals.week_date_start",
            "description": "<p>Date of reports week start. Date format ISO: YYYY-mm-dd.</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "client_id",
            "description": "<p>All clients data.</p>"
          },
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "client_id.interval_active",
            "description": "<p>Client status true/false.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "client_id.interval_id",
            "description": "<p>The client id of Interval.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "client_id.interval_localid",
            "description": "<p>The client local id of Interval.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "client_id.interval_name",
            "description": "<p>Name of client.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"approvals\":\n     [\n         {\n             \"approval_id\":1,\n             \"status\":1,                     //status of report is approved\n             \"user_id\":242858,\n             \"username\":\"William Lucas\"\n             \"week_date_end\":\"2017-05-30\"\n             \"week_date_start\":\"2017-05-24\"\n         },\n         ...\n     ],\n \"client_id\":\n     [\n         {\n             \"interval_active\":true,\n             \"interval_id\":\"234510\",\n             \"interval_localid\":\"00032\",\n             \"interval_name\":\"ANZ - Australia and New Zealand\"\n         },\n         ...\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/ReportController.php",
    "groupTitle": "Report"
  },
  {
    "type": "post",
    "url": "/api/sendreminders",
    "title": "Send reminders",
    "name": "SendReminders",
    "group": "Report",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Array",
            "optional": false,
            "field": "approval_id",
            "description": "<p>The approval id for approve.</p>"
          }
        ]
      }
    },
    "description": "<p>Resend reports that have not been approved by the managers</p>",
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\":true\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/ReportController.php",
    "groupTitle": "Report"
  },
  {
    "type": "post",
    "url": "/api/timereport",
    "title": "Set Time of reporting",
    "name": "Set_Time_of_reporting",
    "group": "Report",
    "description": "<p>Set time of reporting</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "integer",
            "optional": false,
            "field": "week",
            "description": "<p>Week number.</p>"
          },
          {
            "group": "Parameter",
            "type": "integer",
            "optional": false,
            "field": "hour",
            "description": "<p>Hour of the day.</p>"
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\":true\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/ReportController.php",
    "groupTitle": "Report"
  },
  {
    "type": "get",
    "url": "/api/timereport",
    "title": "Get Time of reporting",
    "name": "Time_of_reporting",
    "group": "Report",
    "description": "<p>Get time of reporting</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "week",
            "description": "<p>Week number.</p>"
          },
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "hour",
            "description": "<p>Hour of the day.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n {\n     \"week\":6,\n     \"hour\":22\n }",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/ReportController.php",
    "groupTitle": "Report"
  },
  {
    "type": "post",
    "url": "/api/getselected",
    "title": "Get selected users",
    "name": "GetSelectedUsers",
    "group": "Selected",
    "description": "<p>Get a link between managers and users</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "managers",
            "description": "<p>All managers with users.</p>"
          },
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "managers.interval_id",
            "description": "<p>The manager id from interval.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "managers.firstname",
            "description": "<p>Firstname manager.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "managers.lastname",
            "description": "<p>Lastname manager.</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "managers.users",
            "description": "<p>All users of manager.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "managers.users.firstname",
            "description": "<p>Firstname user.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "managers.users.lastname",
            "description": "<p>Lastname user.</p>"
          },
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "managers.users.user_id",
            "description": "<p>The user id from interval.</p>"
          },
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "managers.users.manager_id",
            "description": "<p>The id of users manager.</p>"
          },
          {
            "group": "Success 200",
            "type": "array",
            "optional": false,
            "field": "available_users",
            "description": "<p>All available users.</p>"
          },
          {
            "group": "Success 200",
            "type": "integer",
            "optional": false,
            "field": "available_users.interval_id",
            "description": "<p>The user id from interval.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "available_users.firstname",
            "description": "<p>Firstname user.</p>"
          },
          {
            "group": "Success 200",
            "type": "string",
            "optional": false,
            "field": "available_users.lastname",
            "description": "<p>Lastname user.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"managers\":\n     [\n         {\n             \"interval_id\":16064,\n             \"firstname\":\"Chuck\",\n             \"lastname\":\"Beck\",\n             \"users\": []   //manager don`t have users\n         },\n         ... ,\n         {\n             \"interval_id\":252972,\n             \"firstname\":\"Valerie\",\n             \"lastname\":\"Cooper\",\n             \"users\":     // manager have users\n                 [\n                     {\n                         \"firstname\":\"Zhenghao\",\n                         \"lastname\":\"Yang\",\n                         \"user_id\":289235,\n                         \"manager_id\":252972\n                     },\n                     ...\n                 ]\n         }\n     ],\n  \"available_users\":\n     [\n         {\n             \"interval_id\":203991,\n             \"firstname\":\"Allen\",\n             \"lastname\":\"Lyons\"\n         },\n         {\n             \"interval_id\":279469,\n             \"firstname\":\"Andrew\",\n             \"lastname\":\"Gannon\"\n         },\n         ...\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/ManagerController.php",
    "groupTitle": "Selected"
  },
  {
    "type": "post",
    "url": "/api/setselected",
    "title": "Set selected users",
    "name": "SetSelectedUsers",
    "group": "Selected",
    "description": "<p>Write the relationship of users with managers</p>",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "available_users",
            "description": "<p>Array id of available users. Optional</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "managers",
            "description": "<p>Array all managers with users. Optional</p>"
          },
          {
            "group": "Parameter",
            "type": "integer",
            "optional": false,
            "field": "managers.manager_id",
            "description": "<p>The manager id.</p>"
          },
          {
            "group": "Parameter",
            "type": "array",
            "optional": false,
            "field": "managers.users",
            "description": "<p>The users id.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Request-Example:",
          "content": "{\n  \"available_users\":\n     {\n         1,\n         2,\n        ...\n         n\n     },\n  \"managers\":    //If the manager's settings are not changed, its data is no need to send0.\n     [\n         {\n             \"manager_id\":123,\n             \"users\":\n                 {\n                     1,\n                     2,\n                    ...\n                     n\n                 }\n         }\n     ]\n}",
          "type": "json"
        }
      ]
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Successful request.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\":true\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/ManagerController.php",
    "groupTitle": "Selected"
  },
  {
    "type": "post",
    "url": "/api/updateuser",
    "title": "Update users",
    "name": "UpdateUser",
    "group": "User",
    "description": "<p>Forced update of user data in the database</p>",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Successful request.</p>"
          }
        ]
      },
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\n  \"status\":true\n}",
          "type": "json"
        }
      ]
    },
    "error": {
      "examples": [
        {
          "title": "Error-Response:",
          "content": "HTTP/1.1 400 Bad Request",
          "type": "json"
        }
      ]
    },
    "version": "0.0.0",
    "filename": "./app/Http/Controllers/UserController.php",
    "groupTitle": "User"
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./doc/main.js",
    "group": "_home_vagrant_projects_interval_doc_main_js",
    "groupTitle": "_home_vagrant_projects_interval_doc_main_js",
    "name": ""
  },
  {
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "optional": false,
            "field": "varname1",
            "description": "<p>No type.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "varname2",
            "description": "<p>With type.</p>"
          }
        ]
      }
    },
    "type": "",
    "url": "",
    "version": "0.0.0",
    "filename": "./public/doc/main.js",
    "group": "_home_vagrant_projects_interval_public_doc_main_js",
    "groupTitle": "_home_vagrant_projects_interval_public_doc_main_js",
    "name": ""
  }
] });
