# API Testing with Postman

## Setup
1. Install [Postman](https://www.postman.com/downloads/) if you haven't already.
2. Import the collection using one of these methods:

### Method 1: Import from Raw JSON
1. Copy the entire JSON object below (from `{` to `}`)
2. In Postman:
   - Click "Import" in the top-left
   - Select "Raw text" tab
   - Paste the JSON
   - Click "Continue" then "Import"

### Method 2: Import from File
1. Create a new file named `business-review-system.postman_collection.json`
2. Copy the JSON below into the file and save it
3. In Postman:
   - Click "Import"
   - Drag & drop the file or click "Upload Files" to select it

### Method 3: Direct Import (if hosted)
```json
{
  "info": {
    "name": "Business Review System API",
    "schema": "[https://schema.getpostman.com/json/collection/v2.1.0/collection.json](https://schema.getpostman.com/json/collection/v2.1.0/collection.json)"
  },
  "item": [
    // ... (rest of the JSON remains the same)
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000"
    },
    {
      "key": "auth_token",
      "value": "YOUR_AUTH_TOKEN_HERE"
    }
  ]
}


```json
{
  "info": {
    "name": "Business Review System API",
    "schema": "[https://schema.getpostman.com/json/collection/v2.1.0/collection.json](https://schema.getpostman.com/json/collection/v2.1.0/collection.json)"
  },
  "item": [
    {
      "name": "Register User",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"name\": \"Test User\",\n    \"email\": \"test@example.com\",\n    \"password\": \"password123\",\n    \"password_confirmation\": \"password123\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/v1/auth/register",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "auth", "register"]
        }
      }
    },
    {
      "name": "Login",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"email\": \"test@example.com\",\n    \"password\": \"password123\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/v1/auth/login",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "auth", "login"]
        }
      }
    },
    {
      "name": "Create Business",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "Authorization",
            "value": "Bearer {{auth_token}}"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"name\": \"Awesome Business\",\n    \"description\": \"A great place to work\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/v1/businesses",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "businesses"]
        }
      }
    },
    {
      "name": "List Businesses",
      "request": {
        "method": "GET",
        "header": [],
        "url": {
          "raw": "{{base_url}}/api/v1/businesses",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "businesses"]
        }
      }
    },
    {
      "name": "Create Review",
      "request": {
        "method": "POST",
        "header": [
          {
            "key": "Content-Type",
            "value": "application/json"
          },
          {
            "key": "Authorization",
            "value": "Bearer {{auth_token}}"
          }
        ],
        "body": {
          "mode": "raw",
          "raw": "{\n    \"business_id\": 1,\n    \"rating\": 5,\n    \"title\": \"Amazing!\",\n    \"body\": \"Great service!\"\n}"
        },
        "url": {
          "raw": "{{base_url}}/api/v1/reviews",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "reviews"]
        }
      }
    },
    {
      "name": "List Reviews",
      "request": {
        "method": "GET",
        "header": [],
        "url": {
          "raw": "{{base_url}}/api/v1/reviews?business_id=1",
          "host": ["{{base_url}}"],
          "path": ["api", "v1", "reviews"],
          "query": [
            {
              "key": "business_id",
              "value": "1"
            }
          ]
        }
      }
    }
  ],
  "variable": [
    {
      "key": "base_url",
      "value": "http://localhost:8000"
    },
    {
      "key": "auth_token",
      "value": "YOUR_AUTH_TOKEN_HERE"
    }
  ]
}