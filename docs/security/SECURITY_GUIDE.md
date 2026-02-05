# Security Configuration Guide
**SARANA System - Security Setup & Best Practices**

## 🔐 Overview

This guide provides comprehensive instructions for configuring and maintaining security settings for the SARANA system.

## 🚀 Quick Start

### 1. Environment Configuration

```bash
# Copy example environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# Set secure environment variables
```

### 2. Essential Environment Variables

```env
# Application
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:your-generated-key-here

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=sarana_production
DB_USERNAME=secure_user
DB_PASSWORD=strong_password_here

# Cache & Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Mail (for notifications)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
```

## 🛡️ Security Features

### 1. Input Sanitization

**Location:** `app/Http/Middleware/SanitizeInput.php`

**Features:**
- Automatic removal of NULL bytes
- Strips dangerous HTML tags
- Removes JavaScript event handlers
- Trims whitespace

**Configuration:** Already enabled globally in `app/Http/Kernel.php`

### 2. Rate Limiting

**Location:** `app/Http/Middleware/RateLimitProtection.php`

**Current Limits:**
- Login: 5 attempts per minute
- Photo Upload: 20 attempts per minute
- Photo Delete: 30 attempts per minute

**Customization:**
```php
// In routes/web.php
Route::post('/custom-endpoint', [Controller::class, 'method'])
    ->middleware('rate.limit:100,1'); // 100 attempts per minute
```

### 3. Security Headers

**Location:** `app/Http/Middleware/SecurityHeaders.php`

**Headers Applied:**
```http
X-Frame-Options: SAMEORIGIN
X-Content-Type-Options: nosniff
X-XSS-Protection: 1; mode=block
Referrer-Policy: strict-origin-when-cross-origin
Content-Security-Policy: default-src 'self'; ...
Strict-Transport-Security: max-age=31536000; includeSubDomains
Permissions-Policy: geolocation=(), microphone=(), camera=()
```

### 4. Role-Based Access Control

**Location:** `app/Http/Middleware/CheckRole.php`

**Usage:**
```php
// Single role
Route::get('/admin', [Admin::class, 'index'])
    ->middleware('role:admin');

// Multiple roles
Route::get('/reports', [Report::class, 'index'])
    ->middleware('role:admin,keuangan,lembaga');
```

## 🔍 Security Testing

### Test Input Sanitization

```bash
# Test XSS protection
curl -X POST http://your-domain.com/auth \
  -d "email=<script>alert('xss')</script>@test.com&password=test"

# Expected: Input sanitized, script tags removed
```

### Test Rate Limiting

```bash
# Multiple rapid requests to test rate limiting
for i in {1..10}; do
  curl -X POST http://your-domain.com/auth \
    -d "email=test@test.com&password=wrong"
done

# Expected: 429 Too Many Requests after 5 attempts
```

### Verify Security Headers

```bash
# Check security headers
curl -I http://your-domain.com

# Expected output should include security headers
```

## 🔧 Maintenance Tasks

### Daily
- Monitor logs for security events
- Check rate limiting violations
- Review failed login attempts

### Weekly
- Review user access and permissions
- Check for security updates
- Audit sensitive actions

### Monthly
- Update dependencies
- Review and update security policies
- Conduct security assessment
- Test backup restoration

### Quarterly
- Full security audit
- Penetration testing
- Compliance review
- Security training for users

## 📊 Monitoring & Logging

### Security Logs Location

```
storage/logs/
├── laravel.log              # General application logs
└── security/               # Security-specific logs (recommended)
    ├── auth.log
    ├── rate_limit.log
    └── csrf.log
```

### Important Log Patterns to Monitor

```bash
# Failed login attempts
grep "Failed login" storage/logs/laravel.log

# Rate limit violations
grep "Rate limit exceeded" storage/logs/laravel.log

# CSRF errors
grep "CSRF" storage/logs/laravel.log

# Suspicious activity
grep -E "(inject|script|alert|eval)" storage/logs/laravel.log
```

## 🚨 Incident Response

### Security Incident Checklist

1. **Immediate Actions**
   - [ ] Identify affected systems
   - [ ] Preserve evidence/logs
   - [ ] Contain the breach
   - [ ] Notify stakeholders

2. **Investigation**
   - [ ] Determine root cause
   - [ ] Assess impact scope
   - [ ] Document timeline
   - [ ] Identify compromised data

3. **Recovery**
   - [ ] Patch vulnerabilities
   - [ ] Restore from backups if needed
   - [ ] Change compromised credentials
   - [ ] Monitor for recurrence

4. **Post-Incident**
   - [ ] Conduct retrospective
   - [ ] Update security policies
   - [ ] Implement preventive measures
   - [ ] Train staff on lessons learned

## 🔐 Best Practices

### Development
- Never commit `.env` files
- Use environment variables for secrets
- Enable debug mode only in development
- Keep dependencies updated

### Production
- Use HTTPS everywhere
- Enable firewalls
- Regular security updates
- Monitor system logs
- Implement backup strategy
- Use strong password policies

### Code Quality
- Follow secure coding practices
- Conduct code reviews
- Use static analysis tools
- Test security features
- Document security decisions

## 📱 User Security Guidelines

### For Administrators
- Use strong, unique passwords
- Enable 2FA when available
- Regular security training
- Limit access privileges
- Monitor user activity

### For End Users
- Create strong passwords
- Don't share credentials
- Report suspicious activity
- Keep software updated
- Be aware of phishing

## 🆘 Support & Resources

### Security Resources
- [OWASP Top 10](https://owasp.org/www-project-top-ten/)
- [Laravel Security](https://laravel.com/docs/security)
- [PHP Security Guide](https://www.php.net/manual/en/security.php)

### Emergency Contacts
- Security Team: security@your-domain.com
- System Administrator: admin@your-domain.com
- Incident Response: incident@your-domain.com

---

**Last Updated:** 2025-02-05  
**Version:** 1.0  
**Maintained By:** Development Team