<?php

namespace App\Models;

use Database\Factories\ManagerFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Manager
 *
 * @property int $id
 * @property string $fname
 * @property string $lname
 * @property int $gender
 * @property string $dob
 * @property string $email
 * @property string $password
 * @property int $dept_id
 * @property int $role_id
 * @property int $status
 * @property-read string $age
 * @property-read string $full_name
 * @property-read string $gender_name
 * @method static ManagerFactory factory(...$parameters)
 * @method static Builder|Manager newModelQuery()
 * @method static Builder|Manager newQuery()
 * @method static Builder|Manager query()
 * @method static Builder|Manager whereDeptId($value)
 * @method static Builder|Manager whereDob($value)
 * @method static Builder|Manager whereEmail($value)
 * @method static Builder|Manager whereFname($value)
 * @method static Builder|Manager whereGender($value)
 * @method static Builder|Manager whereId($value)
 * @method static Builder|Manager whereLname($value)
 * @method static Builder|Manager wherePassword($value)
 * @method static Builder|Manager whereRoleId($value)
 * @method static Builder|Manager whereStatus($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Department|null $department
 * @property-read string $date_of_birth
 * @property-read \App\Models\Role|null $role
 */
class Manager extends Model
{
	use HasFactory;

	protected $fillable = [
		'fname',
		'lname',
		'gender',
		'dob',
		'email',
		'password',
		'dept_id',
		'role_id',
	];
    public function getDateOfBirthAttribute(): string
    {
        return date_format(date_create($this->dob),"d/m/Y");
    }

	public function getAgeAttribute(): string
	{
		return date_diff(date_create($this->dob), date_create())->y;
	}

	public function getFullNameAttribute(): string
	{
		return $this->fname . ' ' . $this->lname;
	}

	public function getGenderNameAttribute(): string
	{

		return ($this->gender === 1 ? 'Male' : 'Female');
	}
	public function department(): HasOne
	{
		return $this->hasOne(Department::class,'id', 'dept_id');
	}
	public function role(): HasOne
	{
		return $this->hasOne(Role::class,'id', 'role_id');
	}

	public $timestamps = false;

}
