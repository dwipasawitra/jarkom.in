<div id="add-dialog" class="modal-dialog nodisplay">
    <h1>Tambah Kontak Baru</h1>
    <div id="error-add-dialog" class="error nodisplay" />
    </div>
    <p>Silahkan isi nama kontak, nomor handphone dan akun twitter kontak.</p>
    <form method="post" action="<?php echo site_url('kontak/tambah_kontak'); ?>" id="add-form">
    <table>
        <tr>
            <td class="col_title">Nama Kontak</td>
            <td class="col_content"><input type="text" name="nama_kontak" /></td>
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
        <input type="submit" value="Tambah Kontak Baru" />
    </form>
    
    
</div>
<div id="edit-dialog" class="modal-dialog nodisplay">
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
<table style="width: 100%">
    <span class="link-action" onclick="buka_tambah_kontak()">Tambah Kontak Baru</span> | 
    Tampilkan untuk grup : 
    <select id="pilihan-grup">
        <option value="-1" selected>-- Pilih Grup --</option>
    </select>
    <a class="link-action" href="<?php echo site_url('grup');?>"> Kelola Grup </a>
    <br/>
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
           $("#add-dialog").modal({overlayClose: true});
           
           $("#error-add-dialog").hide();
    }
    
    function buka_edit_kontak(id_kontak)
    {
        $("#edit-dialog").modal({overlayClose: true});
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
       $.get("<?php echo site_url('kontak/lihat_semua_grup'); ?>", "", function(data){
           $.each(data, function(i, row) {
                if(row.id_grup == <?php echo $id_grup; ?>) {
                    $("#pilihan-grup").append("<option value='" + row.id_grup + "' selected>" + row.nama_grup +"</option>" );
                } else {
                    $("#pilihan-grup").append("<option value='" + row.id_grup + "'>" + row.nama_grup +"</option>" );
                }
           });
       }, "json");
       
       $("#pilihan-grup").change(function() {
            if($(this).val() !== "-1") {
                window.location = "<?php echo site_url('/kontak');?>?id_grup=" + $(this).val(); 
            }
       });
       
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