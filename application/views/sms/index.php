<p>Silahkan menulis tujuan pesan (dalam bentuk nama grup, nama kontak atau nomor handphone penerima) beserta pesan yang ingin dikirimkan. Untuk saat ini,
    JARKOM.IN hanya membatasi pengiriman pesan pendek 160 karakter per pesannya. Setiap pengiriman pesan SMS berharga 1 poin.
    
</p>
<p>
    <strong>Fitur pengiriman pesan via Twitter akan segera diimplementasikan -- Admin</strong>
</p>
<h3>Kirim Pesan</h3><br>
<div id="sms-error" class="error">
</div>
<div class="success">
</div>
<form name="form_kirim" action="<?php echo site_url('sms/proses_sms'); ?>" method="post" id="form-sms">
	<table>
		<tr>
			<td class="col_title"><strong>Kirim Ke&nbsp;</strong></td>
			<td class="col_content"><input type="text" name="kontak" size="40" id="kontak"/> </td>
		</tr>
       
                 <tr>
			<td  style="vertical-align:top" ><strong>Pesan</strong></td>
			<td><textarea name="konten" cols="50" rows="5" id="konten"> </textarea></td>
		</tr>
                <tr>
			<td  style="vertical-align:top" ><strong>Jumlah Karakter</strong></td>
			<td><input id="jumlah-karakter" type="text" readonly="readonly" size="4" value="0"/></td>
                </tr>
                        <td  style="vertical-align:top" ><strong>Kredit yang dibutuhkan</strong></td>
			<td><input id="kredit-dibutuhkan" type="text" readonly="readonly" size="4" value="0"/>
                            <span id="kredit-dibutuhkan-msg">Kredit Anda tidak mencukupi!</span>
                        </td>
		</tr>
    
                 <tr>
			<td><input type="submit" value="Kirimkan Pesan!" /><input type="button" value="Ulangi" onclick="reset_form()"/></td>
			<td>&nbsp;</td>
		</tr>
	</table>
</form>
<script>
    function reset_form()
    {
        $("#kontak").tokenInput("clear");
        $("#konten").val("");
        $("#konten").trigger("keyup");
        $(".error").hide();
    }
    
    $(document).ready(function() {
       var jumlah_karakter = 0, kredit_dibutuhkan, kredit_saat_ini;
       $("#sms-error").hide();
       $(".success").hide();
       $("#kredit-dibutuhkan-msg").hide();
       $("#form-sms").submit(function() {
           $("#sms-error").hide();
           if(kredit_dibutuhkan <= kredit_saat_ini && jumlah_karakter <= 160) {
                $.post("<?php echo site_url('sms/proses_sms'); ?>", $(this).serialize(), function(data) {
                    if(data.hasil === true)
                    {
                        $(".success").html("Pengiriman pesan berhasil dilaksanakan, menunggu proses selanjutnya");
                        $(".success").fadeIn(500);
                        reset_form();
                        muat_ulang_poin();
                    }
                    else
                    {
                        $("#sms-error").fadeIn(500);
                        $("#sms-error").html(data.pesan);
                    }
                }, "json");
                
           } else {
                $(".error").html("Pengiriman pesan tidak dapat dilaksanakan.");
                $(".error").fadeIn(500);
           }
           return false;
       });
       
       $("#kontak").tokenInput("<?php echo site_url('sms/cari_kontak_json'); ?>", {
            hintText: "Silahkan masukan nomor handphone/nama kontak/nama grup",
            searchingText: "Mencari data...",
            noResultText: "Data tidak ditemukan",
            preventDuplicates: true
       });
       
       $("#konten").keyup(function() {
           jumlah_karakter = $(this).val().length;
           $("#jumlah-karakter").val(jumlah_karakter);
           if(jumlah_karakter > 160) {
               $("#jumlah-karakter").css("background-color", "red");
           } else {
               $("#jumlah-karakter").css("background-color", "white");
           }
           $("#kontak").trigger("change");
       });
       
       $("#kontak").change(function(){
           // Lakukan pengecekan jumlah kontak yang terlibat
           var masukan = { 
               kontak: $("#kontak").val(),
               panjang_sms: jumlah_karakter
           };
           $.post("<?php echo site_url('sms/jumlah_kredit_dibutuhkan'); ?>", $.param(masukan), function(data) {
               kredit_dibutuhkan = data.kredit_dibutuhkan;
               kredit_saat_ini = data.kredit_saat_ini;
               $("#kredit-dibutuhkan").val(kredit_dibutuhkan);
               if(kredit_dibutuhkan > kredit_saat_ini) {
                   $("#kredit-dibutuhkan").css("background-color", "red");
                   $("#kredit-dibutuhkan-msg").fadeIn(500);
               } else {
                   $("#kredit-dibutuhkan").css("background-color", "white");
                   $("#kredit-dibutuhkan-msg").fadeOut(500);
               }
           }, "json");
       });
    });
</script>