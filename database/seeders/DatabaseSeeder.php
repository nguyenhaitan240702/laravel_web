<?php

namespace Database\Seeders;

use App\Models\Accountant;
use App\Models\Ceo;
use App\Models\Attendance;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Manager;
use App\Models\Role;
use App\Models\Salary;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run(): void
	{
		$this->call([
			Department::factory(10)
				->create(),
			Role::factory(50)
				->create(),
			Employee::factory(40)
				->create(),
			Accountant::factory(5)
				->create(),
			Ceo::factory(1)
				->create(),
			Manager::factory(10)
				->create(),
			Attendance::factory(0)
				->create(),
			Salary::factory(0)
				->create(),
		]);
	}
}
