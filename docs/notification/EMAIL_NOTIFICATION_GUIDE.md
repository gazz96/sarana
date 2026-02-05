# Email Notification System Guide

## Overview

The SARANA application now includes a comprehensive email notification system with queue-based processing, multiple email templates, and automated reminder functionality.

## Features

### ✅ Email Templates
- **Workflow Notifications**: Professional HTML emails for problem status changes
- **Reminder Emails**: Automated reminder system for pending tasks
- **Alert Emails**: Urgent notification system for critical issues
- **Completed Workflow**: Special template for finished problems with timeline

### ✅ Queue System
- **Bulk Email Processing**: Handle large recipient lists efficiently
- **Job Chaining**: Process emails in chunks to prevent server overload
- **Error Handling**: Comprehensive error logging and retry mechanisms
- **Database Queue**: Persistent queue storage for reliability

### ✅ Automation
- **Daily Reminders**: Automatic reminders for pending problems
- **Overdue Alerts**: Alerts for problems exceeding time limits
- **Scheduled Processing**: Queue processing every 5 minutes
- **Cron Jobs**: Automated task scheduling

## Configuration

### Environment Variables (.env)

```bash
# Email Settings
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_FROM_ADDRESS=noreply@sarpras.local
MAIL_FROM_NAME="SARANA System"

# Notification Settings
NOTIFICATION_EMAIL_ENABLED=true
EMAIL_QUEUE_ENABLED=true
EMAIL_QUEUE_CONNECTION=database
NOTIFICATION_RETENTION_DAYS=30
NOTIFICATION_MAX_PER_USER=100
```

### Queue Configuration

The email queue uses these settings from `config/notifications.php`:

```php
'queue' => [
    'enabled' => env('EMAIL_QUEUE_ENABLED', true),
    'connection' => env('EMAIL_QUEUE_CONNECTION', 'database'),
    'chunk_size' => env('EMAIL_QUEUE_CHUNK_SIZE', 50),
    'delay_between_chunks' => env('EMAIL_QUEUE_DELAY_BETWEEN_CHUNKS', 2),
],
```

## Usage

### Basic Workflow Notification

```php
use App\Services\EmailNotificationService;

$emailService = new EmailNotificationService();
$emailService->sendWorkflowNotification($users, $problem, 'problem_finished');
```

### Send Reminder

```php
$emailService->sendReminder(
    $users,
    'Problem Menunggu Penanganan',
    'Mohon segera ditangani.',
    route('problems.show', $problem),
    'Lihat Problem',
    'Additional context here'
);
```

### Send Alert

```php
$emailService->sendAlert(
    $users,
    'Problem Overdue',
    'Problem melewati batas waktu.',
    '⚠️ PERLU PERHATIAN SEGERA',
    'Detail informasi tambahan',
    route('problems.index'),
    'Lihat Semua Problem'
);
```

### Send to Role

```php
$emailService->sendToRole('teknisi', 'reminder', [
    'title' => 'Tugas Baru',
    'message' => 'Anda memiliki tugas baru.',
    'action_url' => route('dashboard.index'),
    'action_text' => 'Lihat Dashboard'
]);
```

## Console Commands

### Process Email Queue
```bash
php artisan emails:process-queue
```

### Send Reminders Manually
```bash
php artisan emails:send-reminders
```

### Queue Worker (Background)
```bash
php artisan queue:work --tries=3 --timeout=300
```

## Scheduled Tasks

The system automatically schedules these tasks:

1. **Daily Reminders** (9 AM Asia/Jakarta):
   - Sends reminders for pending problems
   - Sends alerts for overdue problems

2. **Queue Processing** (Every 5 minutes):
   - Processes pending email jobs
   - Handles failed job retries

## Email Templates

### Workflow Events
- `problem_created` - New problem created
- `problem_submitted` - Problem submitted for processing
- `problem_accepted` - Problem accepted by technician
- `problem_in_progress` - Problem being worked on
- `problem_finished` - Problem completed
- `problem_approved_management` - Management approval
- `problem_approved_admin` - Admin approval
- `problem_approved_finance` - Finance approval (completed)
- `problem_cancelled` - Problem cancelled

### Template Locations
- `resources/views/emails/workflow/default.blade.php` - Standard workflow emails
- `resources/views/emails/workflow/completed.blade.php` - Completion emails
- `resources/views/emails/reminder.blade.php` - Reminder emails
- `resources/views/emails/alert.blade.php` - Alert emails

## Testing

### Test Email Setup
```bash
php artisan tinker
>>> Mail::raw('Test email', function($msg) { $msg->to('test@example.com')->subject('Test'); });
```

### Test Workflow Email
```bash
php artisan tinker
>>> $problem = App\Models\Problem::first();
>>> $users = App\Models\User::whereHas('roles', function($q) { $q->where('name', 'admin'); })->get();
>>> app(App\Services\EmailNotificationService::class)->sendWorkflowNotification($users, $problem, 'problem_finished');
```

### Test Queue Processing
```bash
php artisan queue:work --once
```

## Monitoring

### Check Queue Status
```bash
php artisan queue:failed
php artisan queue:retry all
```

### View Logs
```bash
tail -f storage/logs/laravel.log | grep -i email
```

### Monitor Email Jobs
```bash
php artisan queue:monitor
```

## Troubleshooting

### Emails Not Sending
1. Check queue worker is running: `ps aux | grep queue:work`
2. Check mail configuration: `php artisan config:cache`
3. Test mail connection: `php artisan tinker` → `Mail::raw(...)`

### Queue Jobs Failing
1. Check failed jobs: `php artisan queue:failed`
2. Retry failed jobs: `php artisan queue:retry all`
3. Clear queue: `php artisan queue:flush`

### Scheduled Tasks Not Running
1. Check cron setup: `crontab -l`
2. Test schedule: `php artisan schedule:run`
3. Check schedule list: `php artisan schedule:list`

## Performance Optimization

### Queue Configuration
- Adjust `chunk_size` based on server capacity
- Set appropriate `delay_between_chunks` to prevent server overload
- Use Redis queue for better performance (optional)

### Email Optimization
- Enable queue processing for bulk emails
- Use chunking for large recipient lists
- Implement rate limiting for external email services

## Security Considerations

1. **Email Validation**: All email addresses are validated before sending
2. **Rate Limiting**: Built-in protection against email flooding
3. **Error Handling**: Comprehensive error logging and monitoring
4. **Access Control**: Email preferences per user for GDPR compliance

## Files Created/Modified

### New Files
- `app/Mail/ReminderEmail.php` - Reminder email mailable
- `app/Mail/AlertEmail.php` - Alert email mailable
- `app/Jobs/SendBulkEmails.php` - Bulk email job handler
- `app/Services/EmailNotificationService.php` - Email service layer
- `app/Console/Commands/SendEmailReminders.php` - Reminder command
- `app/Console/Commands/ProcessEmailQueue.php` - Queue processor command
- `resources/views/emails/reminder.blade.php` - Reminder template
- `resources/views/emails/alert.blade.php` - Alert template

### Modified Files
- `.env` - Added email and queue configuration
- `config/notifications.php` - Enhanced with queue settings
- `app/Console/Kernel.php` - Added scheduled tasks

## Status

✅ **NOTIF-001 COMPLETED** - Email Notification System fully implemented with:
- Email templates for all notification types
- Queue-based email processing
- Automated reminder and alert system
- Comprehensive error handling and logging
- Console commands for manual operation
- Scheduled tasks for automation

The email notification system is production-ready and fully integrated with the existing SARANA workflow.