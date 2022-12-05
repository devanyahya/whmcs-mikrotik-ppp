<h2>Status Koneksi</h2>

<p>Halaman ini hanya akan menunjukan status koneksi modem ke server saat layanan sedang digunakan</p>

<hr>

<div class="row">
    <div class="col-sm-5">
        {$LANG.orderproduct}
    </div>
    <div class="col-sm-7">
        {$groupname} - {$product}
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        IP Address
    </div>
    <div class="col-sm-7">
        {$ipaddress}
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        Mac Address
    </div>
    <div class="col-sm-7">
        {$macaddress}
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        Uptime
    </div>
    <div class="col-sm-7">
        {$uptime}
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        Batas Kecepatan Download/Upload
    </div>
    <div class="col-sm-7">
        {$limitberapa}
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        Session ID
    </div>
    <div class="col-sm-7">
        {$sessionid}
    </div>
</div>

<div class="row">
    <div class="col-sm-5">
        Layanan
    </div>
    <div class="col-sm-7">
        {$servis}
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        Terakhir Terputus Karena
    </div>
    <div class="col-sm-7">
        {$lastdiscconectreason}
    </div>
</div>
<div class="row">
    <div class="col-sm-5">
        Waktu Terakhir Terputus
    </div>
    <div class="col-sm-7">
        {$lastdiscconect}
    </div>
</div>


<hr>

<div class="row">
    <div class="col-sm-4">
        <form method="post" action="clientarea.php?action=productdetails">
            <input type="hidden" name="id" value="{$serviceid}" />
            <button type="submit" class="btn btn-default btn-block">
                <i class="fa fa-arrow-circle-left"></i>
                Kembali ke Informasi
            </button>
        </form>
    </div>
</div>
