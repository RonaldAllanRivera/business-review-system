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

## Testing Workflow

### 1. Authentication
1. **Register a new user** using the "Auth > Register User" request
   - This will create a new user account
   - The response includes an authentication token

2. **Login** using the "Auth > Login" request
   - Use the same credentials you registered with
   - Copy the `token` from the response
   - Set the `auth_token` collection variable in Postman:
     1. Click on the collection in the sidebar
     2. Go to the "Variables" tab
     3. Update the `auth_token` value with your token
     4. Click "Save"

### 2. Profile Management
1. **Get Profile** (GET `/api/v1/profile`)
   - View your profile information
   - Returns: User details including name, email, bio, and avatar_url

2. **Update Profile** (PUT `/api/v1/profile`)
   - Update your name and bio
   - Example request body:
     ```json
     {
         "name": "Updated Name",
         "bio": "This is my updated bio"
     }
     ```

3. **Upload Avatar** (POST `/api/v1/profile/avatar`)
   - Upload a profile picture
   - Supported formats: JPG, JPEG, PNG, WebP
   - Max size: 2MB
   - The avatar will be available at the `avatar_url` in your profile

### 3. Business Operations
1. **Create Business** (POST `/api/v1/businesses`)
   - Requires authentication
   - Required fields: `name`, `description`

2. **List Businesses** (GET `/api/v1/businesses`)
   - Public endpoint, no authentication required
   - Returns paginated list of businesses

### 4. Review Operations
1. **Create Review** (POST `/api/v1/reviews`)
   - Requires authentication
   - Required fields: `business_id`, `rating` (1-5), `title`, `body`

2. **List Reviews** (GET `/api/v1/reviews?business_id=1`)
   - Public endpoint, no authentication required
   - Filter by business_id

## Collection Variables
- `{{base_url}}`: Your API base URL (default: `http://localhost:8000`)
- `{{auth_token}}`: Your authentication token (set after login)

## Example JSON for Import
```json

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