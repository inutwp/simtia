<?php

namespace Modules\Academic\Repositories\Lesson;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use App\Http\Traits\AuditLogTrait;
use App\Http\Traits\HelperTrait;
use App\Http\Traits\ReferenceTrait;
use Modules\Academic\Entities\LessonAssessment;
use Carbon\Carbon;

class LessonAssessmentEloquent implements LessonAssessmentRepository
{

    use AuditLogTrait;
    use HelperTrait;
    use ReferenceTrait;

	public function create(Request $request, $subject)
	{
        for ($i = 0; $i < count($request->valueopts); $i++)
        {
            $request->merge([
                'exam_id' => $request->valueopts[$i],
                'value' => $request->weights[$i],
                'logged' => auth()->user()->email,
            ]);
            $payload = $request->all();
            $this->logActivity($request, 0, $subject, 'Tambah');
            LessonAssessment::create($payload);
        }
		return;
	}

	public function update(Request $request, $subject)
	{
        for ($i = 0; $i < count($request->valueopts); $i++)
        {
            $this->logActivity($request, $request->assesment_id[$i], $subject, 'Ubah');
            LessonAssessment::where('id', $request->assesment_id[$i])
                ->update([
                    'employee_id' => $request->employee_id,
                    'grade_id' => $request->grade_id,
                    'lesson_id' => $request->lesson_id,
                    'score_aspect_id' => $request->score_aspect_id,
                    'exam_id' => $request->valueopts[$i],
                    'value' => !isset($request->weights[$i]) ? 0 : $request->weights[$i],
                    'logged' => auth()->user()->email,
                ]);
        }
		return;
	}

	public function show($employee_id, $grade_id, $lesson_id, $score_aspect_id)
	{
        return LessonAssessment::where('employee_id', $employee_id)
                ->where('grade_id', $grade_id)
                ->where('lesson_id', $lesson_id)
                ->where('score_aspect_id', $score_aspect_id)
                ->get()->map(function ($model) {
                    $model['department_id'] = $model->getLesson->department_id;
                    $model['department'] = $model->getLesson->getDepartment->name;
                    $model['lesson'] = $model->getLesson->name;
                    $model['code'] = $model->getLessonExam->code;
                    return $model;
                });
	}

    public function dataGroupIn($employeeIdArray, $gradeIdArray, $lessonIdArray, $scoreAspectIdArray)
    {
        return LessonAssessment::select('employee_id', 'grade_id', 'lesson_id', 'score_aspect_id')
                ->whereIn('grade_id', $gradeIdArray)
                ->whereIn('lesson_id', $lessonIdArray)
                ->whereIn('score_aspect_id', $scoreAspectIdArray)
                ->groupBy('employee_id', 'grade_id', 'lesson_id', 'score_aspect_id')
                ->orderBy('grade_id')
                ->get()->map(function ($model) {
                    $model['department'] = $model->getLesson->getDepartment->name;
                    $model['lesson'] = $model->getLesson->name;
                    $model['employee'] = $model->getEmployee->title_first .' '. $model->getEmployee->name .' '. $model->getEmployee->title_end;
                    $model['grade'] = $model->getGrade->grade;
                    $model['score_aspect'] = $model->getScoreAspect->remark;
                    return $model;
                });
    }

    public function showIn($employeeIdArray, $gradeIdArray, $lessonIdArray, $scoreAspectIdArray)
    {
        return LessonAssessment::whereIn('employee_id', $employeeIdArray)
                ->whereIn('grade_id', $gradeIdArray)
                ->whereIn('lesson_id', $lessonIdArray)
                ->whereIn('score_aspect_id', $scoreAspectIdArray)
                ->orderBy('grade_id')
                ->get();
    }

