<?php


class UserModel implements UserModelInterface {
    private $db;
    private $table = 'users';
    private $fields = ['username', 'password', 'email', 'block'];

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Insert a new user into the database.
     * 
     * @param string $username
     * @param string $password
     * @param string $email
     * @return bool
     */
    public function insertUser($username, $password, $email) {
        $stmt = $this->db->prepare('INSERT INTO ' . $this->table . ' (username, password, email) VALUES (:username, :password, :email)');
        return $stmt->execute([
            ':username' => $username,
            ':password' => password_hash($password, PASSWORD_BCRYPT),
            ':email' => $email
        ]);
    }

    /**
     * Retrieve a user by username and password.
     * 
     * @param string $username
     * @param string $password
     * @return array|false
     */
    public function getUser($username, $password) {
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    /**
     * Update a user's email by username.
     * 
     * @param string $username
     * @param string $email
     * @return bool
     */
    public function updateUserEmail($username, $email) {
        $stmt = $this->db->prepare('UPDATE ' . $this->table . ' SET email = :email WHERE username = :username');
        return $stmt->execute([
            ':username' => $username,
            ':email' => $email
        ]);
    }

    /**
     * Delete a user by username.
     * 
     * @param string $username
     * @return bool
     */
    public function deleteUser($username) {
        $stmt = $this->db->prepare('DELETE FROM ' . $this->table . ' WHERE username = :username');
        return $stmt->execute([':username' => $username]);
    }

      /**
     * Block a user by username.
     * 
     * @param string $username
     * @return bool
     */

    public function blockAccount($username){
        $block = true;
        $stmt = $this->db->prepare('UPDATE ' . $this->table . ' SET block = :block WHERE username = :username');
      $result =  $stmt->execute([
            ':username' => $username,
            ':block' => $block
        ]);

        if($result){
            return true;
        }else{
            return false;
        }
    }
      /**
     * Get Block a user by username.
     * 
     * @param string $username
     * @return bool
     */

    public function getblockAccount($username){
        
        $stmt = $this->db->prepare('SELECT * FROM ' . $this->table . ' WHERE username = :username');
        $stmt->execute([':username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            return $user['block'];
        } else {
          
            return null; 
        }
    }
}

?>
