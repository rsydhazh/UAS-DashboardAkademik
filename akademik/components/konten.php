<?php
class KontenController {
    private $model, $view;
    
    public function __construct() {
        $this->model = new KontenModel();
        $this->view = new KontenView();
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
    
    public function importFromUrl() {
        global $app;
        
        if (isset($_POST['url']) && !empty($_POST['url'])) {
            $url = $_POST['url'];
            
            header("Location:" . $app->config["site"] . "/admin/Konten/add?url=" . urlencode($url));
        } else {
            header("Location:" . $app->config["site"] . "/admin/Konten/add?message=URL tidak boleh kosong");
        }
    }
    
    public function importBatch() {
        global $app;
        
        // Buat folder yang diperlukan
        $uploads_dir = str_replace('\\', '/', $app->config["server"] . "/public/uploads/");
        $sample_dir = str_replace('\\', '/', $app->config["server"] . "/public/images/sample/");
        
        if (!file_exists($uploads_dir)) mkdir($uploads_dir, 0777, true);
        if (!file_exists($sample_dir)) mkdir($sample_dir, 0777, true);
        
        // Daftar berita untuk import batch
        $berita_list = [
            [
                'url' => 'https://www.example.com/berita1',
                'judul' => 'Komisi X DPR Minta Sekolah Swasta 3T Jadi Prioritas Pendidikan Gratis',
                'kategori' => 'Pendidikan',
                'tanggal' => '2023-05-01',
                'isi' => 'Isi berita 1...',
                'foto' => 'berita1.jpg',
                'publikasi' => 1
            ],
            // ... berita lainnya
        ];
        
        // Proses import batch
        $berhasil = 0;
        $gagal = 0;
        
        foreach ($berita_list as $berita) {
            // Cek apakah berita dengan URL yang sama sudah ada
            $existing = $app->find("SELECT id FROM konten WHERE url = :url", [":url" => $berita['url']]);
            
            // Pastikan file gambar ada
            if (!file_exists($uploads_dir . $berita['foto']) && file_exists($sample_dir . $berita['foto'])) {
                copy($sample_dir . $berita['foto'], $uploads_dir . $berita['foto']);
            } else if (!file_exists($sample_dir . $berita['foto'])) {
                $berita['foto'] = 'no-image.png';
            }
            
            // SQL untuk insert atau update
            $sql = $existing 
                ? "UPDATE konten SET judul = :judul, kategori = :kategori, tanggal = :tanggal, isi = :isi, foto = :foto, publikasi = :publikasi WHERE url = :url"
                : "INSERT INTO konten (url, judul, kategori, tanggal, isi, foto, publikasi) VALUES (:url, :judul, :kategori, :tanggal, :isi, :foto, :publikasi)";
            
            try {
                $app->query($sql, [
                    ":url" => $berita['url'],
                    ":judul" => $berita['judul'],
                    ":kategori" => $berita['kategori'],
                    ":tanggal" => $berita['tanggal'],
                    ":isi" => $berita['isi'],
                    ":foto" => $berita['foto'],
                    ":publikasi" => $berita['publikasi']
                ]);
                $berhasil++;
            } catch (Exception $e) {
                $gagal++;
            }
        }
        
        // Setelah import, jalankan fixImages
        $model = new KontenModel();
        $result = $model->fixImages();
        
        header("Location:" . $app->config["site"] . "/admin/Konten?message=Berhasil mengimpor " . $berhasil . " berita, gagal " . $gagal . " berita. Perbaikan gambar: " . $result['fixed'] . " diperbaiki, " . $result['missing'] . " tidak ditemukan");
    }

    public function fixImages() {
        $model = new KontenModel();
        $result = $model->fixImages();
        
        header("Location:" . $GLOBALS['app']->config["site"] . "/admin/Konten?message=Perbaikan gambar: " . $result['fixed'] . " diperbaiki, " . $result['missing'] . " tidak ditemukan");
    }
}

// Tambahkan definisi KontenModel
class KontenModel {
    public function getAll() {
        global $app;
        
        $sql = "SELECT * FROM konten ORDER BY tanggal DESC";
        $result = $app->findAll($sql);
        
        return $result;
    }
    
    public function getById($id) {
        global $app;
        
        $sql = "SELECT * FROM konten WHERE id = :id";
        $params = array(
            ":id" => $id
        );
        $result = $app->find($sql, $params);
        
        return $result;
    }
    
