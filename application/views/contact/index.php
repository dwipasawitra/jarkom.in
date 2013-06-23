<div id="add-dialog" class="modal-dialog">
    <h1>Tambah Kontak Baru</h1>
    <div id="error-add-dialog" class="error" />
    </div>
    <p>Silahkan isi nama kontak, nomor handphone dan akun twitter kontak.</p>
    <form method="post" action="<?php echo site_url('kontak/tambah_kontak'); ?>" id="add-form">
    <table>
        <tr>
            <td>Nama Kontak</td>
            <td><input type="text" name="nama_kontak" /></td>
        </tr>
        <tr>
            <td>Nomor Handphone</td>
            <td><input type="text" name="no_handphone" /></td>
            
        </tr>
        <tr>
            <td>Akun Twitter</td>
            <td><input type="text" name="akun_twitter" /></td>
            
        </tr>
        <tr>
            <td>Grup yang diikuti</td>
            <td><input type="text" name="data_grup" class="grup_kontak_input" /></td>
        </tr>
    </table>
        <input type="submit" value="Tambah Kontak Baru" />
    </form>
    
    
</div>
<div id="edit-dialog" class="modal-dialog">
    <h1>Sunting Kontak</h1>
    <div id="error-edit-dialog" class="error" />
    </div>
    <form method="post" action="<?php echo site_url('kontak/tambah_kontak'); ?>" id="edit-form">
    <input type="hidden" name="id_kontak" />
    <table>
        <tr>
            <td>Nama Kontak</td>
            <td><input type="text" name="nama_kontak" ></td>
        </tr>
        <tr>
            <td>Nomor Handphone</td>
            <td><input type="text" name="no_handphone" /></td>
            
        </tr>
        <tr>
            <td>Akun Twitter</td>
            <td><input type="text" name="akun_twitter" /></td>
            
        </tr>
       
    </table>
        <input type="submit" value="Ubah Data Kontak" />
    </form>
    
    
</div>
<table>
    <span class="link-action" onclick="buka_tambah_kontak()">Tambah Kontak Baru</span> | Kelola Grup | Tampilkan untuk grup :<br/>
    <?php foreach($kontak as $row) { ?>
        <tr style="border: 1px solid black;">
            <td style="width: 50%; padding: 2%;">
                <strong><?php echo $row['nama_kontak']; ?></strong>
                <br/>
                <?php echo $row['no_handphone']; ?>
                <br/>
                <span class="link-action" onclick="buka_edit_kontak(<?php echo $row['id_kontak']; ?>)">Sunting Kontak</span> | 
                <span class="link-action" onclick="hapus_kontak(<?php echo $row['id_kontak']; ?>)">Hapus Kontak</span>
            </td>
            <td style="width: 50%;">
            Grup : <br/>
            <input type="text" class="grup_kontak" data-id-kontak="<?php echo $row['id_kontak']; ?>"/>
            </td>
        </tr>
        
    <?php } ?>
</table>
<script>
    function buka_tambah_kontak() {
           $("#add-dialog").modal();
           
           $("#error-add-dialog").hide();
    }
    
    function buka_edit_kontak(id_kontak)
    {
        $("#edit-dialog").modal();
        $("#error-edit-dialog").hide();
        
        // Ambil data untuk disunting
        $.post("<?php echo site_url('kontak/ambil_data_kontak_json'); ?>", "id_kontak=" + id_kontak, function(data){
            $("#edit-form input[name=id_kontak]").val(data.id_kontak);
            $("#edit-form input[name=nama_kontak]").val(data.nama_kontak);
            $("#edit-form input[name=no_handphone]").val(data.no_handphone);
            $("#edit-form input[name=akun_twitter]").val('');
            $("#edit-form input[name=data_grup]").val('');
        }, "json");
        
    }
    
    function hapus_kontak(id_kontak)
    {
        var confirm = window.confirm("Yakin akan menghapus kontak ini?")
        if(confirm == true) {
            $.post("<?php echo site_url('kontak/hapus_kontak'); ?>", "id_kontak=" + id_kontak, function(data) {
                window.location = "<?php echo site_url('kontak/'); ?>";
            } );
        }
    }
    
    $(document).ready(function(){
       $("#add-dialog").hide();
       $("#edit-dialog").hide();
       $(".grup_kontak_input").tokenInput("<?php echo site_url('kontak/list_grup_json'); ?>", { preventDuplicates: true });
       $("#add-form").submit(function(){
            $.post("<?php echo site_url('kontak/tambah_kontak'); ?>", $(this).serialize(), function(data){
                if(data.hasil === false) {
                    $("#error-add-dialog").html(data.pesan);
                    $("#error-add-dialog").show();
                }
                else {
                    // Refresh ke halaman yang sama
                    window.location = "<?php echo site_url('kontak/'); ?>";
                }
            }, "json");
            return false; 
       });
       
       $("#edit-form").submit(function(){
            $.post("<?php echo site_url('kontak/sunting_kontak'); ?>", $(this).serialize(), function(data){
                if(data.hasil === false) {
                    $("#error-add-dialog").html(data.pesan);
                    $("#error-add-dialog").show();
                }
                else {
                    // Refresh ke halaman yang sama
                    window.location = "<?php echo site_url('kontak/'); ?>";
                }
            }, "json");
            return false;
       });
       
       <?php foreach($kontak as $row) { ?>
            $(".grup_kontak[data-id-kontak='<?php echo $row['id_kontak'];?>']").tokenInput("<?php echo site_url('kontak/list_grup_json'); ?>", {
                theme: "facebook",
                preventDuplicates: true,
                prePopulate: [
                    <?php
                        $i = 0;
                        $len = count($row['grup']);
                        foreach($row['grup'] as $grup)
                        {
                            echo '{id:"'.$grup['nama_grup'].'", name: "'.$grup['nama_grup'].'"}';
                            if($i < $len - 1)
                            {
                                echo ',';
                            }
                            $i++;
                        }
                    
                    ?> 
                ]
            });
            $(".grup_kontak[data-id-kontak='<?php echo $row['id_kontak'];?>']").change(function(){
                var data_sunting_grup_kontak = $.param({ id_kontak: <?php echo $row['id_kontak'];?>, data_grup: $(this).val() })
                //alert(data_sunting_grup_kontak);
                $.post("<?php echo site_url('kontak/sunting_grup_kontak'); ?>", data_sunting_grup_kontak, function(data) {
                });
            });
       <?php } ?>
    });
</script>