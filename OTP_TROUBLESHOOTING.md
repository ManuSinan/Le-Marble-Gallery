# OTP Troubleshooting Guide for Live Server

## Common Issues and Solutions

### 1. Check Environment Variables on Live Server

SSH into your live server and verify your `.env` file has these WhatsApp settings:

```bash
# Enable WhatsApp OTP
USE_WHATSAPP_OTP=true

# WhatsApp API Configuration
WHATSAPP_GRAPH_URL=https://graph.facebook.com/v18.0
WHATSAPP_PHONE_NUMBER_ID=your_phone_number_id_here
WHATSAPP_API_KEY=your_access_token_here
WHATSAPP_OTP_COUNTRY_CODE=91
WHATSAPP_OTP_TEMPLATE_NAME=peak_otp
```

**Important:** After updating `.env`, you MUST clear config cache:
```bash
php artisan config:clear
php artisan config:cache
```

### 2. Verify Configuration is Loaded

Run this command on your live server to check if config is loaded correctly:
```bash
php artisan tinker
```
Then run:
```php
config('whatsapp.use_whatsapp_otp')
config('whatsapp.phone_number_id')
config('whatsapp.api_key')
```

### 3. Check Laravel Logs

Check the error logs on your live server:
```bash
tail -f storage/logs/laravel.log
```

Look for these log entries:
- `WhatsApp OTP: Function called`
- `WhatsApp OTP: Config values`
- `WhatsApp OTP: Missing config` (indicates missing env vars)
- `WhatsApp OTP: API error in response` (indicates API issues)
- `WhatsApp OTP: HTTP error` (indicates network/authentication issues)

### 4. Common Issues

#### Issue A: Missing Environment Variables
**Symptoms:** Logs show "WhatsApp OTP: Missing config"
**Solution:** 
- Add all WhatsApp variables to `.env`
- Run `php artisan config:clear && php artisan config:cache`

#### Issue B: API Authentication Failed
**Symptoms:** Logs show HTTP 401 or "Invalid OAuth access token"
**Solution:**
- Verify `WHATSAPP_API_KEY` is correct
- Check if token has expired (Meta tokens can expire)
- Regenerate token from Meta Business Suite

#### Issue C: Phone Number ID Invalid
**Symptoms:** Logs show "Invalid phone number ID"
**Solution:**
- Verify `WHATSAPP_PHONE_NUMBER_ID` matches your Meta Business Suite
- Find it at: Meta Business Suite > WhatsApp > API Setup > From

#### Issue D: Template Not Found
**Symptoms:** Logs show "Template not found" error
**Solution:**
- Verify `WHATSAPP_OTP_TEMPLATE_NAME` matches exactly (case-sensitive)
- Ensure template is approved in Meta Business Suite

#### Issue E: Network/Firewall Blocking
**Symptoms:** Timeout errors or connection refused
**Solution:**
- Check if server can reach `graph.facebook.com`
- Test: `curl -I https://graph.facebook.com`
- Check firewall rules allow outbound HTTPS

#### Issue F: Fallback to SMS Not Working
**Symptoms:** OTP not sent via WhatsApp OR SMS
**Solution:**
- If `USE_WHATSAPP_OTP=false`, check SMS configuration
- Verify SMS API credentials in `.env`

### 5. Quick Diagnostic Script

Run this on your live server to diagnose issues:

```bash
php artisan tinker
```

Then paste:
```php
// Check config
echo "WhatsApp OTP Enabled: " . (config('whatsapp.use_whatsapp_otp') ? 'YES' : 'NO') . "\n";
echo "Graph URL: " . config('whatsapp.graph_url') . "\n";
echo "Phone Number ID: " . (config('whatsapp.phone_number_id') ? 'SET' : 'MISSING') . "\n";
echo "API Key: " . (config('whatsapp.api_key') ? 'SET (' . strlen(config('whatsapp.api_key')) . ' chars)' : 'MISSING') . "\n";
echo "Country Code: " . config('whatsapp.country_code') . "\n";
echo "Template: " . config('whatsapp.otp_template_name') . "\n";
```

### 6. Test OTP Function Directly

Test the OTP function on live server:
```bash
php artisan tinker
```

Then:
```php
sendWhatsAppOtp('1234567890', '123456');
```

Check `storage/logs/laravel.log` for detailed output.

### 7. Verify Meta API Access

Test if your server can reach Meta API:
```bash
curl -X POST "https://graph.facebook.com/v18.0/YOUR_PHONE_NUMBER_ID/messages" \
  -H "Authorization: Bearer YOUR_ACCESS_TOKEN" \
  -H "Content-Type: application/json" \
  -d '{
    "messaging_product": "whatsapp",
    "to": "91XXXXXXXXXX",
    "type": "template",
    "template": {
      "name": "peak_otp",
      "language": {"code": "en"},
      "components": [{
        "type": "body",
        "parameters": [{"type": "text", "text": "123456"}]
      }]
    }
  }'
```

Replace:
- `YOUR_PHONE_NUMBER_ID` with your actual phone number ID
- `YOUR_ACCESS_TOKEN` with your API key
- `91XXXXXXXXXX` with a test phone number

### 8. Deployment Checklist

When deploying to live server, ensure:

- [ ] `.env` file has all WhatsApp variables set
- [ ] `USE_WHATSAPP_OTP=true` (if using WhatsApp)
- [ ] `USE_WHATSAPP_OTP=false` (if using SMS fallback)
- [ ] Run `php artisan config:clear`
- [ ] Run `php artisan config:cache`
- [ ] Check logs: `tail -f storage/logs/laravel.log`
- [ ] Test OTP sending functionality
- [ ] Verify network connectivity to `graph.facebook.com`

### 9. Fallback to SMS

If WhatsApp is not working, you can temporarily use SMS:

1. Set in `.env`:
   ```
   USE_WHATSAPP_OTP=false
   ```

2. Clear config cache:
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

3. Ensure SMS configuration is correct in `.env`

### 10. Contact Points

If issues persist:
1. Check Meta Business Suite for API status
2. Verify WhatsApp Business Account is active
3. Check Meta App Dashboard for token expiration
4. Review Meta API documentation for latest changes
