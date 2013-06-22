<p>Pada halaman ini, Anda dapat mengirim pesan ke nomor tujuan, dengan mengisi nomor tujuan beserta pesan yang akan dikirim</p>
<h3>Kirim Pesan</h3><br>
<div id="sms-error" class="error">
</div>
<form name="form_kirim" action="<?php echo site_url('sms/proses_sms'); ?>" method="post" id="form-sms">
	<table>
		<tr>
			<td><strong>Kirim Ke&nbsp;</strong></td>
			<td><input type="text" name="kontak" size="40" id="kontak"/> </td>
		</tr>
        <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
        <tr>
			<td  style="vertical-align:top" ><strong>Pesan</strong></td>
			<td><textarea name="konten" cols="70" rows="10"> </textarea></td>
		</tr>
        <tr>
			<td>&nbsp;</td>
			<td>&nbsp;</td>
		</tr>
        <tr>
			<td><input type="submit" value="Kirim" /></td>
			<td>&nbsp;</td>
		</tr>
	</table>
</form>
<script>
    $(document).ready(function() {
       $("#sms-error").hide();
       $("#form-sms").submit(function() {
           $("#sms-error").hide();
           $.post("<?php echo site_url('sms/proses_sms'); ?>", $(this).serialize(), function(data) {
               if(data.hasil === true)
               {
                   window.location = "<?php echo site_url('sms/terkirim'); ?>";
               }
               else
               {
                   $("#sms-error").fadeIn(500);
                   $("#sms-error").html(data.pesan);
               }
           }, "json");
           return false;
       });
       
       $("#kontak").tokenInput("<?php echo site_url('sms/cari_kontak_json'); ?>", {
            hintText: "Silahkan masukan nomor handphone/nama kontak/nama grup",
            searchingText: "Mencari data...",
            noResultText: "Data tidak ditemukan",
            preventDuplicates: true
       });
    });
</script>