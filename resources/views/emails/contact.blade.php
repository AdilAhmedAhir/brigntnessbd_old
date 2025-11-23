<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Message</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #1a1a1a 0%, #2c2c2c 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .field {
            margin-bottom: 20px;
            padding: 15px;
            background: white;
            border-radius: 8px;
            border-left: 4px solid #d4af37;
        }
        .field-label {
            font-weight: bold;
            color: #1a1a1a;
            margin-bottom: 5px;
        }
        .field-value {
            color: #555;
            line-height: 1.5;
        }
        .message-content {
            background: white;
            padding: 20px;
            border-radius: 8px;
            border: 1px solid #e9ecef;
            white-space: pre-line;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding: 20px;
            background: #1a1a1a;
            color: white;
            border-radius: 8px;
            font-size: 14px;
        }
        .golden {
            color: #d4af37;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📧 New Contact Form Message</h1>
        <p>You have received a new message from your website contact form</p>
    </div>
    
    <div class="content">
        <div class="field">
            <div class="field-label">👤 Name:</div>
            <div class="field-value">{{ $name }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">📧 Email:</div>
            <div class="field-value">
                <a href="mailto:{{ $email }}" style="color: #d4af37; text-decoration: none;">{{ $email }}</a>
            </div>
        </div>
        
        @if($phone)
        <div class="field">
            <div class="field-label">📞 Phone:</div>
            <div class="field-value">
                <a href="tel:{{ $phone }}" style="color: #d4af37; text-decoration: none;">{{ $phone }}</a>
            </div>
        </div>
        @endif
        
        <div class="field">
            <div class="field-label">📋 Subject:</div>
            <div class="field-value">{{ $subject }}</div>
        </div>
        
        <div class="field">
            <div class="field-label">💬 Message:</div>
            <div class="message-content">{{ $messageContent }}</div>
        </div>
    </div>
    
    <div class="footer">
        <p><span class="golden">💡 Tip:</span> You can reply directly to this email to respond to the customer.</p>
        <p style="margin: 0; opacity: 0.8;">This message was sent from your website's contact form.</p>
    </div>
</body>
</html>
