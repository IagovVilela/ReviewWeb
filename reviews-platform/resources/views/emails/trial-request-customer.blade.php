<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Free Trial Request - Reviews Platform</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
            border-top: 5px solid #8b5cf6;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e0e0e0;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #8b5cf6 0%, #7c3aed 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
        }
        .title {
            color: #2c3e50;
            font-size: 24px;
            font-weight: bold;
            margin: 15px 0;
        }
        .message-box {
            background-color: #ede9fe;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border-left: 4px solid #8b5cf6;
        }
        .info-box {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .highlight {
            background-color: #fef3c7;
            padding: 15px;
            border-radius: 6px;
            margin: 20px 0;
            border: 1px solid #fbbf24;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            color: #666;
            font-size: 12px;
        }
        .feature-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        .feature-list li {
            padding: 10px 0;
            padding-left: 30px;
            position: relative;
        }
        .feature-list li:before {
            content: "✓";
            position: absolute;
            left: 0;
            color: #8b5cf6;
            font-weight: bold;
            font-size: 18px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">
                ✅
            </div>
            <div class="title">Request Received Successfully!</div>
            <p style="color: #666; margin: 10px 0;">Hello {{ $contactName }},</p>
        </div>

        <div class="message-box">
            <h3 style="margin-top: 0; color: #2c3e50;">🎉 Thank you for your interest in Reviews Platform!</h3>
            <p>We've received your free trial request for <strong>{{ $companyName }}</strong>.</p>
            <p>Our team will contact you shortly with complete details about:</p>
            <ul style="margin: 15px 0; padding-left: 20px;">
                <li>Pricing and plans</li>
                <li>Features and capabilities</li>
                <li>Setup and onboarding process</li>
                <li>Any questions you may have</li>
            </ul>
        </div>

        <div class="highlight">
            <h4 style="margin: 0;">⏰ What happens next?</h4>
            <p style="margin: 10px 0;"><strong>Someone from our team will be in touch within 24 hours</strong> to provide you with full details and help you get started.</p>
        </div>

        <div class="info-box">
            <h3 style="margin-top: 0; color: #2c3e50;">🚀 What you'll get with Reviews Platform:</h3>
            <ul class="feature-list">
                <li>Smart review redirection - positive reviews go to Google Maps</li>
                <li>Reputation protection - capture negative feedback privately</li>
                <li>Complete dashboard with real-time analytics</li>
                <li>Automatic email notifications for negative reviews</li>
                <li>WhatsApp contact capture for remarketing</li>
                <li>Multi-language support (English & Portuguese)</li>
                <li>Customizable review pages with your branding</li>
                <li>Dark mode support for better user experience</li>
            </ul>
        </div>

        <div style="background-color: #d1f4e8; padding: 15px; border-radius: 6px; border: 1px solid #86efac; margin: 20px 0; text-align: center;">
            <p style="margin: 0;"><strong>🎁 Special Bonus:</strong> Your customers will be incentivized to review via a <strong>£10,000 prize draw</strong> - paid by us at no cost to you!</p>
        </div>

        <div class="footer">
            <p><strong>Reviews Platform</strong></p>
            <p>The complete solution for intelligent online review management</p>
            <p style="margin-top: 15px;">Need immediate assistance? Feel free to reply to this email.</p>
            <p>© 2025 Reviews Platform - All rights reserved</p>
        </div>
    </div>
</body>
</html>
