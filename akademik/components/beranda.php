
<?php
class BerandaController {
    private $model, $view;
    public function __construct() {
        $this->model = new BerandaModel();
        $this->view = new BerandaView();
    }
    public function index() {
        $berita = $this->model->getPublishedKonten();
        $this->view->index($berita);
    }
    
    public function detail($id) {
        global $app;
        
        $berita = $this->model->getKontenById($id);
        if ($berita && $berita->publikasi == 1) {
            $this->view->detail($berita);
        } else {
            // Tambahkan pesan error jika berita tidak ditemukan
            $_SESSION['error_message'] = "Berita tidak ditemukan atau belum dipublikasikan";
            header("Location:" . $app->config["site"]);
            exit;
        }
    }
    public function dashboard() {
        $berita = $this->model->getPublishedKonten();
        $this->view->index($berita);
    }
}
class BerandaModel {
    public function getPublishedKonten() {
        global $app;
        
        $sql = "SELECT * FROM konten WHERE publikasi = 1 ORDER BY tanggal DESC LIMIT 10";
        $result = $app->findAll($sql);
        
        return $result;
    }
    
    public function getKontenById($id) {
        global $app;
        
        $sql = "SELECT * FROM konten WHERE id = :id";
        $params = array(
            ":id" => $id
        );
        $result = $app->find($sql, $params);
        
        return $result;
    }
}
class BerandaView {
    public function index($berita) {
        global $app;
        
        // Pastikan folder uploads ada
        $uploads_dir = str_replace('\\', '/', $app->config["server"] . "/public/uploads/");
        if (!file_exists($uploads_dir)) mkdir($uploads_dir, 0777, true);
?>
<div class="pmd-card pmd-z-depth">      
    <div class="pmd-card-title">
        <h2 class="pmd-card-title-text typo-fill-secondary">Dashboard</h2>
    </div>
    <div class="pmd-card-body">
        <div class="pmd-scrollbar mCustomScrollbar" style="height: 600px; overflow-y: auto;">
            <?php
            foreach ($berita as $item) {
                // Cek dan perbaiki gambar
                $foto_path = $uploads_dir . $item->foto;
                $foto_url = $app->config["site"] . "/public/uploads/" . $item->foto;
                
                if (!file_exists($foto_path) && !empty($item->foto)) {
                    $sample_path = str_replace('\\', '/', $app->config["server"] . "/public/images/sample/" . $item->foto);
                    if (file_exists($sample_path)) copy($sample_path, $foto_path);
                    else $foto_url = $app->config["site"] . "/public/images/no-image.png";
                }
            ?>
            <div class="pmd-card pmd-z-depth" style="margin-bottom: 20px;">
                <div class="row">
                    <div class="col-md-4">
                        <div class="pmd-card-media" style="height: 200px; overflow: hidden;">
                            <img src="<?php echo $foto_url; ?>" class="img-responsive" alt="<?php echo $item->judul; ?>" style="object-fit: cover; width: 100%; height: 100%;">
                        </div>
                    </div>
                    <div class="col-md-8">
                        <div class="pmd-card-title">
                            <h2 class="pmd-card-title-text">
                                <a href="<?php echo $app->config["site"]; ?>/index.php?com=Beranda&task=detail&id=<?php echo $item->id; ?>"><?php echo $item->judul; ?></a>
                            </h2>
                        </div>
                        <div class="pmd-card-body">
                            <?php echo substr(strip_tags($item->isi), 0, 250); ?>...
                        </div>
                        <div class="pmd-card-actions">
                            <span class="pmd-card-subtitle-text">
                                <strong>Kategori:</strong> <?php echo $item->kategori; ?> | 
                                <strong>Tanggal:</strong> <?php echo date("d-m-Y", strtotime($item->tanggal)); ?>
                            </span>
                            <a href="<?php echo $app->config["site"]; ?>/index.php?com=Beranda&task=detail&id=<?php echo $item->id; ?>" class="btn btn-sm pmd-btn-raised pmd-ripple-effect btn-primary">Baca Selengkapnya</a>
                        </div>
                    </div>
                </div>
            </div>
            <?php } ?>
        </div>
    </div>
</div>

<script>
    (function($){
        $(window).load(function(){
            $(".pmd-scrollbar").mCustomScrollbar({
                theme:"minimal-dark",
                scrollButtons:{enable:true}
            });
        });
    })(jQuery);
</script>
<?php
    }
    
