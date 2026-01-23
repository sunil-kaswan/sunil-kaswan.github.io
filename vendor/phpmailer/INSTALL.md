# PHPMailer Installation Instructions

## Option 1: Using Composer (Recommended)

1. Install Composer if you don't have it: https://getcomposer.org/
2. Open terminal/command prompt in your project root directory
3. Run: `composer require phpmailer/phpmailer`
4. PHPMailer will be installed in `vendor/phpmailer/phpmailer/`

## Option 2: Manual Installation

1. Download PHPMailer from: https://github.com/PHPMailer/PHPMailer/releases
2. Extract the ZIP file
3. Copy the `src` folder from PHPMailer to `vendor/phpmailer/phpmailer/src/`
4. The structure should be: `vendor/phpmailer/phpmailer/src/PHPMailer.php`

## Gmail Configuration

If using Gmail SMTP:

1. Go to your Google Account settings
2. Enable 2-Step Verification
3. Generate an App Password:
   - Go to: https://myaccount.google.com/apppasswords
   - Select "Mail" and "Other (Custom name)"
   - Enter "Portfolio Contact Form"
   - Copy the generated 16-character password
4. Update `forms/contact.php`:
   - Set `$mail->Username` to your Gmail address
   - Set `$mail->Password` to the App Password (not your regular password)

## Note

If PHPMailer is not installed, the form will automatically use PHP's built-in `mail()` function as a fallback.
