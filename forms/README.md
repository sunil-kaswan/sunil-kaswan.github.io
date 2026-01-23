# Contact Form Setup

## ✅ Current Setup

Form is configured to submit directly to `forms/contact.php` via AJAX (no page reload).

### How it works:
1. User fills the form and clicks "Send Message"
2. JavaScript (`validate.js`) prevents default form submission
3. Data is sent via AJAX (fetch) to `forms/contact.php`
4. PHP processes the data and sends email
5. Success/error message is displayed without page reload

## 📧 Email Configuration

### Option 1: Using PHPMailer (Recommended for Gmail)

1. **Install PHPMailer:**
   ```bash
   composer require phpmailer/phpmailer
   ```
   Or manually download from: https://github.com/PHPMailer/PHPMailer

2. **Configure Gmail App Password:**
   - Go to: https://myaccount.google.com/apppasswords
   - Generate App Password for "Mail"
   - Update `forms/contact.php`:
     ```php
     $mail->Username = 'kaswansunil26@gmail.com';
     $mail->Password = 'your-16-character-app-password';
     ```

### Option 2: Using PHP mail() Function

If PHPMailer is not installed, the form will automatically use PHP's `mail()` function.

**Note:** `mail()` function requires proper server configuration. On localhost (XAMPP/WAMP), you may need to configure SMTP in `php.ini`.

## 🧪 Testing

1. **Start your local server** (XAMPP/WAMP)
2. **Open website** at `http://localhost/Kelly/contact.html`
3. **Fill and submit the form**
4. **Check**:
   - Success message should appear
   - Email should be sent to `kaswansunil26@gmail.com`

## 🔧 Troubleshooting

- **Form not submitting?** Check browser console for errors
- **Email not received?** Check spam folder, verify SMTP settings
- **Network error?** Ensure you're running on a web server (not file://)

## 📝 Files Involved

- `contact.html` - Form HTML
- `forms/contact.php` - PHP handler
- `assets/vendor/php-email-form/validate.js` - AJAX submission