    public function save() {
        global $app;
        
        $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
        $judul = $_POST['judul'];
        $kategori = $_POST['kategori'];
        $tanggal = $_POST['tanggal'];
        $isi = $_POST['isi'];
        $url = $_POST['url'];
        $publikasi = isset($_POST['publikasi']) ? 1 : 0;
        
        // Handle foto upload
        $foto = '';
        if (isset($_FILES['foto']) && $_FILES['foto']['name'] != '') {
            $target_dir = $app->config["server"] . "/public/uploads/";
            $foto = basename($_FILES["foto"]["name"]);
            $target_file = $target_dir . $foto;
            
            // Cek jika file sudah ada, buat nama unik
            if (file_exists($target_file)) {
                $foto = time() . '_' . $foto;
                $target_file = $target_dir . $foto;
            }
            
            move_uploaded_file($_FILES["foto"]["tmp_name"], $target_file);
        } else if (isset($_POST['foto_lama'])) {
            $foto = $_POST['foto_lama'];
        }
        
        if ($id == 0) {
            // Insert baru
            $sql = "INSERT INTO konten (judul, kategori, tanggal, isi, url, foto, publikasi) 
                    VALUES (:judul, :kategori, :tanggal, :isi, :url, :foto, :publikasi)";
        } else {
            // Update
            $sql = "UPDATE konten SET 
                    judul = :judul,
                    kategori = :kategori,
                    tanggal = :tanggal,
                    isi = :isi,
                    url = :url,
                    foto = :foto,
                    publikasi = :publikasi
                    WHERE id = :id";
        }
        
        $params = array(
            ":judul" => $judul,
            ":kategori" => $kategori,
            ":tanggal" => $tanggal,
            ":isi" => $isi,
            ":url" => $url,
            ":foto" => $foto,
            ":publikasi" => $publikasi
        );
        
        if ($id > 0) {
            $params[":id"] = $id;
        }
        
        $app->query($sql, $params);
        
        header("Location:" . $app->config["site"] . "/admin/Konten?message=Data berhasil disimpan");
    }
    
    public function delete($id) {
        global $app;
        
        // Get foto filename before deleting record
        $konten = $this->getById($id);
        
        if ($konten && $konten->foto != "") {
            $target_dir = $app->config["server"] . "/public/uploads/";
            $target_file = $target_dir . $konten->foto;
            
            if (file_exists($target_file)) {
                unlink($target_file);
            }
        }
        
        $sql = "DELETE FROM konten WHERE id = :id";
        $params = array(
            ":id" => $id
        );
        $app->query($sql, $params);
        
        header("Location:" . $app->config["site"] . "/admin/Konten?message=Data berhasil dihapus");
    }

    public function fixImages() {
        global $app;
        
        // Buat folder yang diperlukan
        $uploads_dir = str_replace('\\', '/', $app->config["server"] . "/public/uploads/");
        $sample_dir = str_replace('\\', '/', $app->config["server"] . "/public/images/sample/");
        
        if (!file_exists($uploads_dir)) mkdir($uploads_dir, 0777, true);
        if (!file_exists($sample_dir)) mkdir($sample_dir, 0777, true);
        
        // Pastikan gambar default ada
        $default_image = str_replace('\\', '/', $app->config["server"] . "/public/images/no-image.png");
        if (!file_exists($default_image)) {
            file_put_contents($default_image, file_get_contents("https://via.placeholder.com/800x600.png?text=No+Image"));
        }
        
        // Ambil semua konten
        $sql = "SELECT id, foto FROM konten WHERE foto != ''";
        $result = $app->findAll($sql);
        
        $fixed = 0;
        $missing = 0;
        
        foreach ($result as $item) {
            $foto_path = $uploads_dir . $item->foto;
            
            // Jika file tidak ada di uploads, coba salin dari folder sample
            if (!file_exists($foto_path)) {
                $sample_path = $sample_dir . $item->foto;
                
                if (file_exists($sample_path)) {
                    if (copy($sample_path, $foto_path)) $fixed++;
                } else {
                    // Jika tidak ada di sample, gunakan gambar default
                    $app->query("UPDATE konten SET foto = 'no-image.png' WHERE id = :id", [":id" => $item->id]);
                    $missing++;
                }
            }
        }
        
        return ['fixed' => $fixed, 'missing' => $missing];
    }
}

class KontenView {
    public function index($data) {
        global $app;
?>
    <div class="pmd-card pmd-z-depth">      
        <div class="pmd-card-title">
            <h2 class="pmd-card-title-text typo-fill-secondary">Konten</h2>
        </div>
        
        <?php if (isset($_REQUEST["message"])) { ?>
            <div class="alert alert-info"><?php echo $_REQUEST["message"]; ?></div>
        <?php } ?>
        
        <div class="pmd-card-body">
            <div style="margin-bottom: 16px;">
                <a href="<?php echo $app->config["site"]; ?>/admin/Konten/add" class="btn btn-md btn-primary pmd-ripple-effect">TAMBAH</a>
            </div>
            
            <table id="example-checkbox" class="table pmd-table table-hover table-striped display responsive nowrap" cellspacing="0" width="100%">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Kategori</th>
                        <th>Tanggal</th>
                        <th>Publikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $item) { ?>
                    <tr>
                        <td><?php echo $item->judul; ?></td>
                        <td><?php echo $item->kategori; ?></td>
                        <td><?php echo date("d-m-Y", strtotime($item->tanggal)); ?></td>
                        <td>
                            <?php if ($item->publikasi == 1) { ?>
                                <span class="badge" style="background-color: #4CAF50; color: white;">Dipublikasikan</span>
                            <?php } else { ?>
                                <span class="badge" style="background-color: #F44336; color: white;">Draft</span>
                            <?php } ?>
                        </td>
                        <td>
                            <a href="<?php echo $app->config["site"]; ?>/admin/Konten/edit/<?php echo $item->id; ?>" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect" title="Edit"><i class="material-icons pmd-sm">edit</i></a>
                            <a href="<?php echo $app->config["site"]; ?>/admin/Konten/delete/<?php echo $item->id; ?>" class="btn btn-sm pmd-btn-fab pmd-btn-flat pmd-ripple-effect" title="Hapus" onclick="return confirm('Anda yakin ingin menghapus data ini?')"><i class="material-icons pmd-sm">delete</i></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <style>
        /* Menyesuaikan warna tombol tambah */
        .btn-primary {
            background-color: #4285f4;
            color: white;
            border-color: #4285f4;
        }
        .btn-primary:hover {
            background-color: #3367d6;
            border-color: #3367d6;
        }
        
        /* Memperbaiki tampilan tombol aksi */
        .pmd-btn-fab.btn-sm {
            width: 30px;
            height: 30px;
            min-width: 30px;
            line-height: 30px;
            padding: 0;
        }
        .pmd-btn-fab.btn-sm .material-icons.pmd-sm {
            font-size: 18px;
            line-height: 30px;
            color: #757575;
        }
        
        /* Memperbaiki tampilan badge */
        .badge {
            padding: 5px 8px;
            font-weight: 500;
            border-radius: 3px;
            font-size: 12px;
        }
        
        /* Memperbaiki tampilan tabel */
        .table > thead > tr > th {
            color: #333;
            font-weight: 500;
            border-bottom: 1px solid #ddd;
        }
        .table > tbody > tr > td {
            vertical-align: middle;
            padding: 12px 8px;
        }
    </style>
<?php
    }
    
