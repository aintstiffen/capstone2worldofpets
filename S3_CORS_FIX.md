# S3 CORS Configuration Fix

## Problem
Gallery images appear dark/black on deployed site due to CORS (Cross-Origin Resource Sharing) policy blocking the images from being loaded via WebGL.

## Solution

### Option 1: Configure S3 Bucket CORS (Recommended)

1. Go to AWS S3 Console: https://s3.console.aws.amazon.com/
2. Click on your bucket: `worldofpetss`
3. Go to the "Permissions" tab
4. Scroll down to "Cross-origin resource sharing (CORS)"
5. Click "Edit"
6. Paste the following CORS configuration:

```json
[
    {
        "AllowedHeaders": [
            "*"
        ],
        "AllowedMethods": [
            "GET",
            "HEAD"
        ],
        "AllowedOrigins": [
            "https://capstone2worldofpets-main-kso4cw.laravel.cloud",
            "http://localhost:*",
            "https://petsofworld.test",
            "*"
        ],
        "ExposeHeaders": [
            "ETag",
            "Content-Length"
        ],
        "MaxAgeSeconds": 3000
    }
]
```

7. Click "Save changes"

### Option 2: Make Bucket Publicly Accessible

If CORS doesn't work, ensure your bucket allows public read access:

1. Go to "Permissions" tab in your S3 bucket
2. Edit "Block public access (bucket settings)"
3. Uncheck "Block all public access"
4. Save changes
5. Under "Bucket Policy", add:

```json
{
    "Version": "2012-10-17",
    "Statement": [
        {
            "Sid": "PublicReadGetObject",
            "Effect": "Allow",
            "Principal": "*",
            "Action": "s3:GetObject",
            "Resource": "arn:aws:s3:::worldofpetss/*"
        }
    ]
}
```

### What I've Fixed in Code

I've updated `CircularGallery.jsx` to:
1. Only set `crossOrigin='anonymous'` for cross-domain images
2. Added error handling for failed image loads
3. Added fallback gradient display if images fail to load

## Testing

After applying S3 CORS configuration:

1. Clear browser cache (Ctrl+Shift+Delete)
2. Visit your deployed site
3. Navigate to any breed page with gallery
4. Gallery should now display images properly

## Alternative: Use CloudFront

For better performance and automatic CORS handling:

1. Create a CloudFront distribution for your S3 bucket
2. Update `AWS_URL` in `.env` to use CloudFront URL
3. CloudFront automatically handles CORS

## Deployed Site URL

Your site: https://capstone2worldofpets-main-kso4cw.laravel.cloud

Update the CORS `AllowedOrigins` to match your exact deployed domain.

---

**Status**: Code fixed ✅ | S3 CORS needs configuration ⏳
