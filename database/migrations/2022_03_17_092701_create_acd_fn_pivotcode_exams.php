<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $procedure = "  CREATE OR REPLACE FUNCTION academic.fn_pivotcode_exams (
                            IN tablename CHARACTER VARYING,
                            IN rowc CHARACTER VARYING,
                            IN colc CHARACTER VARYING,
                            IN cellc CHARACTER VARYING,
                            IN p_assessment_id BIGINT,
                            IN p_class_id BIGINT,
                            IN p_semester_id BIGINT,
                            IN celldatatype CHARACTER VARYING
                        )
                        RETURNS CHARACTER VARYING
                        LANGUAGE 'plpgsql'
                        AS $$
                            DECLARE 
                                dynsql1 VARCHAR;
                                dynsql2 VARCHAR;
                                columnlist VARCHAR;
                            BEGIN
                                dynsql1 = 'SELECT string_agg(DISTINCT '||colc||'||'' '||celldatatype||''','','' ORDER BY '||colc||'||'' '||celldatatype||''') FROM '||tablename||' WHERE lesson_assessment_id = '||p_assessment_id||' AND class_id = '||p_class_id||' AND semester_id = '||p_semester_id||';';
                                EXECUTE dynsql1 INTO columnlist;
                                dynsql2 = 'SELECT * FROM crosstab (
                                    ''SELECT '||rowc||',student_no,INITCAP(name) AS student,avg_score,final_score,remark,id,'||colc||','||cellc||' FROM '||tablename||' WHERE lesson_assessment_id = '||p_assessment_id||' AND class_id = '||p_class_id||' AND semester_id = '||p_semester_id||' GROUP BY 1,2,3,4,5,6,7,8,9 ORDER BY 1,2'',
                                    ''SELECT DISTINCT '||colc||' FROM '||tablename||' WHERE lesson_assessment_id = '||p_assessment_id||' AND class_id = '||p_class_id||' AND semester_id = '||p_semester_id||' ORDER BY 1''
                                ) AS newtable (
                                    '||rowc||' bigint,student_no VARCHAR, student VARCHAR, avg_score NUMERIC, final_score NUMERIC, remark VARCHAR, id bigint,'||columnlist||'
                                );';
                                RETURN dynsql2;
                            END;
                        $$;";
        DB::unprepared($procedure);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared("DROP FUNCTION IF EXISTS academic.fn_pivotcode_exams");   
    }
};
