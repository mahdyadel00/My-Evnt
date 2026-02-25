# Socialite API Documentation

## Overview
This API provides social authentication functionality using Laravel Socialite for Google and Facebook OAuth.

## Endpoints

### 1. Google OAuth

#### Redirect to Google
```
GET /api/v1/{role}/google/redirect
```

**Description:** Redirects user to Google OAuth consent screen.

**Parameters:**
- `role`: User role (user, figure, company)

**Response:** Redirect to Google OAuth

---

#### Google Callback
```
GET /api/v1/{role}/google/callback
```

**Description:** Handles Google OAuth callback and creates/logs in user.

**Response:** Redirect to dashboard with success/error message

---

### 2. Facebook OAuth

#### Redirect to Facebook
```
GET /api/v1/{role}/facebook/redirect
```

**Description:** Redirects user to Facebook OAuth consent screen.

**Parameters:**
- `role`: User role (user, figure, company)

**Response:** Redirect to Facebook OAuth

---

#### Facebook Callback
```
GET /api/v1/{role}/facebook/callback
```

**Description:** Handles Facebook OAuth callback and creates/logs in user.

**Response:** Redirect to dashboard with success/error message

---

### 3. Get Social Profile

#### Get User Profile from Social Provider
```
POST /api/v1/{role}/social/profile
```

**Description:** Retrieves user profile information from social provider using access token.

**Parameters:**
- `role`: User role (user, figure, company)

**Request Body:**
```json
{
    "provider": "google|facebook",
    "access_token": "string"
}
```

**Validation Rules:**
- `provider`: required|string|in:google,facebook
- `access_token`: required|string|min:10

**Success Response (200):**
```json
{
    "success": true,
    "data": {
        "id": "123456789",
        "name": "John Doe",
        "email": "john@example.com",
        "avatar": "https://example.com/avatar.jpg"
    }
}
```

**Error Response (400):**
```json
{
    "success": false,
    "message": "Validation failed"
}
```

**Error Response (500):**
```json
{
    "success": false,
    "message": "Unable to retrieve user profile"
}
```

## Database Schema

### Users Table
The following fields are used for social authentication:

- `social_id`: Stores the social provider user ID
- `social_type`: Stores the provider type (google, facebook)
- `avatar`: Stores the user's avatar URL (if column exists)
- `email_verified_at`: Automatically set for social users
- `is_active`: Set to true for social users

## Error Handling

All endpoints include comprehensive error handling:

1. **Try-catch blocks** for all operations
2. **Logging** of all errors and warnings
3. **Graceful fallbacks** for missing data
4. **User-friendly error messages**

## Security Features

1. **Input validation** using Form Request classes
2. **Rate limiting** on authentication endpoints
3. **Secure password generation** for social users
4. **Email verification** for social users
5. **Proper error logging** without exposing sensitive data

## Usage Examples

### Frontend Integration

#### Google Login Button
```html
<a href="/api/v1/user/google/redirect" class="btn btn-google">
    <i class="fab fa-google"></i> Login with Google
</a>
```

#### Facebook Login Button
```html
<a href="/api/v1/user/facebook/redirect" class="btn btn-facebook">
    <i class="fab fa-facebook"></i> Login with Facebook
</a>
```

#### JavaScript API Call
```javascript
// Get user profile from access token
fetch('/api/v1/user/social/profile', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({
        provider: 'google',
        access_token: 'your_access_token_here'
    })
})
.then(response => response.json())
.then(data => {
    if (data.success) {
        console.log('User profile:', data.data);
    } else {
        console.error('Error:', data.message);
    }
});
```

## Configuration

### Environment Variables
Make sure to configure the following in your `.env` file:

```env
GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URI=http://your-domain.com/api/v1/user/google/callback

FACEBOOK_CLIENT_ID=your_facebook_app_id
FACEBOOK_CLIENT_SECRET=your_facebook_app_secret
FACEBOOK_REDIRECT_URI=http://your-domain.com/api/v1/user/facebook/callback
```

### Services Configuration
Configure the services in `config/services.php`:

```php
'google' => [
    'client_id' => env('GOOGLE_CLIENT_ID'),
    'client_secret' => env('GOOGLE_CLIENT_SECRET'),
    'redirect' => env('GOOGLE_REDIRECT_URI'),
],

'facebook' => [
    'client_id' => env('FACEBOOK_CLIENT_ID'),
    'client_secret' => env('FACEBOOK_CLIENT_SECRET'),
    'redirect' => env('FACEBOOK_REDIRECT_URI'),
],
```

## Migration

To add the avatar column to the users table, run:

```bash
php artisan migrate
```

This will add the `avatar` column to store social media profile pictures.

## Testing

### Test Social Authentication
1. Visit `/api/v1/user/google/redirect` to test Google OAuth
2. Visit `/api/v1/user/facebook/redirect` to test Facebook OAuth
3. Use the callback URLs to complete the authentication flow

### Test API Endpoint
```bash
curl -X POST http://your-domain.com/api/v1/user/social/profile \
  -H "Content-Type: application/json" \
  -d '{
    "provider": "google",
    "access_token": "your_access_token"
  }'
```

## Troubleshooting

### Common Issues

1. **"Unable to connect to Google/Facebook"**
   - Check your client ID and secret
   - Verify redirect URI configuration
   - Ensure OAuth consent screen is configured

2. **"Unable to retrieve user information"**
   - Check if user granted necessary permissions
   - Verify access token validity
   - Check social provider API status

3. **"Unable to create or find user account"**
   - Check database connection
   - Verify users table structure
   - Check for unique constraint violations

### Logs
Check the Laravel logs for detailed error information:
```bash
tail -f storage/logs/laravel.log
```
