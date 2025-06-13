# N8N Integration with Laravel Job Portal

This document explains how to integrate the Laravel Job Portal application with n8n for CV processing.

## Setup Instructions

### 1. Configure Environment Variables

Add the following to your `.env` file:

```
# n8n Integration
N8N_WEBHOOK_URL=http://your-n8n-instance:5678/webhook/your-webhook-id
```

Replace `your-n8n-instance` with your n8n server address and `your-webhook-id` with the actual webhook ID from n8n.

### 2. n8n Workflow Setup

1. Create a new workflow in n8n
2. Add an HTTP Webhook node as a trigger
   - Set the node to receive a POST request
   - This is the URL you will use in the `N8N_WEBHOOK_URL` env variable
3. Configure your workflow to process the incoming PDF data:
   - The incoming data will contain:
     - `fileContent`: Base64-encoded PDF file
     - `fileName`: Original filename
     - `userId`: User ID from Laravel
     - `resumeId`: Resume ID from Laravel
     - `callbackUrl`: URL to send results back to Laravel

4. Set up nodes to:
   - Decode the base64 PDF content
   - Process the PDF to extract information
   - Send the results back to Laravel using the provided callback URL

### 3. Callback to Laravel

After processing the CV, n8n should make a POST request to the callback URL with:

```json
{
  "userId": 123,
  "resumeId": 456,
  "parsedData": {
    "skills": ["PHP", "Laravel", "JavaScript"],
    "experience": "...",
    "education": "...",
    "otherFields": "..."
  }
}
```

## Testing

1. Upload a CV from the candidate profile
2. Check Laravel logs for successful CV transmission to n8n
3. Verify n8n workflow execution
4. Check Laravel logs for successful callback processing

## Troubleshooting

If the integration is not working as expected:

1. Check Laravel logs for any errors
2. Verify n8n is running and accessible
3. Confirm the webhook URL is correctly configured
4. Check n8n logs for any errors processing the CV
5. Test the callback URL with a simple POST request to ensure it's working 