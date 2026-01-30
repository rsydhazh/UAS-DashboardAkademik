<?php
class App {
    public $config;
    public $connection;

    public function __construct($config) {
        $this->config = $config;

        // Cek apakah session sudah dimulai sebelum memanggil session_name dan session_start
        if (session_status() == PHP_SESSION_NONE) {
            session_name("selasa");
            session_start();
        }

        try {
            // Gunakan opsi koneksi yang lebih sederhana
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            );
            
            $dsn = $config["driver"].":host=".$config["host"].";dbname=".$config["database"];
            $this->connection = new PDO($dsn, $config["user"], $config["password"], $options);
        } catch (PDOException $ex) {
            // Tampilkan pesan error yang lebih informatif
            echo '<div style="background-color: #f8d7da; color: #721c24; padding: 15px; margin: 10px; border: 1px solid #f5c6cb; border-radius: 5px;">';
            echo '<h3>Database Connection Error:</h3>';
            echo '<p>' . $ex->getMessage() . '</p>';
            echo '<p>Please check that your MySQL server is running and the connection details are correct.</p>';
            echo '</div>';
            exit();
        }
    }

    public function loadComponent() {
        $component = isset($_REQUEST["com"]) ? $_REQUEST["com"] : "Beranda";
        $task = isset($_REQUEST["task"]) ? $_REQUEST["task"] : "index";

        $path = $this->config["server"]."/components/".strtolower($component).".php";
        include_once $path;
        $controllerName = $component."Controller";
        $controller = new $controllerName();

        if (isset($_REQUEST["id"])) {
            $controller->{$task}($_REQUEST["id"]);
        } else {
            $controller->{$task}();
        }
    
        $html = ob_get_contents();
        ob_clean();
        return $html;
    }

    public function find($sql, $params = array()) {
        $result = null;
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute($params);
            $result = $stmt->fetch();
            $stmt->closeCursor();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            exit();
        }
        return $result;
    }

    public function findAll($sql, $params = array()) {
        $result = array();
        try {
            $stmt = $this->connection->prepare($sql);
            $stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute($params);
            while (($obj = $stmt->fetch()) == true) {
                $result[] = $obj;
            }
            $stmt->closeCursor();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            exit();
        }
        return $result;
    }

    public function query($sql, $params) {
        $affectedRows = 0;
        try {
            $stmt = $this->connection->prepare($sql);
            //$stmt->setFetchMode(PDO::FETCH_OBJ);
            $stmt->execute($params);
            $affectedRows = $stmt->rowCount();
            $stmt->closeCursor();
        } catch (PDOException $ex) {
            echo $ex->getMessage();
            exit();
        }
        return $affectedRows;
    }
}
?>



