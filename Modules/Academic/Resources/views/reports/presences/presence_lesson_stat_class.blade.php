@php
    $WindowHeight = $InnerHeight - 168 . "px";
    $WindowWidth = $InnerWidth - 12 . "px";
    $GridHeight = $InnerHeight - 288 . "px";
@endphp
<div style="overflow-y: auto;">
    <div class="container-fluid mt-1 mb-1">
        <div class="row">
            <div class="col-12">
                <label class="mb-1" style="width:125px;">Tahun Ajaran:</label>
                <span class="mr-2"></span>
                <label class="mb-1" style="width:130px;">Departemen:</label>
                <span class="mr-2"></span>
                <label class="mb-1" style="width:80px;">Semester:</label>
                <span class="mr-2"></span>
                <label class="mb-1" style="width:80px;">Tingkat:</label>
                <span class="mr-2"></span>
                <label class="mb-1" style="width:200px;">Pelajaran:</label>
                <span class="mr-2"></span>
                <label class="mb-1" style="width:110px;">Dari Tanggal:</label>
                <span class="mr-2"></span>
                <label class="mb-1" style="width:110px;">Sampai Tanggal:</label>
            </div>
            <div class="col-12">
                <form id="form-report-presence-lesson-stat-class">
                <input type="hidden" id="id-report-presence-lesson-stat-class-deptid" />
                <select id="AcademicReportSchoolyear" class="easyui-combobox cbox" style="width:125px;height:22px;" data-options="panelHeight:125,valueField:'value',textField:'text'">
                    @foreach ($schoolyears as $schoolyear)
                    <option value="{{ $schoolyear->id }}">{{ $schoolyear->is_active == 1 ? $schoolyear->school_year .' (A)' : $schoolyear->school_year }}</option>
                    @endforeach
                </select>
                <span class="mr-2"></span>
                <input class="easyui-textbox tbox" id="AcademicReportDept" style="width:130px;height:22px;" data-options="readonly:true" />
                <span class="mr-2"></span>
                <select id="AcademicReportSemester" class="easyui-combogrid cgrd" style="width:80px;height:22px;" data-options="
                    panelWidth: 250,
                    idField: 'id',
                    textField: 'semester',
                    fitColumns: true,
                    method: 'post',
                    columns: [[
                        {field:'department',title:'Departemen',width:150},
                        {field:'semester',title:'Semester',width:100},
                    ]],
                ">
                </select>
                <span class="mr-2"></span>
                <select id="AcademicReportGrade" class="easyui-combobox cbox" style="width:80px;height:22px;" data-options="panelHeight:125,valueField:'value',textField:'text'"></select>
                <span class="mr-2"></span>
                <select id="AcademicReportLesson" class="easyui-combobox cbox" style="width:200px;height:22px;" data-options="panelHeight:125,valueField:'value',textField:'text'"></select>
                <span class="mr-2"></span>
                <input id="AcademicReportDateFrom" class="easyui-datebox dbox" style="width:110px;height:22px;" data-options="formatter:dateFormatter,parser:dateParser" value="{{ date('Y-m-d', strtotime('-1 months')) }}" />
                <span class="mr-2"></span>
                <input id="AcademicReportDateTo" class="easyui-datebox dbox" style="width:110px;height:22px;" data-options="formatter:dateFormatter,parser:dateParser" value="{{ date('Y-m-d') }}" />
                <span class="mr-2"></span>
                <a href="javascript:void(0)" class="easyui-linkbutton lbtn" onclick="filterAcademicReportPresenceLessonStatClass(1)" style="height:22px;" data-options="iconCls:'ms-Icon ms-Icon--Search'"></a>
                <a href="javascript:void(0)" class="easyui-linkbutton lbtn" onclick="$('#form-report-presence-lesson-stat-class').form('reset');filterAcademicReportPresenceLessonStatClass(-1)" style="height:22px;" data-options="iconCls:'ms-Icon ms-Icon--Clear'"></a>
                </form>
            </div>
            <div class="col-12">
                <br/>
                <table id="tb-report-presence-lesson-stat-class" class="easyui-datagrid" style="width:100%;height:{{ $GridHeight }}"
                    data-options="method:'post',rownumbers:true,toolbar:'#toolbarAcademicReportPresenceLessonStatClass'">
                    <thead>
                        <tr>
                            <th data-options="field:'class',width:200,resizeable:true">Kelas</th>
                            <th data-options="field:'graph',width:725,resizeable:true"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
{{-- toolbar --}}
<div id="toolbarAcademicReportPresenceLessonStatClass">
    <div class="container-fluid">
        <div class="row">
            <div class="col-6">
                <span style="line-height: 25px;"><b>Persentase Statistik Kehadiran per Kelas (%)</b></span>
            </div>
            <div class="col-6 text-right">
                <a href="javascript:void(0)" class="easyui-linkbutton lbtn" iconCls="ms-Icon ms-Icon--PDF" plain="true" onclick="exportAcademicReportPresenceLessonStatClass('pdf')">Ekspor PDF</a>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        $(".cgrd").combogrid()      
        $(".cbox").combobox()      
        $(".tbox").textbox()      
        $(".dbox").datebox()      
        $(".lbtn").linkbutton()      
        $("#AcademicReportSemester").combogrid({
            url: '{{ url('academic/semester/combo-grid') }}',
            queryParams: { _token: "{{ csrf_token() }}" },
            onClickRow: function(index, row) {
                $("#id-report-presence-lesson-stat-class-deptid").val(row.department_id)
                $("#AcademicReportDept").textbox("setValue", row.department)
                $("#AcademicReportGrade").combobox("clear")
                $("#AcademicReportGrade").combobox("reload", "{{ url('academic/report/grade/combo-box') }}" + "/" + row.department_id + "/0?_token=" + "{{ csrf_token() }}")
                $("#AcademicReportLesson").combobox("clear")
                $("#AcademicReportLesson").combobox("reload", "{{ url('academic/report/lesson/combo-box') }}" + "/" + row.department_id + "/0?_token=" + "{{ csrf_token() }}")
            }
        })
        $("#tb-report-presence-lesson-stat-class").datagrid()
    })
    function filterAcademicReportPresenceLessonStatClass(val) {
        if (val > 0) {
            if ($("#AcademicReportGrade").combobox("getValue") > 0) {
                $("#tb-report-presence-lesson-stat-class").datagrid("reload", "{{ url('academic/report/presence/lesson/stat/class/data') }}" 
                    + "?_token=" + "{{ csrf_token() }}" 
                    + "&department_id=" + $("#id-report-presence-lesson-stat-class-deptid").val()
                    + "&schoolyear_id=" + $("#AcademicReportSchoolyear").combobox("getValue")
                    + "&semester_id=" + $("#AcademicReportSemester").combogrid("getValue")
                    + "&grade_id=" + $("#AcademicReportGrade").combobox("getValue")
                    + "&lesson_id=" + $("#AcademicReportLesson").combobox("getValue")
                    + "&start_date=" + $("#AcademicReportDateFrom").datebox("getValue")
                    + "&end_date=" + $("#AcademicReportDateTo").datebox("getValue")
                )
            }
        } else {
            $("#tb-report-presence-lesson-stat-class").datagrid("loadData", [])
        }
    }
    function exportAcademicReportPresenceLessonStatClass(document) {
        var dg = $("#tb-report-presence-lesson-stat-class").datagrid('getData')
        if (dg.total > 0) {
            var payload = {
                department: $("#AcademicReportDept").textbox("getValue"),
                department_id: $("#id-report-presence-lesson-stat-class-deptid").val(),
                schoolyear: $("#AcademicReportSchoolyear").combobox("getText"),
                schoolyear_id: $("#AcademicReportSchoolyear").combobox("getValue"),
                semester: $("#AcademicReportSemester").combogrid("getText"),
                semester_id: $("#AcademicReportSemester").combogrid("getValue"),
                grade: $("#AcademicReportGrade").combobox("getText"),
                grade_id: $("#AcademicReportGrade").combobox("getValue"),
                lesson: $("#AcademicReportLesson").combobox("getText"),
                lesson_id: $("#AcademicReportLesson").combobox("getValue"),
                start_date: $("#AcademicReportDateFrom").datebox("getValue"), 
                end_date: $("#AcademicReportDateTo").datebox("getValue"),
                rows: dg.rows, 
            }
            exportDocument("{{ url('academic/report/presence/lesson/stat/class/export-') }}" + document,payload,"Ekspor Statistik Presensi Kelas ke "+ document.toUpperCase(),"{{ csrf_token() }}")
        }
    }
</script>