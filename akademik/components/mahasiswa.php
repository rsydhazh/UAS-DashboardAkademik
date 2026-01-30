<?php
class MahasiswaController {
    private $model, $view;
    
    public function __construct() {
        $this->model = new MahasiswaModel();
        $this->view = new MahasiswaView();
    }
    
    public function index() {
        $result = $this->model->getAll();
        $this->view->index($result);
    }
    
    public function add() {
        $this->view->form();
    }
    
    public function edit($nim) {
        $result = $this->model->getByNim($nim);
        $this->view->form($result);
    }
    
    public function save() {
        $this->model->save();
    }
    
    public function delete($nim) {
        $this->model->delete($nim);
    }
}

class MahasiswaModel {
    public function getAll() {
        global $app;
        
        $sql = "SELECT m.*, j.nama as jurusan_nama 
                FROM mahasiswa m
                JOIN jurusan j ON m.jurusan_id = j.id
                ORDER BY m.nim";
        $result = $app->findAll($sql);
        
        return $result;
    }
    
    public function getByNim($nim) {
        global $app;
        
        $sql = "SELECT * FROM mahasiswa WHERE nim=:nim";
        $params = array(
            ":nim" => $nim
        );
        $result = $app->find($sql, $params);
        
        return $result;
    }
    
    public function save() {
        global $app;
        
        $nim = isset($_POST["nim"]) ? $_POST["nim"] : "";
        $nama = isset($_POST["nama"]) ? $_POST["nama"] : "";
        $tempat_lahir = isset($_POST["tempat_lahir"]) ? $_POST["tempat_lahir"] : "";
        $tanggal_lahir = isset($_POST["tanggal_lahir"]) ? $_POST["tanggal_lahir"] : "";
        $jenis_kelamin = isset($_POST["jenis_kelamin"]) ? $_POST["jenis_kelamin"] : "";
        $jurusan_id = isset($_POST["jurusan_id"]) ? $_POST["jurusan_id"] : "";
        $tahun_masuk = isset($_POST["tahun_masuk"]) ? $_POST["tahun_masuk"] : "";
        
        // Handle file upload
        $foto = "";
        $old_foto = isset($_POST["old_foto"]) ? $_POST["old_foto"] : "";
        
        if (isset($_FILES["foto"]) && $_FILES["foto"]["name"] != "") {
            $target_dir = $app->config["server"] . "/public/uploads/";
            $foto = time() . "_" . basename($_FILES["foto"]["name"]);
            $target_file = $target_dir . $foto;
            
            // Delete old file if exists
            if ($old_foto != "" && file_exists($target_dir . $old_foto)) {
                unlink($target_dir . $old_foto);
            }
            
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        } else {
            $foto = $old_foto;
        }
        
        $is_new = !$this->getByNim($nim);
        
        if ($is_new) {
            // Insert
            $sql = "INSERT INTO mahasiswa (nim, nama, tempat_lahir, tanggal_lahir, jenis_kelamin, jurusan_id, tahun_masuk, foto) 
                    VALUES (:nim, :nama, :tempat_lahir, :tanggal_lahir, :jenis_kelamin, :jurusan_id, :tahun_masuk, :foto)";
        } else {
            // Update
            $sql = "UPDATE mahasiswa 
                    SET nama=:nama, tempat_lahir=:tempat_lahir, tanggal_lahir=:tanggal_lahir, 
                        jenis_kelamin=:jenis_kelamin, jurusan_id=:jurusan_id, tahun_masuk=:tahun_masuk, foto=:foto 
                    WHERE nim=:nim";
        }
        
        $params = array(
            ":nim" => $nim,
            ":nama" => $nama,
            ":tempat_lahir" => $tempat_lahir,
            ":tanggal_lahir" => $tanggal_lahir,
            ":jenis_kelamin" => $jenis_kelamin,
            ":jurusan_id" => $jurusan_id,
            ":tahun_masuk" => $tahun_masuk,
            ":foto" => $foto
        );
        
        $app->query($sql, $params);
        header("Location:" . $app->config["site"] . "/admin/Mahasiswa");
    }
    
