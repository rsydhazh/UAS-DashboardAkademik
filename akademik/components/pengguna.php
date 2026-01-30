<?php
class PenggunaController {
    private $model, $view;
    public function __construct() {
        $this->model = new PenggunaModel();
        $this->view = new PenggunaView();
    }
    
    public function index() {
        $result = $this->model->getAll();
        $this->view->index($result);
    }
    
    public function add() {
        $this->view->form();
    }
    
    public function edit($id) {
        $result = $this->model->getById($id);
        $this->view->form($result);
    }
    
    public function save() {
        $this->model->save();
    }
    
    public function delete($id) {
        $this->model->delete($id);
    }
    
    public function login() {
        $this->model->login();
    }
    
    public function logout() {
        $this->model->logout();
    }
}

class PenggunaModel {
    public function getAll() {
        global $app;
        
        $sql = "SELECT * FROM pengguna ORDER BY id";
        $result = $app->findAll($sql);
        
        return $result;
    }
    
    public function getById($id) {
        global $app;
        
        $sql = "SELECT * FROM pengguna WHERE id=:id";
        $params = array(
            ":id" => $id
        );
        $result = $app->find($sql, $params);
        
        return $result;
    }
    
    public function save() {
        global $app;
        
        $id = isset($_POST["id"]) ? $_POST["id"] : "";
        $username = isset($_POST["username"]) ? $_POST["username"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";
        $nama = isset($_POST["nama"]) ? $_POST["nama"] : "";
        $level_akses = isset($_POST["level_akses"]) ? $_POST["level_akses"] : "";
        
        if ($id == "") {
            // Insert
            $sql = "INSERT INTO pengguna (username, password, nama, level_akses) 
                    VALUES (:username, :password, :nama, :level_akses)";
            $params = array(
                ":username" => $username,
                ":password" => password_hash($password, PASSWORD_DEFAULT),
                ":nama" => $nama,
                ":level_akses" => $level_akses
            );
        } else {
            // Update
            if ($password != "") {
                $sql = "UPDATE pengguna 
                        SET username=:username, password=:password, nama=:nama, level_akses=:level_akses 
                        WHERE id=:id";
                $params = array(
                    ":id" => $id,
                    ":username" => $username,
                    ":password" => password_hash($password, PASSWORD_DEFAULT),
                    ":nama" => $nama,
                    ":level_akses" => $level_akses
                );
            } else {
                $sql = "UPDATE pengguna 
                        SET username=:username, nama=:nama, level_akses=:level_akses 
                        WHERE id=:id";
                $params = array(
                    ":id" => $id,
                    ":username" => $username,
                    ":nama" => $nama,
                    ":level_akses" => $level_akses
                );
            }
        }
        
        $app->query($sql, $params);
        header("Location:".$app->config["site"]."/admin/Pengguna");
    }
    
    public function delete($id) {
        global $app;
        
        $sql = "DELETE FROM pengguna WHERE id=:id";
        $params = array(
            ":id" => $id
        );
        $app->query($sql, $params);

        header("Location:".$app->config["site"]."/admin/Pengguna");
    }
    
    public function logout() {
        global $app;

        // Hapus semua data session
        $_SESSION = array();
        
        // Hapus cookie session jika ada
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        // Hancurkan session
        session_destroy();
        
        // Redirect ke halaman login
        header("Location:".$app->config["site"]);
        exit;
    }
    
    public function login() {
        global $app;

        $username = isset($_POST["username"]) ? $_POST["username"] : "";
        $password = isset($_POST["password"]) ? $_POST["password"] : "";

        // Validasi input
        if (empty($username) || empty($password)) {
            header("Location:".$app->config["site"]."?error=2");
            exit;
        }

        $sql = "SELECT *
                FROM pengguna
                WHERE username=:username";
        $params = array(
            ":username" => $username
        );
        $result = $app->find($sql, $params);
        
        if ($result) {
            $success = password_verify($password, $result->password);
            if ($success) {
                // Buat session baru
                session_regenerate_id(true);
                
                $_SESSION["pengguna"] = new stdClass();
                $_SESSION["pengguna"]->username = $result->username;
                $_SESSION["pengguna"]->nama = $result->nama;
                $_SESSION["pengguna"]->level_akses = $result->level_akses;
                
                // Redirect ke admin dengan URL lengkap
                header("Location:".$app->config["site"]."/admin");
                exit;
            } else {
                // Redirect ke halaman login dengan pesan error
                header("Location:".$app->config["site"]."?error=1");
                exit;
            }
        } else {
            // Redirect ke halaman login dengan pesan error
            header("Location:".$app->config["site"]."?error=1");
            exit;
        }
    }
}

class PenggunaView {
    public function index($result) {
        global $app;
?>
        <div class="pmd-card pmd-z-depth">      
            <div class="pmd-card-title">
                <h2 class="pmd-card-title-text typo-fill-secondary">Pengguna</h2>
            </div>
            
            <div class="pmd-card-body">
                <div style="margin-bottom: 16px;">
                    <a class="btn btn-md btn-primary" href="<?php echo $app->config["site"]; ?>/admin/Pengguna/add">TAMBAH</a>
                </div>
                <div class="table-responsive">
                    <table id="example" class="table pmd-table table-hover table-striped display responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Username</th>
                                <th>Nama</th>
                                <th>Level Akses</th>
                                <th style="width:100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
        foreach ($result as $v) {
?>
                            <tr>
                                <td><?php echo $v->username; ?></td>
                                <td><?php echo $v->nama; ?></td>
                                <td><?php echo $v->level_akses; ?></td>
                                <td>
                                    <a href="<?php echo $app->config["site"]; ?>/admin/Pengguna/edit/<?php echo $v->id; ?>"><i class="material-icons md-dark pmd-sm">edit</i></a>
                                    <a href="javascript:deleteRecord('<?php echo $v->id; ?>');"><i class="material-icons md-dark pmd-sm">delete</i></a>
                                </td>
                            </tr>
<?php
        }
?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <script>
            function deleteRecord(id) {
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    window.location.href = "<?php echo $app->config["site"]; ?>/admin/Pengguna/delete/" + id;
                }
            }
        </script>
<?php
    }
    
