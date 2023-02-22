<style>
    @media print {
        #back_to_list {
            display: none;
        }
    }
</style>

<body onload="window.print()">
    <a href="{{ route('filament.resources.penjualan.index') }}" id="back_to_list">List Penjualan</a>

    <div style="margin-left:150px; margin-right:150px;">
        <div class="header" style="margin-bottom:10px;" >
            <table width="100%" style="text-align: center;">
                <tr>
                    <td>
                        <div>
                            <img src="{{ url('/storage/kopi1815.png') }}" width="5%">
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div>
                            <span>Kopi 1815 BDJ</span>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Jl. Mahat Kasar, Gatot Subroto VI No. 158</span>
                    </td>

                </tr>
                <tr>
                    <td>
                        <span>Banjarmasin Kota, Kalimantan Selatan</span>
                    </td>
                </tr>
                <tr>
                    <td>
                        <span>Indonesia</span>
                    </td>
                </tr>
            </tablew>
        </div>
        <div class="body">
            <table>
                <tr>
                    <td>
                        <div>
                            <p>Time </p>
                        </div>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{ \Carbon\Carbon::now() }}</p>
                    </td>
                </tr>
                <tr>
                    <td>
                        <p>Cashier</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        <p>{{ $penjualan->user->name }}</p>

                    </td>
                </tr>
                @if ($penjualan->is_member)
                <tr>
                    <td>
                        <p>Customer</p>
                    </td>
                    <td>
                        <p>:</p>
                    </td>
                    <td>
                        {{ $penjualan->member->nama }}
                    </td>
                </tr>    
                @endif
            </table>
            <table width="100%">
                <tr>
                    <td>
                        <p>#{{ $penjualan->no_transaksi }}</p>
                    </td>
                    <td>
                         <p style="text-align: right;">1 Guest</p>

                    </td>
                </tr>
            </table>
            <hr style="height:2px; background-color:#000;">
            @foreach ($penjualan->penjualan_detail as $item)
            <table width="100%">
                <tr>
                    <td width="25%">
                        <p>{{ $item->menu->nama }}</p>
                    </td>
                </tr>
                @if ($penjualan->pembayaran != $point)
                <tr>
                    <td width="25%">
                    </td>
                    <td width="5%">
                        <p>Rp.{{ number_format($item->harga , 2, ',', '.')  }}</p>
                    </td>
                    <td width="5%">
                        <p>{{ $item->qty }}x</p>
                    </td>
                    <td width="100%">
                        <p style="text-align: right;">
                            Rp.{{ number_format($item->subtotal , 2, ',', '.')  }}
                        </p>
                    </td>
                </tr>    
                @endif
            </table>
            @endforeach
            <hr style="height:2px; background-color:#000;">
            @if ($penjualan->pembayaran != $point)
            <table width="100%">
                <tr>
                    <td width="15%">
                        Total
                    </td>
                    <td width="100%">
                        <p style="text-align:right;">
                            Rp.{{ number_format($penjualan->total , 2, ',', '.') }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        {{ $penjualan->pembayaran == $cash ? 'Cash' : 'Non Cash' }}
                    </td>
                    <td width="100%">
                        <p style="text-align:right;">
                            Rp.{{ number_format($penjualan->bayar , 2, ',', '.') }}
                        </p>
                    </td>
                </tr>
                @if ($penjualan->kembalian != 0)
                <tr>
                    <td width="15%">
                        Change Due
                    </td>
                    <td width="100%">
                        <p style="text-align:right;">
                            Rp.{{ number_format($penjualan->kembalian , 2, ',', '.') }}
                        </p>
                    </td>
                </tr>
                @endif
            </table>
            @endif
            @if ($penjualan->is_member)
            <table width="100%" style="margin-top: 10px;">
                <tr>
                    <td width="15%">
                        Beginning Points
                    </td>
                    <td width="100%">
                        <p style="text-align:right;">
                            {{ $beginning_points }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        {{ $penjualan->pembayaran == $point ? 'Points Used' : 'Earned Points' }}
                    </td>
                    <td width="100%">
                        <p style="text-align:right;">
                            {{ $penjualan->member_point->point ?? 0 }}
                        </p>
                    </td>
                </tr>
                <tr>
                    <td width="15%">
                        Total Points
                    </td>
                    <td width="100%">
                        <p style="text-align:right;">
                            {{ $total_points }}
                        </p>
                    </td>
                </tr>
            </table>
            @endif
        </div>
        <div class="footer" style="margin-top:35px;">
            <div align="center">Wifi Password:<br>satulapansatulima</div>
        </div>
    </div>

</body>
