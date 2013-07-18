<p>Pada halaman ini, Anda dapat mengubah beberapa data diri beserta password/kata sandi dari akun Anda</p>
<h3>Data diri</h3>
<div id="data-pengguna-error" class="error nodisplay">
</div>
<div id="data-pengguna-success" class="success nodisplay">
</div>
<form method="post" action="<?php echo site_url("/akun/ubah_data_pengguna"); ?>" id="data_pengguna">
<table>
	<tr>
    	<td class="col_title"><strong>Nama akun</strong></td>
        <td class="col_content">wira</td>
    </tr>
    <tr>
    	<td><strong>Nama lengkap</strong></td>
        <td><input type="text" size="50" name="nama_lengkap" value="<?php echo $pengguna['nama_lengkap']; ?>"/></td>
    </tr>
    <tr>
    	<td><strong>Nomor Handphone</strong></td>
        <td><input type="text" size="50" name="no_handphone" value="<?php echo $pengguna['no_handphone']; ?>"/></td>
    </tr>
    
</table>
<input type="submit" value="Ubah data diri" />
</form>
<br/>

<h3>Password</h3>
<div id="password-error" class="error">
</div>
<div id="password-success" class="success">
</div>
<form method="post" action="<?php echo site_url("/akun/ubah_password"); ?>" id="password">
<table>
	<tr>
    	<td class="col_title"><strong>Password Lama</strong></td>
        <td class="col_content"><input type="password" size="50" name="password_lama" /></td>
    </tr>
    <tr>
    	<td><strong>Password Baru</strong></td>
        <td><input type="password" size="50" name="password_baru" /></td>
    </tr>
    <tr>
    	<td><strong>Ulangi Password Baru</strong></td>
        <td><input type="password" size="50" name="ulangi_password_baru" /></td>
    </tr>
</table>
 
<input type="submit" value="Ubah password" />
</form>

<script>
    $(document).ready(function() {
        $("#data-pengguna-error").hide();
        $("#data-pengguna-success").hide();
        $("#password-error").hide();
        $("#password-success").hide();
        $("#data_pengguna").submit(function() {
                $.post("<?php echo site_url('/akun/ubah_data_pengguna'); ?>", $(this).serialize(), function(data) {
                    $("#error-left").hide();
                    if(data.hasil == true) {
                        $("#data-pengguna-success").html("Data pengguna berhasil diubah");
                        $("#data-pengguna-success").fadeIn(500);
                    }
                    else
                    {
                        $("#data-pengguna-error").html(data.pesan);
                        $("#data-pengguna-error").fadeIn(500);
                    }
                }, "json");
                return false;
        });
        $("#password").submit(function() {
                $.post("<?php echo site_url('/akun/ubah_password'); ?>", $(this).serialize(), function(data) {
                    $("#error-left").hide();
                    if(data.hasil == true) {
                        $("#password-success").html("Password berhasil diubah");
                        $("#password-success").fadeIn(500);
                    }
                    else
                    {
                        $("#password-error").html(data.pesan);
                        $("#password-error").fadeIn(500);
                    }
                }, "json");
                return false;
        });
    });
        
</script>