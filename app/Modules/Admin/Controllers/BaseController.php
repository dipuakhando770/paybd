<?php

namespace Admin\Controllers;

use App\Libraries\Template;
use CodeIgniter\Controller;
use CodeIgniter\Exceptions\PageNotFoundException;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/*
 * Title: Template code
 * Description: Template code
 * Author: Mahadi Hasan (mehedibuet11@gmail.com)
 * Date: 2024-06-05
 */

abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;
    protected $template;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = ['form_template', 'partials'];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
    // protected $session;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.
        $this->template = new Template();
        $cookie_verify_maintenance_mode = "non-verified";

        if (isset($_COOKIE["verify_maintenance_mode"]) && $_COOKIE["verify_maintenance_mode"] != "") {
            $cookie_verify_maintenance_mode = encrypt_decode($_COOKIE["verify_maintenance_mode"]);
        }
        if ($cookie_verify_maintenance_mode != 'verified' && get_option('is_maintenance_mode') && segment(1) != "maintenance") {
            header('Location: ' . base_url('maintenance'));
            exit;
        }
        if (get_option('is_maintenance_mode') != 1 && segment(1) == "maintenance") {
            header('Location: ' . base_url('/'));
            exit;
        }
     
    }

    public function show404()
    {
        throw PageNotFoundException::forPageNotFound();
    }
}
