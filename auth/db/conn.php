<?php
namespace db;

class conn {
    private const SERVER = "localhost";
    private const USERNAME = "hourtracker";
    private const PASSWORD = "xM5CH@/kSBM6iX6z";
    private const DBNAME = "hourtracker";
    public $conn;
    public function __construct() {
        self::$conn = new \mysqli(self::SERVER, self::USERNAME, self::PASSWORD, self::DBNAME);
        if (self::$conn->connect_error) {
            die("Connection failed: " . self::$conn->connect_error);
        }
    }
    public function check_username($username): bool {
        $sql = "SELECT * FROM users WHERE username = ?";
        $stmt = self::$conn->prepare($sql);
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
        
    }
    

}
$dbcon = new conn();
?>