    public function form($data = null) {
        global $app;
        
        // Inisialisasi data jika null
        if (!$data) {
            $data = new stdClass();
            $data->id = 0;
            $data->judul = '';
            $data->kategori = 'Artikel'; // Default kategori
            $data->tanggal = date('Y-m-d');
            $data->isi = '';
            $data->url = '';
            $data->foto = '';
            $data->publikasi = 0;
        }
?>
<div class="pmd-card pmd-z-depth">
    <div class="pmd-card-title">
        <h2 class="pmd-card-title-text typo-fill-secondary"><?php echo ($data->id > 0) ? 'Edit' : 'Tambah'; ?> Konten</h2>
    </div>
    
    <?php if (isset($_REQUEST["message"])) { ?>
        <div class="alert alert-info"><?php echo $_REQUEST["message"]; ?></div>
    <?php } ?>
    
    <div class="pmd-card-body">
        <form action="<?php echo $app->config["site"]; ?>/admin/Konten/save" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $data->id; ?>">
            
            <!-- Judul Berita -->
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="judul" class="control-label">Judul Berita</label>
                <input type="text" class="form-control" id="judul" name="judul" value="<?php echo $data->judul; ?>" required>
            </div>
            
            <!-- Kategori Berita -->
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="kategori" class="control-label">Kategori Berita</label>
                <select class="form-control" id="kategori" name="kategori">
                    <option value="Artikel" <?php echo ($data->kategori == 'Artikel') ? 'selected' : ''; ?>>Artikel</option>
                    <option value="Informasi" <?php echo ($data->kategori == 'Informasi') ? 'selected' : ''; ?>>Informasi</option>
                </select>
            </div>
            
            <!-- Tanggal Berita -->
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="tanggal" class="control-label">Tanggal Berita</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" value="<?php echo $data->tanggal; ?>" required>
            </div>
            
            <!-- Isi Berita -->
            <div class="form-group pmd-textfield">
                <label for="isi" class="control-label">Isi Berita</label>
                <textarea class="form-control" id="isi" name="isi" rows="10"><?php echo $data->isi; ?></textarea>
            </div>
            
            <!-- Foto/Gambar -->
            <div class="form-group">
                <label for="foto" class="control-label">Foto</label>
                <?php if ($data->foto) { ?>
                    <div class="mb-2">
                        <img src="<?php echo $app->config["site"]; ?>/public/uploads/<?php echo $data->foto; ?>" style="max-width: 200px; max-height: 150px; margin-bottom: 10px; border: 1px solid #ddd; padding: 3px; border-radius: 4px;">
                        <input type="hidden" name="foto_lama" value="<?php echo $data->foto; ?>">
                    </div>
                <?php } ?>
                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*">
                <small class="form-text text-muted">Pilih satu gambar untuk berita ini. Format yang didukung: JPG, PNG, GIF.</small>
            </div>
            
            <!-- URL Sumber (Opsional) -->
            <div class="form-group pmd-textfield pmd-textfield-floating-label">
                <label for="url" class="control-label">URL Sumber (Opsional)</label>
                <input type="url" class="form-control" id="url" name="url" value="<?php echo $data->url; ?>">
            </div>
            
            <!-- Checkbox Publikasi -->
            <div class="form-group">
                <div class="checkbox pmd-default-theme">
                    <label class="pmd-checkbox pmd-checkbox-ripple-effect">
                        <input type="checkbox" id="publikasi" name="publikasi" value="1" <?php echo ($data->publikasi == 1) ? 'checked' : ''; ?>>
                        <span class="pmd-checkbox-label">&nbsp;</span>
                        <span style="font-weight: bold; font-size: 16px;">Publikasikan</span>
                    </label>
                    <small class="form-text text-muted" style="margin-top: 5px; margin-left: 25px;">Centang untuk mempublikasikan berita ini ke halaman utama.</small>
                </div>
            </div>
            
            <!-- Tombol Aksi -->
            <div class="form-group" style="margin-top: 30px;">
                <button type="submit" class="btn pmd-ripple-effect btn-primary">Simpan</button>
                <a href="<?php echo $app->config["site"]; ?>/admin/Konten" class="btn pmd-ripple-effect btn-default">Batal</a>
            </div>
        </form>
    </div>
</div>

<script>
    // Inisialisasi editor WYSIWYG jika tersedia
    if (typeof CKEDITOR !== 'undefined') {
        CKEDITOR.replace('isi', {
            height: 300,
            toolbarGroups: [
                { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
                { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
                { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
                { name: 'forms', groups: [ 'forms' ] },
                '/',
                { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
                { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
                { name: 'links', groups: [ 'links' ] },
                { name: 'insert', groups: [ 'insert' ] },
                '/',
                { name: 'styles', groups: [ 'styles' ] },
                { name: 'colors', groups: [ 'colors' ] },
                { name: 'tools', groups: [ 'tools' ] },
                { name: 'others', groups: [ 'others' ] }
            ]
        });
    }
</script>

<style>
    /* Memperbaiki tampilan form */
    .form-group {
        margin-bottom: 25px;
    }
    .control-label {
        font-weight: 500;
        font-size: 16px;
        color: #333;
        margin-bottom: 8px;
        display: block;
    }
    .form-control {
        border-radius: 4px;
        border: 1px solid #ddd;
        box-shadow: none;
        padding: 8px 12px;
        font-size: 15px;
    }
    .form-control:focus {
        border-color: #1453a1;
        box-shadow: 0 0 0 0.2rem rgba(20, 83, 161, 0.25);
    }
    
    /* Memperbaiki tampilan checkbox */
    .pmd-checkbox {
        display: inline-block;
        position: relative;
        margin-bottom: 15px;
    }
    .pmd-checkbox [type="checkbox"] {
        position: absolute;
        opacity: 0;
        margin: 0;
    }
    .pmd-checkbox [type="checkbox"] + .pmd-checkbox-label {
        position: relative;
        padding-left: 30px;
        cursor: pointer;
        display: inline-block;
    }
    .pmd-checkbox [type="checkbox"] + .pmd-checkbox-label:before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border: 2px solid #1453a1;
        background: #fff;
        border-radius: 3px;
    }
    .pmd-checkbox [type="checkbox"]:checked + .pmd-checkbox-label:before {
        background: #1453a1;
    }
    .pmd-checkbox [type="checkbox"]:checked + .pmd-checkbox-label:after {
        content: '';
        position: absolute;
        left: 7px;
        top: 3px;
        width: 6px;
        height: 12px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
    
    /* Memperbaiki tampilan tombol */
    .btn-primary {
        background-color: #1453a1;
        border-color: #1453a1;
        color: white;
        font-weight: 500;
        padding: 8px 20px;
        font-size: 16px;
    }
    .btn-primary:hover {
        background-color: #0d3d7a;
        border-color: #0d3d7a;
    }
    .btn-default {
        background-color: #f5f5f5;
        border-color: #ddd;
        color: #333;
        font-weight: 500;
        padding: 8px 20px;
        font-size: 16px;
    }
    .btn-default:hover {
        background-color: #e0e0e0;
        border-color: #ccc;
    }
</style>
<?php
    }
}
?>










