<body onload="window.print()">
    <div>
        <div class="header">
            <center>
                <h2>KOPI 1815</h2>
                <h3>LAPORAN LABA RUGI</h3>
                <h4>Priode : {{ $dari }} Sampai :
                    {{ $sampai }}</h4>
            </center>
        </div>
    </div>

    <table class="table" border="1" width="100%">
        <tbody>
            <tr>
                <td>Penjualan</td>
                <td> Rp.{{ number_format($penjualan , 2, ',', '.')  }} </td>
                <td></td> </tr> <tr>
                    <td>Modal</td>
                    <td></td>
                    <td>Rp.{{ number_format($pembelian , 2, ',', '.')  }}</td>
            </tr>
            <tr>
                <td align="center"><b>Laba</td>
                <td></td>
                <td style="text-align:right;"><b>Rp.{{ number_format($laba , 2, ',', '.')  }}</b></td>
            </tr>
        </tbody>
</body>
