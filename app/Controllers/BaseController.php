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
     * @var string
     */
    protected $withLayout = '';

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
     * An array of helpers to be loaded automatically upon
     *
     * @var array
     */
    protected $vars = null;

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
        if(!IS_NULL($request)){
            $this->setValues($request->getPost());
        }
        
        $this->addBreadcrumb($this->titleWebsite, site_url());
        $this->addBreadcrumb($this->title, site_url($this->slug.'s'));
        // Do Not Edit This Line

        helper(['core_helper', 'option_helper']);

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


    protected function addValues($values = []){
        $this->values = array_merge($this->values, $values);
    }

    protected function getValues($Model=false){
        if(!$this->getModel()){
            return false;
        }

        return ($Model?$Model:$this->getModel())->setValues($this->values);
    }

    protected function getID(){
        if(!$this->getModel()){
            return false;
        }

        return $this->getModel()->getID($this->values);
    }

    protected function FILTER(){
        return [];
    }

    protected function View($View, $Params=[])
    {
        return view('layout/'.$this->layout.$this->layoutView, array_merge(
            [
                'view' => $View,
                'lang' => 'es',
                'title' => $this->titleWebsite,
                'titlePage' => $this->titlePage,
                'description' => $this->description,
                'filter' => $this->viewFilter,
                'breadcrumbs' => $this->breadcrumbs,
                'content' => $this->content,
                'model' => $this->getModel(),
                'values' => $this->values,
                'action' => $this->slug,
                'validator'=>$this->validator,
                'columns'=>$this->getColumns(),
                'all'=>$this->FILTER(),
                'vars'=>$this->vars,
                'layout' => $this->withLayout,
                'td'=>function($tr, $td, $column){
                    return $this->td($tr, $td, $column);
                },
                'head'=>function(){
                    return $this->head();
                },
                'script'=>function(){
                    return $this->script();
                }
            ],
            $Params
        ));
    }

    protected function isNew(){
        if(!$this->getModel()){
            return true;
        }

        return !$this->getModel()->isDeleted($this->values);
    }

    protected function getModel(){
        return false;
    }

    protected function viewContent(): Object
    {
        return (Object) array();
    }

    public function index()
    {
        $this->titlePage = $this->viewContent()->list->titlePage;
        $this->description = $this->viewContent()->list->description;
        $this->setContent($this->viewContent()->list->title, $this->viewContent()->list->content);
        unset($this->breadcrumbs[1]);
        $this->addBreadcrumb($this->titlePage);
        $this->withLayout = 'index';

        // Verificar si el usuario ya está autenticado
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        return $this->View($this->viewList);
    }

    public function new()
    {
        $this->titlePage = $this->viewContent()->new->titlePage;
        $this->description = $this->viewContent()->new->description;
        $this->setContent($this->viewContent()->new->title, $this->viewContent()->new->content);
        $this->addBreadcrumb($this->titlePage);
        $this->withLayout = 'new';

        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        return $this->View($this->viewEdit);
    }

    public function details($id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $Model = $this->getModel()->Exists($id);
        
        if(strlen(trim($id))===0 || is_null($Model)){
            return redirect()->to($this->slug.'s')->with('warning', '¡Este registro no existe!');
        }

        $description = isset($Model[$this->getModel()->description()]) ? $Model[$this->getModel()->description()]: "";

        $this->titlePage = $this->viewContent()->view->titlePage.$description;
        $this->description = $this->viewContent()->view->description.$description;
        $this->setContent($this->viewContent()->view->title.$description, $this->viewContent()->view->content.$description);
        $this->addBreadcrumb($this->titlePage);

        $this->setValues($Model);

        $this->withLayout = 'view';

        return $this->View($this->viewView);
    }

    public function edit($id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $this->titlePage = $this->viewContent()->edit->titlePage;
        $this->description = $this->viewContent()->edit->description;
        $this->setContent($this->viewContent()->edit->title, $this->viewContent()->edit->content);
        $this->addBreadcrumb($this->titlePage);

        $Model = $this->getModel()->Exists($id);
        
        if(strlen(trim($id))===0 || is_null($Model)){
            return redirect()->to($this->slug.'s')->with('warning', '¡Este registro no existe!');
        }

        $this->setValues($Model);

        $this->withLayout = 'edit';
        return $this->View($this->viewEdit);
    }

    public function trash($id)
    {
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $Model = $this->getModel();
        $Data = $Model->Exists($id);
        if(strlen(trim($id))===0 || is_null($Data)){
            return redirect()->to($this->slug.'s')->with('warning', '¡Este registro no existe!');
        }

        $this->setValues($Data);
        $Model->delete($this->getID());

        return redirect()->to($this->slug.'s')->with('success', '¡El registro ha sido eliminado exitosamente!');
    }

    public function saved(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        if($this->validate($this->getModel()->getValidation())){
            $data = ($this->getValues());

            $Model = $this->getModel();
            $Saved = ($this->isNew() && !$this->getID()) ? $Model->insert($data) : $Model->update($this->getID(), $data);

            if($Saved){
                $ModelId = $this->getID();
                if($this->isNew() && !$this->getID()){
                    $ModelId = $Model->getInsertID();
                }
                return redirect()->to($this->slug.'/edit/'.$ModelId)->with('success', _('¡Los datos se han guardado correctamente!'));
            }

        }
        
        $this->titlePage =  $this->isNew() ? $this->viewContent()->new->titlePage: $this->viewContent()->edit->titlePage;
        $this->description =  $this->isNew() ? $this->viewContent()->new->description: $this->viewContent()->edit->description;
        if($this->isNew()){
            $this->setContent($this->viewContent()->new->title, $this->viewContent()->new->content);
        }else{
            $this->setContent($this->viewContent()->edit->title, $this->viewContent()->edit->content);
        }
        $this->addBreadcrumb($this->titlePage);
        return $this->View($this->viewEdit);
    }

    public function save(){
        if (!(session()->get('isLoggedIn'))) {
            return redirect()->to('/login');
        }

        $data = ($this->getValues());

        if($this->validate($this->getModel()->getValidation()) || count($this->getModel()->getValidation()) === 0){
            
            $Model = $this->getModel();
            $Saved = ($this->isNew() && !$this->getID()) ? $Model->insert($data) : $Model->update($this->getID(), $data);

            if($Saved){
                $ModelId = $this->getID();
                if($this->isNew() && !$this->getID()){
                    $ModelId = $Model->getInsertID();
                }
                $data[$Model->primaryKey()] = $ModelId;
                return json_encode(['data' => $data, 'validator'=> null, 'status' => 'success', 'message' => _('¡Los datos se han guardado correctamente!')]);
            }

        }
        
        return json_encode(['data' => $data, 'validator'=> (\Config\Services::validation())->getErrors()]);
    }

    protected function td($tr, $td, $column){
        return $td;
    }

    protected function head(): string
    {
        return '';
    }

    protected function script(): string
    {
        return '';
    }
}
