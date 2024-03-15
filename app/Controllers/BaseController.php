<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var array
     */
    protected $helpers = [];

    /**
     * An array of helpers to be loaded automatically upon
     *
     * @var array
     */
    protected $breadcrumbs = [];

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $titlePage = '';

    
    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $titleWebsite = '';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $description = '';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $layout = '';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var string
     */
    protected $layoutView = '';

    /**
     * An string of helpers to be loaded automatically upon
     *
     * @var object
     */
    protected $content = false;

    /**
     * An array of helpers to be loaded automatically upon
     *
     * @var array
     */
    protected $values = [];

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
        $this->titleWebsite = "Bestkids";
        $this->layout = "dashboard/";
        $this->layoutView = "dashboard";

        $this->breadcrumbs = [];
        $this->values = [];
        $this->addBreadcrumb($this->titleWebsite, site_url());
        $this->addBreadcrumb($this->title, site_url($this->slug.'s'));
        // Do Not Edit This Line

        helper(['core_helper']);

        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
    }
    
    protected function setContent($title, $content=""){
        $this->content = (Object) array('title'=>$title, 'content'=>$content);
    }
    

    protected function addBreadcrumb($title, $slug="javascript:void(0)"){
        $this->breadcrumbs[] = (Object) array('title'=>$title, 'slug'=>$slug);
    }

    protected function setValues($values = []){
        $this->values = $values;
    }

    protected function View($View, $Params=[])
    {
        return view('layout/'.$this->layout.$this->layoutView, array_merge(
            [
                'view' => $View,
                'title' => $this->titleWebsite,
                'titlePage' => $this->titlePage,
                'description' => $this->description,
                'breadcrumbs' => $this->breadcrumbs,
                'content' => $this->content,
                'model' => $this->getModel(),
                'values' => $this->values
            ],
            $Params
        ));

    }

    protected function getModel(){
        return false;
    }
}