    public function form($data = null) {
        global $app;
        
        $id = isset($data->id) ? $data->id : "";
        $username = isset($data->username) ? $data->username : "";
        $nama = isset($data->nama) ? $data->nama : "";
        $level_akses = isset($data->level_akses) ? $data->level_akses : "";
?>
        <div class="pmd-card-body">
            <form class="form-horizontal" role="form" method="post" action="<?php echo $app->config["site"]; ?>/admin/Pengguna/save">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                
                <div class="form-group pmd-textfield">
                    <label for="username" class="col-sm-2 control-label">Username</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo $username; ?>" required>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="password" class="col-sm-2 control-label">Password</label>
                    <div class="col-sm-10">
                        <input type="password" class="form-control" id="password" name="password" <?php echo ($id == "") ? "required" : ""; ?>>
                        <?php if ($id != "") { echo "<small class='form-text text-muted'>Kosongkan jika tidak ingin mengubah password</small>"; } ?>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="nama" class="col-sm-2 control-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="level_akses" class="col-sm-2 control-label">Level Akses</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="level_akses" name="level_akses" required>
                            <option value="">-- Pilih Level Akses --</option>
                            <option value="Administrator" <?php echo ($level_akses == "Administrator") ? "selected" : ""; ?>>Administrator</option>
                            <option value="Dosen" <?php echo ($level_akses == "Dosen") ? "selected" : ""; ?>>Dosen</option>
                            <option value="Mahasiswa" <?php echo ($level_akses == "Mahasiswa") ? "selected" : ""; ?>>Mahasiswa</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?php echo $app->config["site"]; ?>/admin/Pengguna" class="btn btn-default">Batal</a>
                    </div>
                </div>
            </form>
        </div>
<?php
    }
}
?>