    public function detail($berita) {
        global $app;
        
        // Cek dan perbaiki gambar
        $uploads_dir = str_replace('\\', '/', $app->config["server"] . "/public/uploads/");
        $foto_path = $uploads_dir . $berita->foto;
        $foto_url = $app->config["site"] . "/public/uploads/" . $berita->foto;
        
        if (!file_exists($foto_path) && !empty($berita->foto)) {
            $sample_path = str_replace('\\', '/', $app->config["server"] . "/public/images/sample/" . $berita->foto);
            if (file_exists($sample_path)) copy($sample_path, $foto_path);
            else $foto_url = $app->config["site"] . "/public/images/no-image.png";
        }
?>
<div class="pmd-card pmd-z-depth">      
    <div class="pmd-card-title" style="padding-top: 20px;">
        <h1 class="pmd-card-title-text" style="font-size: 30px; line-height: 1.3; margin-bottom: 16px; font-weight: 700; color: #000000;"><?php echo $berita->judul; ?></h1>
        <div class="pmd-card-subtitle-text" style="background-color: #f5f5f5; padding: 8px 12px; border-radius: 4px; display: inline-block; margin-bottom: 16px;">
            <strong>Kategori:</strong> <span class="badge" style="background-color: #1453a1; color: white; padding: 4px 8px;"><?php echo $berita->kategori; ?></span> &nbsp;|&nbsp; 
            <strong>Tanggal:</strong> <span style="color: #666;"><?php echo date("d-m-Y", strtotime($berita->tanggal)); ?></span>
        </div>
    </div>
    
    <div class="pmd-card-media" style="text-align: center; margin-bottom: 24px; padding: 0 16px;">
        <img src="<?php echo $foto_url; ?>" class="img-responsive" alt="<?php echo $berita->judul; ?>" style="max-width:100%; max-height: 500px; margin:0 auto; display:block; border-radius: 4px; box-shadow: 0 2px 10px rgba(0,0,0,0.1);">
    </div>
    
    <div class="pmd-card-body" style="padding: 0 24px 16px; font-size: 16px; line-height: 1.6;">
        <div style="background-color: white; padding: 20px; border-radius: 4px; box-shadow: 0 1px 3px rgba(0,0,0,0.1);">
            <?php echo $berita->isi; ?>
            
            <?php if ($berita->url) { ?>
            <div style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #eee;">
                <strong>Sumber:</strong> <a href="<?php echo $berita->url; ?>" target="_blank" style="color: #1453a1;"><?php echo $berita->url; ?></a>
            </div>
            <?php } ?>
        </div>
    </div>
    
    <!-- Tombol kembali di bagian bawah -->
    <div class="pmd-card-actions" style="padding: 20px 16px; text-align: center; margin-top: 16px;">
        <a href="<?php echo $app->config["site"]; ?>/admin" class="btn pmd-btn-raised pmd-ripple-effect btn-primary" style="font-size: 16px; padding: 8px 24px; min-width: 150px; text-transform: uppercase; font-weight: 500; letter-spacing: 0.5px; box-shadow: 0 2px 5px rgba(0,0,0,0.2); background-color: #1453a1; border-radius: 4px; color: #ffffff;">
            KEMBALI
        </a>
    </div>
</div>

<style>
    /* Tambahan CSS untuk memperbaiki tampilan */
    .pmd-card {
        margin-bottom: 30px;
    }
    .pmd-card-title-text {
        color: #000000; /* Judul hitam */
        font-weight: 700; /* Bold */
    }
    .pmd-card-body p {
        margin-bottom: 16px;
    }
    .btn-primary {
        background-color: #1453a1; /* Warna biru */
        color: #ffffff; /* Warna teks putih */
    }
    .btn-primary:hover {
        background-color: #0d3d7a; /* Biru lebih gelap saat hover */
        box-shadow: 0 4px 8px rgba(0,0,0,0.3);
        transition: all 0.3s ease;
    }
    /* Responsif untuk layar kecil */
    @media (max-width: 768px) {
        .pmd-card-title-text {
            font-size: 24px;
        }
        .pmd-card-body {
            padding: 0 16px 16px;
        }
    }
</style>
<?php
    }
}
?>





















