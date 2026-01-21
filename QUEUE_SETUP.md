   php artisan queue:work database --queue=invoices,emails,default
   
# Queue Setup for Invoice Generation & Email Sending

This application uses Laravel queues to process invoice generation and email sending asynchronously for better performance.

## Setup Instructions

### 1. Configure Queue Connection

Ensure your `.env` file has the queue connection set:

```env
QUEUE_CONNECTION=database
```

**Note:** For production, consider using `redis` for better performance:
```env
QUEUE_CONNECTION=redis
```

### 2. Create Jobs Table (if not exists)

If you haven't already, create the jobs table:

```bash
php artisan queue:table
php artisan migrate
```

This will create the `jobs` and `failed_jobs` tables in your database.

### 3. Start Queue Worker

You need to run a queue worker to process the jobs. Choose one of the following methods:

#### Option A: Using Supervisor (Recommended for Production)

Create a supervisor configuration file at `/etc/supervisor/conf.d/laravel-worker.conf`:

```ini
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /home/expensi.in/public_html/bigbest-admin/artisan queue:work database --sleep=3 --tries=3 --max-time=3600 --queue=invoices,emails,default
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=expen5770
numprocs=2
redirect_stderr=true
stdout_logfile=/home/expensi.in/public_html/bigbest-admin/storage/logs/worker.log
stopwaitsecs=3600
```

Then reload supervisor:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
```

#### Option B: Using Systemd (Alternative for Production)

Create a service file at `/etc/systemd/system/laravel-worker.service`:

```ini
[Unit]
Description=Laravel Queue Worker
After=network.target

[Service]
User=expen5770
Group=expen5770
Restart=always
ExecStart=/usr/bin/php /home/expensi.in/public_html/bigbest-admin/artisan queue:work database --sleep=3 --tries=3 --max-time=3600 --queue=invoices,emails,default

[Install]
WantedBy=multi-user.target
```

Then enable and start:
```bash
sudo systemctl enable laravel-worker
sudo systemctl start laravel-worker
```

#### Option C: Manual (Development/Testing)

For development or testing, you can run the worker manually:

```bash
php artisan queue:work database --queue=invoices,emails,default
```

Or run it in the background:
```bash
nohup php artisan queue:work database --queue=invoices,emails,default > storage/logs/queue.log 2>&1 &
```

### 4. Monitor Queue Status

Check if jobs are being processed:

```bash
# View queue status
php artisan queue:monitor database:default,invoices,emails

# View failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### 5. Queue Configuration

The application uses two dedicated queues:
- **`invoices`**: For invoice PDF generation jobs
- **`emails`**: For invoice email sending jobs

This allows you to:
- Process invoices and emails in parallel
- Scale workers independently
- Prioritize certain job types

## Performance Benefits

1. **Non-blocking**: Invoice generation and email sending don't slow down the main application
2. **Scalable**: Can process multiple invoices/emails in parallel
3. **Resilient**: Automatic retries on failure (3 attempts with exponential backoff)
4. **Organized**: Separate queues for different job types

## Job Details

### GenerateInvoiceJob
- **Queue**: `invoices`
- **Retries**: 3 attempts
- **Timeout**: 120 seconds
- **Backoff**: 30s, 60s, 120s

### SendInvoiceEmailJob
- **Queue**: `emails`
- **Retries**: 3 attempts
- **Timeout**: 60 seconds
- **Backoff**: 30s, 60s, 120s

## Troubleshooting

### Jobs not processing
1. Check if queue worker is running: `ps aux | grep queue:work`
2. Check logs: `tail -f storage/logs/laravel.log`
3. Check failed jobs: `php artisan queue:failed`

### Jobs failing
1. Check error logs: `storage/logs/laravel.log`
2. Verify email configuration in `.env`
3. Verify file permissions for `public/documents/orders/`
4. Check database connection

### High memory usage
- Reduce `numprocs` in supervisor config
- Add `--max-jobs=1000` to queue:work command to restart worker after N jobs

## Production Recommendations

1. **Use Redis** instead of database for better performance
2. **Use Supervisor** for automatic restarts
3. **Monitor queue** with tools like Laravel Horizon (if using Redis)
4. **Set up alerts** for failed jobs
5. **Regular cleanup** of old jobs: `php artisan queue:prune-failed --hours=48`

