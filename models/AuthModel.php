

<?php

class AuthModel implements AuthModelInterface
{

    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function recordLoginAttempt($username, $ipAddress, $password)
    {
        $stmt = $this->db->prepare('INSERT INTO login_attempts (username, ip_address, attempt_time, password) VALUES (:username, :ip_address, NOW(), :password)');
        $save =  $stmt->execute([
            ':username' => $username,
            ':ip_address' => $ipAddress,
            ':password' => $password,
        ]);
        echo $save;
    }

    public function countRecentLoginAttempts($username, $ipAddress, $timeFrame = '15 MINUTE')
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM login_attempts WHERE (username = :username) AND attempt_time > (NOW() - INTERVAL ' . $timeFrame . ')'
        );
        $stmt->execute([
            ':username' => $username
        ]);
        return $stmt->fetchColumn();
    }


    public function countRecentTotalLoginAttemptsBySameIP($timeFrame = '1 MINUTE', $ipAddress)
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM login_attempts WHERE attempt_time > (NOW() - INTERVAL ' . $timeFrame . ') AND ip_address = :ipAddress'
        );
        $stmt->execute([
            ':ipAddress' => $ipAddress
        ]);
        return $stmt->fetchColumn();
    }


    public function countRecentTotalLoginAttempts($timeFrame = '1 MINUTE')
    {
        $stmt = $this->db->prepare(
            'SELECT COUNT(*) FROM login_attempts WHERE attempt_time > (NOW() - INTERVAL ' . $timeFrame . ')'
        );
        $stmt->execute();
        return $stmt->fetchColumn();
    }



    public function clearLoginAttempts($username, $ipAddress)
    {
        $stmt = $this->db->prepare('DELETE FROM login_attempts WHERE username = :username OR ip_address = :ip_address');
        $stmt->execute([
            ':username' => $username,
            ':ip_address' => $ipAddress
        ]);
    }


    public function blockIP($ipAddress)
    {
        $stmt = $this->db->prepare('INSERT INTO ip_block (ip_address, block_time) VALUES (:ip_address, NOW())');
        $stmt->execute([
            ':ip_address' => $ipAddress
        ]);
    }


    public function getblockIP($ipAddress)
    {

        $stmt = $this->db->prepare('SELECT * FROM ip_block WHERE ip_address = :ipAddress');
        $stmt->execute([':ipAddress' => $ipAddress]);
        $ip = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ip) {
            return $ip['ip_address'];
        } else {

            return null;
        }
    }
}

?>