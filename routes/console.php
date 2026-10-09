<?php

use Illuminate\Support\Facades\Schedule;

// Hostinger: add a cron entry `* * * * * cd ~/public_html && php artisan schedule:run >> /dev/null 2>&1`.
Schedule::command('clinic:remind')->dailyAt('16:00');
