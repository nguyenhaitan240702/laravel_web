<?php

namespace App\Http\Controllers;

use App\Enums\ShiftEnum;
use App\Models\AttendanceShiftTime;
use App\Models\Ceo;
use App\Http\Requests\StoreCeoRequest;
use App\Http\Requests\UpdateCeoRequest;
use App\Models\Employee;
use App\Models\Fines;
use App\Models\Manager;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;


class CeoController extends Controller
{
    use ResponseTrait;
	public function __construct()
	{
		$this->middleware('ceo');
		$this->model = Manager::query();
		$routeName = Route::currentRouteName();
		$arr = explode('.', $routeName);
		$arr = array_map('ucfirst', $arr);
		$title = implode(' - ', $arr);

		View::share('title', $title);
	}

	/**
	 * Display a listing of the resource.
	 *
	 */
	public function index()
	{
		$limit = 25;
		$fields = [
			'id',
			'fname',
			'lname',
			'gender',
			'dob',
			'email',
			'role_id',
			'dept_id',
		];
		$data = Employee::whereStatus(1)
			->with(['roles', 'departments'])
			->paginate($limit, $fields);
		return view('ceo.index', ([
			'data' => $data
		]));
	}

	public function time()
	{
//		dd(Schema::getColumnListing('attendance_shift_times'));
		$time = AttendanceShiftTime::get();
		$count = AttendanceShiftTime::count();
		$shifts = ShiftEnum::asArray();
		return view('ceo.time', [
			'time' => $time,
			'count' => $count,
			'shifts' => $shifts,
		]);
	}

	public function time_save(Request $request)
	{
		$check_in_start = $request->get('in_start');
		$check_in_end = $request->get('in_end');
		$check_in_late_1 = $request->get('in_late_1');
		$check_in_late_2 = $request->get('in_late_2');
		$check_out_early_1 = $request->get('out_early_1');
		$check_out_early_2 = $request->get('out_early_2');
		$check_out_start = $request->get('out_start');
		$check_out_end = $request->get('out_end');
		$status = $request->get('status');
		$id = $request->get('id');
		AttendanceShiftTime::create([
			'id' => $id,
			'check_in_start' => $check_in_start,
			'check_in_end' => $check_in_end,
			'check_in_late_1' => $check_in_late_1,
			'check_in_late_2' => $check_in_late_2,
			'check_out_early_1' => $check_out_early_1,
			'check_out_early_2' => $check_out_early_2,
			'check_out_start' => $check_out_start,
			'check_out_end' => $check_out_end,
			'status' => $status,
		]);
		return AttendanceShiftTime::whereId($id)->get();
	}

	public function time_change(Request $request)
	{
		$check_in_start = $request->get('in_start');
		$check_in_end = $request->get('in_end');
		$check_in_late_1 = $request->get('in_late_1');
		$check_in_late_2 = $request->get('in_late_2');
		$check_out_early_1 = $request->get('out_early_1');
		$check_out_early_2 = $request->get('out_early_2');
		$check_out_start = $request->get('out_start');
		$check_out_end = $request->get('out_end');
		$name = $request->get('name');
		$id =ShiftEnum::getValue($name);
		AttendanceShiftTime::whereId($id)
			->update([
				'check_in_start' => $check_in_start,
				'check_in_end' => $check_in_end,
				'check_in_late_1' => $check_in_late_1,
				'check_in_late_2' => $check_in_late_2,
				'check_out_early_1' => $check_out_early_1,
				'check_out_early_2' => $check_out_early_2,
				'check_out_start' => $check_out_start,
				'check_out_end' => $check_out_end,
			]);

		return AttendanceShiftTime::whereId($id)->get();
	}

	public function pay_rate_api(Request $request): JsonResponse
	{


		$dept_id = $request->get('dept_id');
		$data = Role::query()
			->leftJoin('departments', 'roles.dept_id', '=', 'departments.id')
			->select('roles.*', 'departments.name as dept_name')
			->where('dept_id', '=', $dept_id)
			->paginate();
		// return $data->append('pay_rate_money');
        foreach ($data as $each){
            $each->pay_rate = $each->pay_rate_money;
        }
        $arr['data'] = $data->getCollection();
        $arr['pagination'] = $data->linkCollection();

        return $this->successResponse($arr);

	}

	public function pay_rate_change(Request $request): array
	{
		$pay_rate = $request->pay_rate;
		$id = $request->id;
		Role::query()
			->Where('id', '=', $id)
			->update([
				'pay_rate' => $pay_rate,
			]);
		return Role::whereId($id)->get()->append('pay_rate_money')->toArray();
	}

    public function role_store(Request $request)
    {
        $name = $request->name;
        $dept_id = $request->dept_id;
        $pay_rate = $request->pay_rate;
         Role::create([
            'name' => $name,
            'dept_id' => $dept_id,
            'pay_rate' => $pay_rate,
            'status' => '1',
         ]);
        $roles = Role::query()
        ->leftJoin('departments', 'roles.dept_id', '=', 'departments.id')
        ->select('roles.*', 'departments.name as dept_name')
        ->where('dept_id', '=', $dept_id)
        ->get();
        return $roles->append('pay_rate_money')->toArray();
    }

	public function fines_store(Request $request): array
	{
		$name = $request->name;
		$fines = $request->fines;
		$deduction = $request->deduction;
		return Fines::create([
			'name' => $name,
			'fines' => $fines,
			'deduction' => $deduction,
		])->append(['fines_time', 'deduction_detail'])->toArray();
	}

	public function fines_update(Request $request): array
	{
		$id = $request->id;
		$name = $request->name;
		$fines = $request->fines;
		$deduction = $request->deduction;
		Fines::query()
			->where('id', $id)
			->update([
				'name' => $name,
				'fines' => $fines,
				'deduction' => $deduction,
			]);
		return Fines::whereId($id)->get()->append(['fines_time', 'deduction_detail'])->toArray();
	}

    public function create_emp()
    {
        return view('ceo.create');
    }
	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		//
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param StoreCeoRequest $request
	 * @return Response
	 */
	public function store(StoreCeoRequest $request)
	{
		//
	}

	/**
	 * Display the specified resource.
	 *
	 * @param Ceo $ceo
	 * @return Response
	 */
	public function show(Ceo $ceo)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param Ceo $ceo
	 * @return Response
	 */
	public function edit(Ceo $ceo)
	{
		//
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param UpdateCeoRequest $request
	 * @param Ceo $ceo
	 * @return Response
	 */
	public function update(UpdateCeoRequest $request, Ceo $ceo)
	{
		//
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param Ceo $ceo
	 * @return void
	 */
	public function destroy(Ceo $ceo)
	{
		//
	}
}
