@inject('reference', 'Modules\Finance\Http\Controllers\FinanceController')
<html>
  <head>
    <title>{{ config('app.name') .' '. strtoupper(Session::get('institute')) }} - Data Jenis Tabungan {{ $type }}</title>
    <link href="file:///{{ public_path('css/print-minimal.css') }}" rel="stylesheet" />
  </head>
  <body>
    <div id="header">
      <div style="font-weight:bold;line-height:18px;">
        <span>{{ strtoupper(Session::get('institute')) }}</span><br/>
        <span>Data Jenis Tabungan {{ $type }} </span><br/>
        <span>Tanggal Cetak Laporan: {{ date('d/m/Y') . ' - ' . date('H:i:s') . ' WIB' }}</span>
        <br/> 
      </div>
    </div>
    <br/>
    <table width="100%">
      <thead>
        <tr>
            <th class="text-center">NO.</th>
            <th class="text-left">NAMA DEPARTEMEN</th>
            <th class="text-left">NAMA</th>
            <th class="text-left">REKENING KAS</th>
            <th class="text-left">REKENING UTANG</th>
            <th class="text-center">AKTIF</th>
            <th class="text-center">KETERANGAN</th>
        </tr>
      </thead>
      <tbody>
        @php $num = 1; @endphp
        @foreach ($saving_types as $val)
          <tr>
            <td class="text-center">{{ $num }}</td>
            <td class="text-left">{{ $val->getDepartment->name }}</td>
            <td>{{ $val->name }}</td>
            <td>{{ $val->getCashAccount->code .' | '. $val->getCashAccount->name }}</td>
            <td>{{ $val->getCreditAccount->code .' | '. $val->getCreditAccount->name }}</td>
            <td class="text-center">{{ $reference->getActive()[$val->is_active] }}</td>
            <td>{{ $val->remark }}</td>
          </tr> 
          @php $num++; @endphp
        @endforeach
      </tbody>
    </table>
  </body>
</html>