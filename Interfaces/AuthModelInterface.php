<?php

interface AuthModelInterface{

    public function recordLoginAttempt($username, $ipAddress, $password);
    public function countRecentLoginAttempts($username, $ipAddress, $timeFrame);
    public function countRecentTotalLoginAttempts($timeFrame);
    public function clearLoginAttempts($username, $ipAddress);
    public function countRecentTotalLoginAttemptsBySameIP($timeFrame, $ipAddress);
    public function blockIP($ipAddress);
    public function getblockIP($ipAddress);

}


?>