	public function data(Request $request)
	{
        $param = $this->gridRequest($request,'asc','employee_id');
        $query = LessonAssessment::select('employee_id','grade_id','lesson_id','score_aspect_id');
        if (auth()->user()->getDepartment->is_all != 1)
        {
            $query = $query->whereHas('getLesson', function($qry) {
                $qry->where('department_id', auth()->user()->department_id);
            });
        } 
        $query = $query->groupBy('employee_id','grade_id','lesson_id','score_aspect_id');
        // filter
        $name = isset($request->params['fname']) ? $request->params['fname'] : '';
        if ($name != '') 
        {
            $query = $query->whereHas('getEmployee', function($qry){
                $qry->whereRaw('LOWER(name) like ?', ['%'.Str::lower($name).'%']);
            });
        }
        $lesson = isset($request->params['flesson']) ? $request->params['flesson'] : '';
        if ($lesson != '') 
        {
            $query = $query->whereHas('getLesson', function($qry){
                $qry->whereRaw('LOWER(name) like ?', ['%'.Str::lower($lesson).'%']);
            });
        }
        // result
        $result["total"] = $query->distinct()->count('employee_id');
        $result["rows"] = $query->skip(($param['page'] - 1) * $param['rows'])->take($param['rows'])->orderBy($param['sort'], $param['sort_by'])->get()->map(function ($model) {
            $model['seq'] = $model->getTeacherStatus($model->lesson_id)->pluck('id')[0].$model->getLesson->department_id.$model->lesson_id.$model->employee_id.$model->grade_id.$model->getTeacherStatus($model->lesson_id)->pluck('status_id')[0];
            $model['employee'] = $model->employee_id;
            $model['grade'] = $model->grade_id;
            $model['lesson'] = $model->lesson_id;
            $model['score_aspect'] = $model->score_aspect_id;
            $model['employee_id'] = $this->getEmployeeName($model->employee_id);
            $model['grade_id'] = $model->getGrade->grade;
            $model['lesson_id'] = $model->getLesson->name;
            $model['score_aspect_id'] = $model->getScoreAspect->remark;
            $model['department'] = $model->getLesson->getDepartment->name;
            $model['department_id'] = $model->getLesson->department_id;
            return $model->only(['seq','employee_id','grade_id','lesson_id','score_aspect_id','employee','grade','lesson','score_aspect','department','department_id']);
        });
        return $result;
	}

	public function destroy($employee_id, $grade_id, $lesson_id, $score_aspect_id, $subject)
	{
        $request = new Request();
        $model_id = $employee_id.'-'.$grade_id.'-'.$lesson_id.'-'.$score_aspect_id;
        $this->logActivity($request, $model_id, $subject, 'Hapus');
        return LessonAssessment::where('employee_id', $employee_id)
            ->where('grade_id', $grade_id)
            ->where('lesson_id', $lesson_id)
            ->where('score_aspect_id', $score_aspect_id)
            ->delete();
	}

    public function combobox(Request $request)
    {
        return LessonAssessment::where('lesson_id', $request->lesson_id)
                ->where('employee_id', $request->employee_id)
                ->get()->map(function ($model) {
                    $model['score_aspect'] = $model->getScoreAspect->remark;
                    $model['subject'] = $model->getLessonExam->subject;
                    return $model;
                });
    }

    private function logActivity(Request $request, $model_id, $subject, $action) 
    {
        if ($action == 'Tambah')
        {
            $data = array(
                'employee_id' => $request->employee_id, 
                'lesson_id' => $request->lesson_id, 
                'grade_id' => $request->grade_id, 
                'score_aspect_id' => $request->score_aspect_id, 
                'exam_id' => $request->exam_id, 
                'value' => $request->value, 
            );
            $this->logTransaction('#', $action .' '. $subject, '{}', json_encode($data));
        } else {
            if ($action == 'Hapus')
            {
                $ids = explode('-', $model_id);
                $query = LessonAssessment::where('employee_id', $ids[0])
                            ->where('grade_id', $ids[1])
                            ->where('lesson_id', $ids[2])
                            ->where('score_aspect_id', $ids[3])
                            ->first();
            } else {
                $query = LessonAssessment::find($model_id);
            }
            $before = array(
                'employee_id' => $query->employee_id, 
                'lesson_id' => $query->lesson_id, 
                'grade_id' => $query->grade_id, 
                'score_aspect_id' => $query->score_aspect_id, 
                'exam_id' => $query->exam_id, 
                'value' => $query->value, 
            );
            $after = array(
                'employee_id' => $request->has('employee_id') ? $request->employee_id : $query->employee_id, 
                'lesson_id' => $request->has('lesson_id') ? $request->lesson_id : $query->lesson_id, 
                'grade_id' => $request->has('grade_id') ? $request->grade_id : $query->grade_id, 
                'score_aspect_id' => $request->has('score_aspect_id') ? $request->score_aspect_id : $query->score_aspect_id, 
                'exam_id' => $request->has('exam_id') ? $request->exam_id : $query->exam_id, 
                'value' => $request->has('value') ? $request->value : $query->value, 
            );
            if ($action == 'Ubah')
            {
                $this->logTransaction('#', $action .' '. $subject, json_encode($before), json_encode($after));
            } else {
                $this->logTransaction('#', $action .' '. $subject, json_encode($before), '{}');
            }
        } 
    }
}