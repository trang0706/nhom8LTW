<?php

session_start();

$route = $_GET['route'] ?? $_GET['r'] ?? '';

if (empty($route)) {
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '');
    if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
        $uri = substr($uri, strlen($scriptName));
    }
    $route = trim($uri, '/');
}

$routes = [
    '' => ['HomeController', 'index'],
    'jobs' => ['JobController', 'index'],
    'jobs/detail' => ['JobController', 'detail'],
    
    'applications/apply' => ['ApplicationController', 'apply'],
    'applications/history' => ['ApplicationController', 'history'],
    'applications/download-cv' => ['ApplicationController', 'downloadCv'],
    
    'profile' => ['ProfileController', 'index'],
    'profile/update' => ['ProfileController', 'update'],
    
    'employer/dashboard' => ['EmployerController', 'dashboard'],
    'employer/jobs' => ['EmployerController', 'jobs'],
    'employer/jobs/create' => ['EmployerController', 'createJob'],
    'employer/jobs/store' => ['EmployerController', 'storeJob'],
    'employer/jobs/edit' => ['EmployerController', 'editJob'],
    'employer/jobs/update' => ['EmployerController', 'updateJob'],
    'employer/jobs/toggle' => ['EmployerController', 'toggleJobStatus'],
    'employer/jobs/delete' => ['EmployerController', 'deleteJob'],
    'employer/candidates' => ['EmployerController', 'candidates'],
    'employer/candidates/update-status' => ['EmployerController', 'updateStatus'],
    'employer/candidates/export' => ['EmployerController', 'exportCsv'],
    'employer/interviews' => ['EmployerController', 'interviews'],
    
    'admin/dashboard' => ['AdminController', 'dashboard'],
    'admin/companies' => ['AdminController', 'companies'],
    'admin/companies/toggle' => ['AdminController', 'toggleCompanyStatus'],
    'admin/users' => ['AdminController', 'users'],
    'admin/users/toggle' => ['AdminController', 'toggleUserStatus'],
    'admin/jobs' => ['AdminController', 'jobs'],
    'admin/jobs/delete' => ['AdminController', 'deleteJob'],
    'admin/skills' => ['AdminController', 'skills'],
    
    'auth/login' => ['AuthController', 'login'],
    'auth/register' => ['AuthController', 'register'],
    'auth/logout' => ['AuthController', 'logout'],
    
    'api/jobs/search' => ['ApiController', 'searchJobs'],
    'api/jobs/check-applied' => ['ApiController', 'checkApplied'],
    'api/notifications/read' => ['ApiController', 'markNotificationRead'],
];

if (array_key_exists($route, $routes)) {
    [$controllerName, $actionName] = $routes[$route];
    $controllerFile = __DIR__ . '/app/Controllers/' . $controllerName . '.php';

    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();
        $controller->$actionName();
        exit;
    }
}

http_response_code(404);
header("Location: /");
exit;