    public function delete($nim) {
        global $app;
        
        // Get foto filename before deleting record
        $mahasiswa = $this->getByNim($nim);
        
        if ($mahasiswa && $mahasiswa->foto != "") {
            $target_dir = $app->config["server"] . "/public/uploads/";
            $target_file = $target_dir . $mahasiswa->foto;
            
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
        
        $sql = "DELETE FROM mahasiswa WHERE nim=:nim";
        $params = array(
            ":nim" => $nim
        );
        $app->query($sql, $params);
        
        header("Location:" . $app->config["site"] . "/admin/Mahasiswa");
    }
    
    public function getAllJurusan() {
        global $app;
        
        $sql = "SELECT * FROM jurusan ORDER BY nama";
        $result = $app->findAll($sql);
        
        return $result;
    }
}

class MahasiswaView {
    public function index($result) {
        global $app;
?>
        <div class="pmd-card pmd-z-depth">      
            <div class="pmd-card-title">
                <h2 class="pmd-card-title-text typo-fill-secondary">Mahasiswa</h2>
            </div>
            
            <div class="pmd-card-body">
                <div style="margin-bottom: 16px;">
                    <a class="btn btn-md btn-primary" href="<?php echo $app->config["site"]; ?>/admin/Mahasiswa/add">TAMBAH</a>
                </div>
                <div class="table-responsive">
                    <table id="example" class="table pmd-table table-hover table-striped display responsive" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Foto</th>
                                <th>NIM</th>
                                <th>Nama</th>
                                <th>Jenis Kelamin</th>
                                <th>Jurusan</th>
                                <th>Tahun Masuk</th>
                                <th style="width:100px;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
<?php
        foreach ($result as $v) {
            $foto_url = ($v->foto != "") 
                ? $app->config["site"] . "/public/uploads/" . $v->foto 
                : $app->config["site"] . "/public/images/user-icon.png";
?>
                        <tr>
                            <td><img src="<?php echo $foto_url; ?>" alt="<?php echo $v->nama; ?>" style="width:50px;height:50px;object-fit:cover;"></td>
                            <td><?php echo $v->nim; ?></td>
                            <td><?php echo $v->nama; ?></td>
                            <td><?php echo $v->jenis_kelamin; ?></td>
                            <td><?php echo $v->jurusan_nama; ?></td>
                            <td><?php echo $v->tahun_masuk; ?></td>
                            <td>
                                <a href="<?php echo $app->config["site"]; ?>/admin/Mahasiswa/edit/<?php echo $v->nim; ?>"><i class="material-icons md-dark pmd-sm">edit</i></a>
                                <a href="javascript:deleteRecord('<?php echo $v->nim; ?>');"><i class="material-icons md-dark pmd-sm">delete</i></a>
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
            function deleteRecord(nim) {
                if (confirm("Apakah Anda yakin ingin menghapus data ini?")) {
                    window.location.href = "<?php echo $app->config["site"]; ?>/admin/Mahasiswa/delete/" + nim;
                }
            }
        </script>
<?php
    }
    
    public function form($data = null) {
        global $app;
        
        $model = new MahasiswaModel();
        $jurusan_list = $model->getAllJurusan();
        
        $nim = isset($data->nim) ? $data->nim : "";
        $nama = isset($data->nama) ? $data->nama : "";
        $tempat_lahir = isset($data->tempat_lahir) ? $data->tempat_lahir : "";
        $tanggal_lahir = isset($data->tanggal_lahir) ? $data->tanggal_lahir : "";
        $jenis_kelamin = isset($data->jenis_kelamin) ? $data->jenis_kelamin : "";
        $jurusan_id = isset($data->jurusan_id) ? $data->jurusan_id : "";
        $tahun_masuk = isset($data->tahun_masuk) ? $data->tahun_masuk : "";
        $foto = isset($data->foto) ? $data->foto : "";
        
        $foto_url = ($foto != "") 
            ? $app->config["site"] . "/public/uploads/" . $foto 
            : $app->config["site"] . "/public/images/user-icon.png";
        
        $is_edit = ($nim != "");
?>
        <div class="pmd-card-body">
            <form class="form-horizontal" role="form" method="post" action="<?php echo $app->config["site"]; ?>/admin/Mahasiswa/save" enctype="multipart/form-data">
                <div class="form-group pmd-textfield">
                    <label for="nim" class="col-sm-2 control-label">NIM</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nim" name="nim" value="<?php echo $nim; ?>" <?php echo $is_edit ? "readonly" : ""; ?> required>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="nama" class="col-sm-2 control-label">Nama</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="nama" name="nama" value="<?php echo $nama; ?>" required>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="tempat_lahir" class="col-sm-2 control-label">Tempat Lahir</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir" value="<?php echo $tempat_lahir; ?>" required>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="tanggal_lahir" class="col-sm-2 control-label">Tanggal Lahir</label>
                    <div class="col-sm-10">
                        <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir" value="<?php echo $tanggal_lahir; ?>" required>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="jenis_kelamin" class="col-sm-2 control-label">Jenis Kelamin</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="jenis_kelamin" name="jenis_kelamin" required>
                            <option value="">-- Pilih Jenis Kelamin --</option>
                            <option value="Laki-laki" <?php echo ($jenis_kelamin == "Laki-laki") ? "selected" : ""; ?>>Laki-laki</option>
                            <option value="Perempuan" <?php echo ($jenis_kelamin == "Perempuan") ? "selected" : ""; ?>>Perempuan</option>
                        </select>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="jurusan_id" class="col-sm-2 control-label">Jurusan</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="jurusan_id" name="jurusan_id" required>
                            <option value="">-- Pilih Jurusan --</option>
                            <?php foreach ($jurusan_list as $j) { ?>
                                <option value="<?php echo $j->id; ?>" <?php echo ($jurusan_id == $j->id) ? "selected" : ""; ?>><?php echo $j->nama; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="tahun_masuk" class="col-sm-2 control-label">Tahun Masuk</label>
                    <div class="col-sm-10">
                        <select class="form-control" id="tahun_masuk" name="tahun_masuk" required>
                            <option value="">-- Pilih Tahun Masuk --</option>
                            <?php 
                            $current_year = date('Y');
                            for ($year = $current_year; $year >= $current_year - 10; $year--) { 
                            ?>
                                <option value="<?php echo $year; ?>" <?php echo ($tahun_masuk == $year) ? "selected" : ""; ?>><?php echo $year; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
                
                <div class="form-group pmd-textfield">
                    <label for="foto" class="col-sm-2 control-label">Foto</label>
                    <div class="col-sm-10">
                        <?php if ($foto != "") { ?>
                            <div class="mb-3">
                                <img src="<?php echo $foto_url; ?>" alt="Foto Mahasiswa" style="max-width:200px;max-height:200px;">
                            </div>
                        <?php } ?>
                        <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                        <input type="hidden" name="old_foto" value="<?php echo $foto; ?>">
                        <small class="form-text text-muted">Format: JPG, PNG, GIF. Ukuran maksimal: 2MB.</small>
                    </div>
                </div>
                
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="<?php echo $app->config["site"]; ?>/admin/Mahasiswa" class="btn btn-default">Batal</a>
                    </div>
                </div>
            </form>
        </div>
<?php
    }
}
?>
