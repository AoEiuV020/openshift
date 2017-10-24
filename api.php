<?php
/*
 * 访问只输出true or false,
 * 异常直接不输出，
 */
function action($a) {
    $rcsocks = new Process();
    $pidFileName = 'pid';
    if(file_exists($pidFileName)) {
        $pid = file_get_contents($pidFileName);
        $rcsocks->setPid($pid);
    }
    switch($a) {
    case 'start':
        if ($rcsocks->status()) {
            // 如果已经启动返回false,
            return false;
        }
        $ip = $_GET['ip'];
        $port = $_GET['port'];
        $rcsocks->setCommand('./ssocks/rssocks -s '.$ip.':'.$port);
        $rcsocks->start();
        file_put_contents($pidFileName, $rcsocks->getPid());
        return $rcsocks->status();
    case 'stop':
        return $rcsocks->stop() && unlink($pidFileName);
    case 'status':
        return $rcsocks->status();
    default:
        return false;
    }
}
if(action($_GET['action'])) {
    echo 'true';
} else {
    echo 'false';
}
?>
<?php
/* An easy way to keep in track of external processes.
 * Ever wanted to execute a process in php, but you still wanted to have somewhat controll of the process ? Well.. This is a way of doing it.
 * @compability: Linux only. (Windows does not work).
 * @author: Peec
 */
class Process{
    private $pid;
    private $command;

    public function __construct($cl=false){
        if ($cl != false){
            $this->command = $cl;
            $this->runCom();
        }
    }
    private function runCom(){
        $command = 'nohup '.$this->command.' > /dev/null 2>&1 & echo $!';
        exec($command ,$op);
        $this->pid = (int)$op[0];
    }

    public function setCommand($command){
        $this->command = $command;
    }

    public function setPid($pid){
        $this->pid = $pid;
    }

    public function getPid(){
        return $this->pid;
    }

    public function status(){
        $command = 'ps -p '.$this->pid;
        exec($command,$op);
        if (!isset($op[1]))return false;
        else return true;
    }

    public function start(){
        if ($this->command != '')$this->runCom();
        else return true;
    }

    public function stop(){
        $command = 'kill -2 '.$this->pid;
        exec($command);
        if ($this->status() == false)return true;
        else return false;
    }
}
?>
