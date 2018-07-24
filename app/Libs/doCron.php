<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/9/26
 * Time: 10:47
 */
$conn = new PDO("mysql:host=127.0.0.1;dbname=sanmenxia", "root", "Sanmenxia@2017",
    array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES `utf8`',
      PDO::ATTR_PERSISTENT => TRUE,
    ));

/*$conn = new PDO("mysql:host=127.0.0.1;dbname=sanmenxia", "root", "root",
    array(
      PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES `utf8`',
      PDO::ATTR_PERSISTENT => TRUE,
    ));*/

//取出下次备份时间
foreach($conn->query('SELECT * FROM `system_backup_auto`') as $row) {
    $next_date = $row['next_date'];
    $last_date = date('Y-m-d H:i:s', strtotime(date('Y-m-d', time())) + 3600 * 2);
    $cycle_type = $row['cycle_type'];
}

//执行符合条件的备份
if(isset($next_date) && !empty($next_date) && $next_date <= time()){
    $file_name = 'backup_'.date('YmdHis').rand('111','999').'.sql';
    $file_path = '/www/5260/public/backup/'.$file_name;
    $file_url = 'http://125.45.181.13/backup/'.$file_name;
    $now = date('Y-m-d H:i:s', time());
    //mysql_dump
    $params = array(
        'h'=> 'localhost',
        'u'=> 'root',
        'p'=> 'Sanmenxia@2017',
        'db'=> 'sanmenxia',
    );
    $cmd = '/usr/local/webserver/mysql/bin/mysqldump -h'.$params['h'].' '.$params['db'].' --set-gtid-purged=off > '.$file_path;
    system($cmd, $i);

    //储存备份数据
    $now = strtotime(date('Y-m-d', time()));
    switch ($cycle_type){
        case 'day':
            $next_date = $now + 3600 * 26;
            break;
        case 'week':
            $next_date = $now + 3600 * 7 * 26;
            break;
        case 'month':
            $next_date = $now + 3600 * 30 * 26;
            break;
        default:
            $next_date = '';
            break;
    }
    echo ('$next_date:'.$next_date);
    $update_sql = 'UPDATE `system_backup_auto` SET `next_date` = "'. $next_date .'", `update_date` = "'.date('Y-m-d H:i:s', time()).'", `last_date` = "'.$last_date .'" WHERE id = 1';
    $row = $conn->exec($update_sql);
    $insert_sql = 'INSERT INTO `system_backup` SET `backup_date` = "'. $last_date .'", `create_date` = "'. date('Y-m-d H:i:s', time()).'", `type` = "auto", `file_name` = "'. $file_name .'", `file_path` = "'. $file_path .'", `file_url` = "'. $file_url .'"';
    $row = $conn->exec($insert_sql);
}
else{
    echo ('没到时间，当前：'.time().'，下次：'.$next_date);
}