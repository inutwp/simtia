@php
  $logo = strpos($profile['logo'], 'img') > 0 ? str_replace(url(''), base_path().'/public', $profile['logo']) : str_replace(url('').'/storage', base_path().'/storage/app/public/', $profile['logo']); 
@endphp
<html>
  <head>
    <title>{{ config('app.name') .' '. strtoupper(Session::get('institute')) }} - Form Pengisian Nilai Santri</title>
    <link href="file:///{{ public_path('css/print-minimal.css') }}" rel="stylesheet" />
  </head>
  <body>
    <div id="header">
      <table class="table no-border" style="width:100%;">
        <tbody>
          <tr>
            <th rowspan="2" width="100px"><img src="file:///{{ $logo }}" height="80px" /></th>
            <td><b>{{ strtoupper($profile['name']) }}</b></td>
          </tr>
          <tr>
            <td style="font-size:11px;">
              {{ $profile['address'] }}<br/>
              Telpon: {{ $profile['phone'] }} - Faksimili: {{ $profile['fax'] }}<br/>
              Website: {{ $profile['web'] }} - Email: {{ $profile['email'] }}
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <hr/>
    <div id="body">
      <br/>
      <div class="text-center" style="font-size:16px;"><b>Form Pengisian Nilai Santri</b></div>
      <br/>
      <br/>
      <div>
        <table class="table no-border" style="font-size: 13px;font-weight:700">
          <tbody>
            <tr>
              <td style="width:15%;">Departemen</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td style="width:30%;">{{ $request['department'] }}</td>
              <td style="width:20%;">Tanggal</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td>____________________</td>
            </tr>
            <tr>
              <td style="width:15%;">Tahun Ajaran</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td>{{ $request['school_year'] }}</td>
              <td style="width:20%;">Jenis Pengujian</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td>____________________________________________________________</td>
            </tr>
            <tr>
              <td style="width:15%;">Semester</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td>{{ $request['semester'] }}</td>
              <td style="width:20%;">Keterangan</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td>____________________________________________________________</td>
            </tr>
            <tr>
              <td style="width:15%;">Kelas</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td>{{ $request['grade'] .' - '. $request['class'] }}</td>
              <td style="width:20%;"></td>
              <td style="width: 1%;text-align:center;"></td>
              <td>____________________________________________________________</td>
            </tr>
            <tr>
              <td style="width:15%;">Pelajaran</td>
              <td style="width: 1%;text-align:center;">:</td>
              <td>{{ $request['lesson'] }}</td>
              <td style="width:20%;"></td>
              <td style="width: 1%;text-align:center;"></td>
              <td>____________________________________________________________</td>
            </tr>
          </tbody>
        </table>
        <br/>
        <table style="width:100%;">
          <thead>
            <tr>
                <th class="text-center" width="4%">NO.</th>
                <th class="text-center" width="15%">NIS</th>
                <th class="text-center" width="30%">NAMA</th>
                <th class="text-center" width="7%">NILAI</th>
                <th class="text-center">KETERANGAN</th>
            </tr>
          </thead>
          <tbody>
            @php $num = 1; @endphp
            @foreach ($students as $student)
              <tr>
                <td class="text-center">{{ $num }}</td>
                <td class="text-center">{{ $student->student_no }}</td>
                <td class="text-left">{{ ucwords($student->name) }}</td>
                <td class="text-center"></td>
                <td class="text-center"></td>
              </tr> 
              @php $num++; @endphp
            @endforeach
          </tbody>
        </table>
        <br/>
        <br/>
        <div style="float:right;">
          <table class="table no-border">
            <tbody>
              <tr>
                <td class="text-center">Guru</td>
              </tr>
              <tr><td></td></tr>
              <tr><td></td></tr>
              <tr><td></td></tr>
              <tr><td></td></tr>
              <tr><td></td></tr>
              <tr><td></td></tr>
              <tr><td></td></tr>
              <tr>
                <td class="text-center">{{ $request['teacher'] }}<br/>_______________________<br/>NIP. {{ $teachers->employee_id }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
  </body>
</html>