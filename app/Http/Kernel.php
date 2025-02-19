<?php

namespace App\Http;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckAcct;
use App\Http\Middleware\CheckCeo;
use App\Http\Middleware\CheckEmp;
use App\Http\Middleware\CheckLogin;
use App\Http\Middleware\CheckMgr;
use App\Http\Middleware\EncryptCookies;
use App\Http\Middleware\PreventRequestsDuringMaintenance;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\TrimStrings;
use App\Http\Middleware\TrustProxies;
use App\Http\Middleware\VerifyCsrfToken;
use Fruitcake\Cors\HandleCors;
use Illuminate\Auth\Middleware\AuthenticateWithBasicAuth;
use Illuminate\Auth\Middleware\Authorize;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Auth\Middleware\RequirePassword;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Foundation\Http\Kernel as HttpKernel;
use Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\SetCacheHeaders;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Routing\Middleware\ThrottleRequests;
use Illuminate\Routing\Middleware\ValidateSignature;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class Kernel extends HttpKernel
{
	/**
	 * The application's global HTTP middleware stack.
	 *
	 * This middleware are run during every request to your application.
	 *
	 * @var array
	 */
	protected $middleware = [
		// \App\Http\Middleware\TrustHosts::class,
		TrustProxies::class,
		HandleCors::class,
		PreventRequestsDuringMaintenance::class,
		ValidatePostSize::class,
		TrimStrings::class,
		ConvertEmptyStringsToNull::class,
	];

	/**
	 * The application's route middleware groups.
	 *
	 * @var array
	 */
	protected $middlewareGroups = [
		'web' => [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
//			AuthenticateSession::class,
			ShareErrorsFromSession::class,
			VerifyCsrfToken::class,
			SubstituteBindings::class,

		],

		'api' => [
			'throttle:api',
			SubstituteBindings::class,
		],
		'employee' => [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
//		    AuthenticateSession::class,
			ShareErrorsFromSession::class,
			VerifyCsrfToken::class,
			SubstituteBindings::class,
			CheckEmp::class,
		],
		'accountant' => [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
//		    AuthenticateSession::class,
			ShareErrorsFromSession::class,
			VerifyCsrfToken::class,
			SubstituteBindings::class,
			CheckAcct::class,
		],
		'manager' => [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
//		    AuthenticateSession::class,
			ShareErrorsFromSession::class,
			VerifyCsrfToken::class,
			SubstituteBindings::class,
			CheckMgr::class,
		],
		'ceo' => [
			EncryptCookies::class,
			AddQueuedCookiesToResponse::class,
			StartSession::class,
//		    AuthenticateSession::class,
			ShareErrorsFromSession::class,
			VerifyCsrfToken::class,
			SubstituteBindings::class,
			CheckCeo::class,
		],

	];

	/**
	 * The application's route middleware.
	 *
	 * This middleware may be assigned to group or used individually.
	 *
	 * @var array
	 */
	protected $routeMiddleware = [
		'auth' => Authenticate::class,
		'auth.basic' => AuthenticateWithBasicAuth::class,
		'cache.headers' => SetCacheHeaders::class,
		'can' => Authorize::class,
		'guest' => RedirectIfAuthenticated::class,
		'password.confirm' => RequirePassword::class,
		'signed' => ValidateSignature::class,
		'throttle' => ThrottleRequests::class,
		'verified' => EnsureEmailIsVerified::class,
		'employee' => CheckEmp::class,
		'ceo' => CheckCeo::class,
		'manager' => CheckMgr::class,
		'accountant' => CheckAcct::class,
		'login' => CheckLogin::class,
	];
}
