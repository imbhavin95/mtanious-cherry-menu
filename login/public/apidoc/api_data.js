define({ "api": [
  {
    "type": "get",
    "url": "api/categories/:menu_id",
    "title": "Categories Listing",
    "name": "Categories_Listing",
    "group": "Categories",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "menu_id",
            "description": "<p>Menu unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "new",
            "description": "<p>Listing new categories</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "updated",
            "description": "<p>Listing updated categories</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "deleted",
            "description": "<p>Listing deleted categories</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Categories"
  },
  {
    "type": "get",
    "url": "api/categories/:menu_id/:timestamp",
    "title": "Categories Listing with Timestamp",
    "name": "Categories_Listing_with_Timestamp",
    "group": "Categories",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>restaurant unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Timestamp",
            "optional": false,
            "field": "timestamp",
            "description": "<p>Timestamp for get record.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "new",
            "description": "<p>Listing new categories</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "updated",
            "description": "<p>Listing updated categories based on timestamp</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "deleted",
            "description": "<p>Listing deleted categories based on timestamp</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Categories"
  },
  {
    "type": "Post",
    "url": "api/item_clicks",
    "title": "Create or Update Item Clicks",
    "name": "Create_or_Update_Item_Clicks",
    "group": "Clicks",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "item_clicks",
            "description": "<p>Request-Example: { &quot;staff_id&quot; : 1, &quot;item_id&quot; : 6, &quot;restaurant_id&quot; : 6, &quot;no_of_clicks&quot; : 7, &quot;timestamp&quot; : &quot;2018-09-28 12:52:07&quot; }, {.. },..</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Response status.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Response message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Clicks"
  },
  {
    "type": "Post",
    "url": "api/category_clicks",
    "title": "Create or Update Category Clicks",
    "name": "Create_or_Update_category_Clicks",
    "group": "Clicks",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "category_clicks",
            "description": "<p>Request-Example: { &quot;staff_id&quot; : 1, &quot;category_id&quot; : 6, &quot;restaurant_id&quot; : 6, &quot;no_of_clicks&quot; : 7, &quot;timestamp&quot; : &quot;2018-09-28 12:52:07&quot; }, {.. },..</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Response status.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Response message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Clicks"
  },
  {
    "type": "Post",
    "url": "api/device_status",
    "title": "Send device status request",
    "name": "Send_Device_status_request",
    "description": "<p>This API is use for check status where device is deleted from web or not?.</p>",
    "group": "Device_status",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "restaurant_id",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>Id of restaurant.</p>"
          },
          {
            "group": "Parameter",
            "type": "token",
            "optional": false,
            "field": "token",
            "description": "<p>token of the login device.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Response status.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Response message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Device_status"
  },
  {
    "type": "Post",
    "url": "api/feedback",
    "title": "Create Feedback",
    "name": "Create_Feedback",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "group": "Feedback",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "json",
            "optional": false,
            "field": "feedbacks",
            "description": "<p>Request-Example: { &quot;staff_id&quot; : 1, &quot;restaurant_id&quot; : 6, &quot;staff_name&quot; : acbd, &quot;stars&quot; : 2, &quot;customer_name&quot; : 'abcd', &quot;feedback&quot; : 'good service' }, {.. },..</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Response status.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Response message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Feedback"
  },
  {
    "type": "Post",
    "url": "api/forgot_password",
    "title": "Forgot Password",
    "name": "Forgot_Password",
    "group": "Forgot_Password",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>Email Id</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Forgot_Password"
  },
  {
    "type": "get",
    "url": "api/help_topics",
    "title": "Help Topics before rsestaurant login",
    "name": "Help_Topics_before_login",
    "group": "Help_Topics",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>Id of the Helptopic.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "title",
            "description": "<p>Logo for Helptopic.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "description",
            "description": "<p>of the Helptopic.</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_active",
            "description": "<p>Return help topic Active = 1 else 0</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_deleted",
            "description": "<p>Return help topic Deleted = 1 else 0</p>"
          },
          {
            "group": "Success 200",
            "type": "Timestamp",
            "optional": false,
            "field": "created_at",
            "description": "<p>Date of help topic creation.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Help_Topics"
  },
  {
    "type": "get",
    "url": "api/itemdetails/:item_id",
    "title": "Item Details",
    "name": "Item_Details",
    "group": "Items",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "item_id",
            "description": "<p>Item unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "item",
            "description": "<p>Details of item</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "images",
            "description": "<p>Listing Images with New,updated and deleteditem images</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Items"
  },
  {
    "type": "get",
    "url": "api/itemdetails/:item_id/:timestamp",
    "title": "Item Images Details with Timestamp",
    "name": "Item_Images_Details_with_Timestamp",
    "group": "Items",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "item_id",
            "description": "<p>Item unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Timestamp",
            "optional": false,
            "field": "timestamp",
            "description": "<p>Timestamp for get record.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "item",
            "description": "<p>Details of item</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "images",
            "description": "<p>Listing Images with new,updated and deleted item images based on timestamp</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Items"
  },
  {
    "type": "get",
    "url": "api/items/:category_id",
    "title": "Items Listing",
    "name": "Items_Listing",
    "group": "Items",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "category_id",
            "description": "<p>Category unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "new",
            "description": "<p>Listing new items</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "updated",
            "description": "<p>Listing updated items</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "deleted",
            "description": "<p>Listing deleted items</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Items"
  },
  {
    "type": "get",
    "url": "api/items/:category_id/:timestamp",
    "title": "Items Listing with Timestamp",
    "name": "Items_Listing_with_Timestamp",
    "group": "Items",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "category_id",
            "description": "<p>category unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Timestamp",
            "optional": false,
            "field": "timestamp",
            "description": "<p>Timestamp for get record.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "new",
            "description": "<p>Listing new items</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "updated",
            "description": "<p>Listing updated items based on timestamp</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "deleted",
            "description": "<p>Listing deleted items based on timestamp</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Items"
  },
  {
    "type": "Post",
    "url": "api/restaurantlogin",
    "title": "Restaurant Login",
    "name": "Restaurant_Login",
    "group": "Login",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "email",
            "optional": false,
            "field": "email",
            "description": "<p>email of the Restaurant.</p>"
          },
          {
            "group": "Parameter",
            "type": "password",
            "optional": false,
            "field": "password",
            "description": "<p>password of the Restaurant.</p>"
          },
          {
            "group": "Parameter",
            "type": "name",
            "optional": false,
            "field": "name",
            "description": "<p>name of the login device.</p>"
          },
          {
            "group": "Parameter",
            "type": "version",
            "optional": false,
            "field": "version",
            "description": "<p>version of the login device.</p>"
          },
          {
            "group": "Parameter",
            "type": "token",
            "optional": false,
            "field": "token",
            "description": "<p>token of the login device.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>id of the Restaurant.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>name of the Restaurant.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>email of the Restaurant.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "role",
            "description": "<p>role of the Restaurant.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "token",
            "description": "<p>Users unique access-key for API.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Login"
  },
  {
    "type": "Post",
    "url": "api/logout",
    "title": "Send logout request",
    "name": "Send_logout_request",
    "group": "Logout",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "restaurant_id",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>Id of restaurant.</p>"
          },
          {
            "group": "Parameter",
            "type": "name",
            "optional": false,
            "field": "name",
            "description": "<p>name of the login device.</p>"
          },
          {
            "group": "Parameter",
            "type": "version",
            "optional": false,
            "field": "version",
            "description": "<p>version of the login device.</p>"
          },
          {
            "group": "Parameter",
            "type": "token",
            "optional": false,
            "field": "token",
            "description": "<p>token of the login device.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Response status.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Response message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Logout"
  },
  {
    "type": "get",
    "url": "api/menus/:restaurant_id",
    "title": "Menus Listing",
    "name": "Menus_Listing",
    "group": "Menus",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>restaurant unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "new",
            "description": "<p>Listing new menus</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "updated",
            "description": "<p>Listing updated menus</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "deleted",
            "description": "<p>Listing deleted menus</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Menus"
  },
  {
    "type": "get",
    "url": "api/menus/:restaurant_id/:timestamp",
    "title": "Menus Listing with Timestamp",
    "name": "Menus_Listing_with_Timestamp",
    "group": "Menus",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>restaurant unique ID.</p>"
          },
          {
            "group": "Parameter",
            "type": "Timestamp",
            "optional": false,
            "field": "timestamp",
            "description": "<p>Timestamp for get record.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "new",
            "description": "<p>Listing new menus</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "updated",
            "description": "<p>Listing updated menus based on timestamp</p>"
          },
          {
            "group": "Success 200",
            "type": "Object[]",
            "optional": false,
            "field": "deleted",
            "description": "<p>Listing deleted menus based on timestamp</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Menus"
  },
  {
    "type": "get",
    "url": "api/packages",
    "title": "Packages Listing",
    "name": "Packages_Listing",
    "group": "Packages",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>id of the Package.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>name of the Package.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "arabic_name",
            "description": "<p>Arabic name of the Package.</p>"
          },
          {
            "group": "Success 200",
            "type": "Double",
            "optional": false,
            "field": "price",
            "description": "<p>Price of the Package.</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "start_date",
            "description": "<p>Starting date of the Package.</p>"
          },
          {
            "group": "Success 200",
            "type": "Date",
            "optional": false,
            "field": "end_date",
            "description": "<p>End date of the Package.</p>"
          },
          {
            "group": "Success 200",
            "type": "Double",
            "optional": false,
            "field": "discount",
            "description": "<p>Discount of the Package.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "users",
            "description": "<p>Limitation of users.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "menus",
            "description": "<p>Limitation of menus.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integet",
            "optional": false,
            "field": "categories",
            "description": "<p>Limitation of categories.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "items",
            "description": "<p>Limitation of items.</p>"
          },
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "devices_limit",
            "description": "<p>Limitation of device login.</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_active",
            "description": "<p>Return menu Active = 1 else 0</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Packages"
  },
  {
    "type": "Post",
    "url": "api/package_request",
    "title": "Send package request",
    "name": "Send_package_request",
    "group": "Packages",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "package_id",
            "optional": false,
            "field": "package_id",
            "description": "<p>Id of Package.</p>"
          },
          {
            "group": "Parameter",
            "type": "restaurant_id",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>Id of the Restaurant.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "status",
            "description": "<p>Response status.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "message",
            "description": "<p>Response message.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Packages"
  },
  {
    "type": "Post",
    "url": "api/registration",
    "title": "Restaurant Registration",
    "name": "Restaurant_Registration",
    "group": "Registration",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "name",
            "optional": false,
            "field": "name",
            "description": "<p>name of the Restaurant.</p>"
          },
          {
            "group": "Parameter",
            "type": "email",
            "optional": false,
            "field": "email",
            "description": "<p>email of the Restaurant.</p>"
          },
          {
            "group": "Parameter",
            "type": "password",
            "optional": false,
            "field": "password",
            "description": "<p>password of the Restaurant.</p>"
          },
          {
            "group": "Parameter",
            "type": "confirm_password",
            "optional": false,
            "field": "confirm_password",
            "description": "<p>Confirm password of the Restaurant.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "success",
            "description": "<p>success message of registration.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Registration"
  },
  {
    "type": "get",
    "url": "api/defaultsettings/:restaurant_id",
    "title": "Background Setting after rsestaurant login",
    "name": "Background_setting_after_login",
    "group": "Settings",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>Restaurant unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>Id of the Setting.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "logo",
            "description": "<p>Logo for Setting.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bg_after_login",
            "description": "<p>Image of the background setting.</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_active",
            "description": "<p>Return item Active = 1 else 0</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_deleted",
            "description": "<p>Return item Deleted = 1 else 0</p>"
          },
          {
            "group": "Success 200",
            "type": "Timestamp",
            "optional": false,
            "field": "created_at",
            "description": "<p>Date of item creation.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Settings"
  },
  {
    "type": "get",
    "url": "api/defaultsettings",
    "title": "Background Setting before rsestaurant login",
    "name": "Background_setting_before_login",
    "group": "Settings",
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>Id of the Setting.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "logo",
            "description": "<p>Logo for Setting.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "bg_before_login",
            "description": "<p>Image of the background setting.</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_active",
            "description": "<p>Return item Active = 1 else 0</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_deleted",
            "description": "<p>Return item Deleted = 1 else 0</p>"
          },
          {
            "group": "Success 200",
            "type": "Timestamp",
            "optional": false,
            "field": "created_at",
            "description": "<p>Date of item creation.</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Settings"
  },
  {
    "type": "get",
    "url": "api/staffs/:restaurant_id",
    "title": "Staff Listing",
    "name": "Staff_Listing",
    "group": "Staffs",
    "header": {
      "fields": {
        "Header": [
          {
            "group": "Header",
            "type": "String",
            "optional": false,
            "field": "Authorization",
            "description": "<p>Users unique access-key.</p>"
          }
        ]
      }
    },
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "Integer",
            "optional": false,
            "field": "restaurant_id",
            "description": "<p>restaurant unique ID.</p>"
          }
        ]
      }
    },
    "success": {
      "fields": {
        "Success 200": [
          {
            "group": "Success 200",
            "type": "Integer",
            "optional": false,
            "field": "id",
            "description": "<p>id of the Staff.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "name",
            "description": "<p>name of the Staff.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "email",
            "description": "<p>email of the Staff.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "role",
            "description": "<p>role of the Staff.</p>"
          },
          {
            "group": "Success 200",
            "type": "String",
            "optional": false,
            "field": "image",
            "description": "<p>Image name of the Staff.</p>"
          },
          {
            "group": "Success 200",
            "type": "Boolean",
            "optional": false,
            "field": "is_active",
            "description": "<p>Return menu Active = 1 else 0</p>"
          }
        ]
      }
    },
    "version": "0.0.0",
    "filename": "./Api.php",
    "groupTitle": "Staffs"
  }
] });
