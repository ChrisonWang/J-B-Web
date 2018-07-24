<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Libs\Logs;

class AutoBackup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'autoBackup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'autoBackup';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $info = array(
	        'cmd'=> 'crontab',
	        'update_date'=> date('Y-m-d H:i:s', time()),
	        'save_date'=> date('Y-m-d H:i:s', time()),
	        'meg'=> '执行crontab定时任务',
        );
	    Logs::cron_log(['succ'=> 'no', 'message'=> $info, 'type'=> 'backup']);
    }
}
