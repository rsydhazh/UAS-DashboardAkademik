<?php
class JurusanController {
    private $model, $view;
    
    public function __construct() {
        $this->model = new JurusanModel();
        $this->view = new JurusanView();
    }
    
    public function index() {
        $this->view->index($this->model->findAll());
    }
    
    public function add() {
        $this->view->edit($this->model->find(0));
    }
    
    public function edit($id) {
        $this->view->edit($this->model->find($id));
    }
    
    public function save() {
        $this->model->save();
    }

    public function delete($id) {
        $this->model->delete($id);
    }

    public function search($id) {
        $this->model->search($id);
    }
}
class JurusanModel {
    public function findAll() {
        global $app;

        $sql = "SELECT jurusan.*, fakultas.nama AS fakultas_nama
                FROM jurusan, fakultas
                WHERE jurusan.fakultas_id=fakultas.id";
        $result = $app->findAll($sql);

        return $result;
    }

    public function find($id) {
        global $app;

        $sql = "SELECT *
                FROM jurusan
                WHERE id=:id";
        $params = array(
            ":id" => $id
        );
        $result = $app->find($sql, $params);
        if (!$result) {
            $result = new stdClass();
            $result->id = 0;
            $result->fakultas_id = 0;
            $result->nama = "";
        }

        return $result;
    }

    public function search($id) {
        global $app, $config;

        $ch = curl_init(); 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        //curl_setopt($ch, CURLOPT_URL, $config["site"]."/admin/Api/getJurusan/".$id); 
        curl_setopt($ch, CURLOPT_URL, $config["site"]."/Api/getJurusan/".$id); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json', 
            'Authorization: Bearer '.$config["token"]
        ));
        //curl_setopt($ch, CURLOPT_POST, 1);
        //curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($postParameters));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($ch);
        //var_dump($output);
        if (curl_errno($ch)) {
            $errNo = curl_errno($ch);
            $errMessage = curl_strerror($errNo);
            echo $errNo." - ".$errMessage;
            return;
        }
        curl_close($ch); 
	
        $json = json_decode($output);
        echo "<pre>".print_r($json, true)."</pre>";
        echo $json->data->nama;
    }

    public function save() {
        global $app;

        $id = intval($_REQUEST["id"]);
        $fakultas_id = intval($_REQUEST["fakultas_id"]);
        $nama = $_REQUEST["nama"];

        if (empty($nama)) {
            header("Location:".$app->config["site"]."/admin/Jurusan/edit/".$id."?message=Nama belum diisi");
            return;
        }

        if ($id == 0) {
            $sql = "INSERT INTO jurusan (fakultas_id, nama)
                    VALUES (:fakultas_id, :nama)";
            $params = array(
                ":fakultas_id" => $fakultas_id,
                ":nama" => $nama
            );
            $app->query($sql, $params);
        } else {
            $sql = "UPDATE jurusan
                    SET fakultas_id=:fakultas_id, nama=:nama
                    WHERE id=:id";
            $params = array(
                ":id" => $id,
                ":fakultas_id" => $fakultas_id,
                ":nama" => $nama
            );
            $app->query($sql, $params);
        }

        header("Location:".$app->config["site"]."/admin/Jurusan?message=Data berhasil disimpan");
    }

    public function delete($id) {
        global $app;

        $sql = "DELETE FROM jurusan
                WHERE id=:id";
        $params = array(
            ":id" => $id
        );
        $app->query($sql, $params);

        header("Location:".$app->config["site"]."/admin/Jurusan?message=Data berhasil dihapus");
    }
}
class JurusanView {
    public function edit($result) {
        global $app;
?>
    <form action="<?php echo $app->config["site"]; ?>/admin/Jurusan/save" method="post">
        <input type="hidden" name="id" value="<?php echo $result->id; ?>">
        <div class="pmd-card pmd-z-depth">      
            <div class="pmd-card-title">
                <h2 class="pmd-card-title-text typo-fill-secondary">Jurusan</h2>
            </div>
<?php
        if (isset($_REQUEST["message"])) {
?>
            <div class="alert alert-info"><?php echo $_REQUEST["message"]; ?></div>
<?php
        }
?>
            <div class="pmd-card-body">
                <div class="group-fields clearfix row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group form-group-sm">
                            <label for="fakultas_id" class="control-label text-danger">
                                Fakultas*
                            </label>
<?php
        $sql = "SELECT *
                FROM fakultas
                ORDER BY nama";
        $resultFakultas = $app->findAll($sql);
?>
                            <select class="form-control" id="fakultas_id" name="fakultas_id">
<?php
        foreach ($resultFakultas as $v) {
            if ($v->id == $result->fakultas_id) {
                $selected = "selected";
            } else {
                $selected = "";
            }
?>
            <option value="<?php echo $v->id; ?>" <?php echo $selected; ?>>
                <?php echo $v->nama; ?>
            </option>
<?php
        }
?>
                            </select>
                            <span class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                </div>
                <div class="group-fields clearfix row">
                    <div class="col-lg-6 col-md-6 col-sm-12 col-xs-12">
                        <div class="form-group form-group-sm">
                            <label for="nama" class="control-label text-danger">
                                Nama*
                            </label>
                            <input class="form-control" name="nama" maxlength="40" value="<?php echo $result->nama; ?>" required autofocus>
                            <span class="pmd-textfield-focused"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="pmd-card-actions">
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a class="btn btn-default" href="<?php echo $app->config["site"]; ?>/admin/Jurusan/index">Batal</a>
            </div>
        </div>
    </form>
<?php
    }

    public function index($result) {
        global $app;
?>
    <div class="pmd-card pmd-z-depth">      
        <div class="pmd-card-title">
            <h2 class="pmd-card-title-text typo-fill-secondary">Jurusan</h2>
        </div>
<?php
        if (isset($_REQUEST["message"])) {
?>
        <div class="alert alert-info"><?php echo $_REQUEST["message"]; ?></div>
<?php
        }
?>
        <div class="pmd-card-body">
            <div>
                <a class="btn btn-md btn-primary" href="<?php echo $app->config["site"]; ?>/admin/Jurusan/add">Tambah</a>
            </div>
            <div class="table-responsive">
                <table id="example" class="table pmd-table table-hover table-striped display responsive" cellspacing="0" width="100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Fakultas</th>
                            <th style="width:100px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
<?php
        foreach ($result as $v) {
?>
                        <tr>
                            <td><?php echo $v->id; ?></td>
                            <td><?php echo $v->nama; ?></td>
                            <td><?php echo $v->fakultas_nama; ?></td>
                            <td>
                                <a href="<?php echo $app->config["site"]; ?>/admin/Jurusan/edit/<?php echo $v->id; ?>"><i class="material-icons md-dark pmd-sm">edit</i></a>
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
                window.location.href = "<?php echo $app->config["site"]; ?>/admin/Jurusan/delete/" + id;
            }
        }
    </script>
<?php
    }
}
?>

