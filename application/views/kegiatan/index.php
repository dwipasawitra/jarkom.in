<div id="tambah-kegiatan" class="modal-dialog">
    <h1>Membuat kegiatan baru</h1>
    <div class="error"></div>
    <p>Silahkan tentukan nama kegiatan, waktu pelaksanaan serta kelompok yang berpartisipasi dalam kegiatan ini</p>
    <form method="post">
    <table>
        <tr>
            <td>Identitas Kegiatan</td>
            <td><input type="text" name="id_kegiatan" /><br/>
                <i>Tanpa spasi</i></td>
        </tr>
        <tr>
            <td>Nama Kegiatan</td>
            <td><input type="text" name="nama_kegiatan" /></td>
        </tr>
        <tr>
            <td>Waktu Pelaksanaan Kegiatan</td>
            <td><input type="text" name="waktu_pelaksanaan_kegiatan" /></td>
        </tr>
        <tr>
            <td>Grup yang berpartisipasi</td>
            <td>
                <select name="grup">
                </select>
            </td>
        </tr>
    </table>
    <input type="submit" value="Tambah Kegiatan Baru" />
    </form>
</div>
<p>Pada halaman ini, Anda dapat melakukan manajemen kegiatan dengan fitur pengiriman pesan pengingat dan konfirmasi kehadiran kegiatan</p>

<span class="link-action" onclick="buka_tambah_kegiatan()">Tambah Kegiatan</span>
<table>
    <?php foreach($kegiatan as $row) { ?>
        <tr style="border: 1px solid black;">
            <td>
                <strong><?php echo $row['nama_kegiatan']; ?></strong><br/>
                ID Kegiatan: <?php echo $row['id_kegiatan']; ?> <br/>
                Waktu pelaksanaan: <?php echo $row['waktu_mulai_kegiatan']; ?></br>
            </td>
            <td>
                <a href="<?php echo site_url('/kegiatan/kelola_pengingat'); echo('?id_kegiatan='.$row['id_kegiatan']); ?>">Kelola Pengingat</a><br/>
                <a href="<?php echo site_url('/kegiatan/konfirmasi_kegiatan'); echo('?id_kegiatan='.$row['id_kegiatan']); ?>">Lihat Konfirmasi Kehadiran</a>
            </td>
        </tr>
    <?php } ?>
</table>
<script>
    function buka_tambah_kegiatan()
    {
        $("#tambah-kegiatan .error").hide();
        $("#tambah-kegiatan").modal();
        $("#tambah-kegiatan select").html("");
        $.get("<?php echo site_url('kontak/lihat_semua_grup');?>","",function(data){
            $.each(data, function(i, row) {
                $("#tambah-kegiatan select").append("<option value='" + row.id_grup + "'>" + row.nama_grup + "</option>");
            });
        }, "json");
    }
    
    
    $(document).ready(function() {
        $("#tambah-kegiatan").hide();
        $("#tambah-kegiatan form").submit(function() {
            $.post("<?php echo site_url('kegiatan/tambah_kegiatan'); ?>", $(this).serialize(), function(data){
                if(data.hasil === true) {
                    //window.location = "<?php echo site_url('/kegiatan'); ?>";
                } else {
                    $("#tambah-kegiatan .error").html(data.pesan);
                    $("#tambah-kegiatan .error").show();
                }
            }, "json");
            return false; 
        });
    });
</script>