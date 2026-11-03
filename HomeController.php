<?php

require_once __DIR__ . '/BaseController.php';
require_once __DIR__ . '/../Models/Job.php';
require_once __DIR__ . '/../Models/Category.php';
require_once __DIR__ . '/../Models/Company.php';
require_once __DIR__ . '/../Models/Skill.php';
require_once __DIR__ . '/../Models/Application.php';

class HomeController extends BaseController {
    public function index(): void {
        $jobModel = new Job();
        $catModel = new Category();
        $companyModel = new Company();
        $skillModel = new Skill();
        $appModel = new Application();

        $featuredJobs = $jobModel->getFeaturedJobs(6);
        $categories = $catModel->getAll();
        $companies = $companyModel->getAll('active');
        $skills = $skillModel->getAll();
        
        $stats = [
            'total_jobs' => $jobModel->countFiltered(null, null, null, 'open'),
            'total_companies' => count($companies),
            'total_applications' => ($appModel->countAllByStatus())['total'] ?? 0,
            'success_rate' => 96
        ];

        $this->render('home/index', [
            'pageTitle' => 'Cổng Tuyển Dụng & Ứng Tuyển Thực Tập - Khoa Toán - CNTT',
            'featuredJobs' => $featuredJobs,
            'categories' => $categories,
            'companies' => array_slice($companies, 0, 6),
            'skills' => array_slice($skills, 0, 10),
            'stats' => $stats
        ]);
    }
}


