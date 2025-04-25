<?php

namespace App\Controllers;

class AccountsAr extends Apirest
{
    protected $modelName = 'App\Models\AccountModel';
    protected $format    = 'json';

    public function login()
    {
        //$this->response->setHeader('Access-Control-Allow-Origin', '*');
        //$this->response->setHeader('Access-Control-Allow-Methods', 'GET, POST, OPTIONS');
        //$this->response->setHeader('Access-Control-Allow-Headers', 'Content-Type, Authorization');

        if(!$this->Authenticate()){
            return $this->NotAuthorized();
        }

        if(!$this->validate([
            'email'=>[
                'rules'=>'required|valid_email',
                'errors'=>[
                    'required'=> '¡El correo es requerido!',
                    'valid_email'=>'¡Ingresa una dirección de correo válida!'
                ]
            ],
            'password'=>[
                'rules'=>'required',
                'errors'=>[
                    'required'=> '¡La contraseña es requerida!'
                ]
            ]
        ])){
            return $this->Response([
                'validation'=> (\Config\Services::validation())->getErrors()
            ]);
        }

        $request = $this->request->getJSON();

        $Account = $this->model->where('enabled', 1)
            ->where('username', strtolower($request->email))
            ->first();

        $Valid = [
            'message' => '¡Correo y contraseña incorrecta!',
            'hash' => false
        ];

        if (!IS_NULL($Account) && password_verify($request->password, $Account['password'])) {
            $IPAddress = $this->request->getIPAddress();
            $payload = [
                'idaccount' => $Account['idaccount'],
                'ip' => $IPAddress,
                'token' => encrypt_token($Account['idaccount'].'|'.$Account['idrole'].'|'.$Account['username'].'|'.$IPAddress)
            ];

            $token = getJWT($payload, '2 days'); // Generar el token con duración de 48h

            
            unset($Valid['message']);
            $Valid['hash'] = true;
            $Valid['token'] = $token;
            $Valid['user'] = array(
                'name' => $Account['name'],
                'surname' => $Account['surname'],
                'fullname' => $Account['name'].' '.$Account['surname'],
                'idrole' => $Account['idrole'],
                'idaccount' => $Account['idaccount'],
                'name' => $Account['name'],
                'groups' => $this->groups($Account)
            );

            $this->model->update($Account['idaccount'],[
                'lastlogin' => date('Y-m-d H:i:s'),
                'lastip' => $IPAddress
            ]);
        }

        return $this->Response($Valid);
    }  
    
    public function me()
    {

        if(!$this->Authenticate()){
            return $this->NotAuthorized();
        }

        $Account = $this->user();

        if($Account){

            return $this->Response(['user'=>array(
                'name' => $Account['name'],
                'surname' => $Account['surname'],
                'fullname' => $Account['name'].' '.$Account['surname'],
                'idrole' => $Account['idrole'],
                'idaccount' => $Account['idaccount'],
                'name' => $Account['name'],
                'groups' => $this->groups($Account)
            )]);            
        }else{
            return $this->Response(null, 401);  
        }
    }  
    
    private function groups($Account){
        if($Account){
            $EntityModel = new \App\Models\EntityModel();
            $TermModel = new \App\Models\TermModel();
            $SocietyModel = new \App\Models\SocietyModel();
            $Society = $SocietyModel->ExistsByCode('groups');
            $Grupos = proccess_model_filters($Society["modelmain"],$Society["filtermain"])->Lists;

            $Join = [];
            if(!IS_NULL($Grupos)){
                $Asignaturas = $TermModel->where('idtaxonomy', 3)->findAll();
                $Programas = $EntityModel->where('idtaxonomy', 9)->findAll();

                foreach ($Programas as $j => $programa) {
                    $Programas[$j]['meta'] = $EntityModel->getMeta($programa['identity']);
                }
                foreach ($Asignaturas as $j => $asignatura) {
                    $Asignaturas[$j]['meta'] = $TermModel->getMeta($asignatura['idterm']);

                    $ListPrograms = [];
                    foreach ($Programas as $programa) {
                        if($programa['meta']["idsubject"] === $asignatura['idterm']){
                            $ListPrograms[] = $programa;
                        }
                    }

                    $Asignaturas[$j]['programs'] = $ListPrograms;
                }
                foreach ($Grupos as $j => $grupo) {
                    $lists = $SocietyModel->getMeta($Society["idsocietys"], $grupo["idterm"]);
                    if(count($lists)>0){
                        foreach ($lists as $list) {
                            if($Account['idaccount']."" === $list['idcolumnend'].""){
                                $Object = $TermModel->getMeta($grupo['idterm']);
                                $Curse = $TermModel->Exists($Object["idcourse"]);

                                if(!IS_NULL($Curse)){
                                    
                                    $ListsAsigature = [];
                                    foreach ($Asignaturas as $asignatura) {
                                        if($asignatura['meta']["idcourse"] === $Curse["idterm"]){
                                            $ListsAsigature[] = $asignatura;
                                        }
                                    }

                                    //$Curse['meta'] = $TermModel->getMeta($Object['idcourse']);
                                    $Curse['asignaturs'] = $ListsAsigature;
                                    $grupo['curse'] = $Curse;
                                    $Join[] = $grupo;                                    
                                }
                            }
                        }
                    }
                }
            }

            return $Join;
        }

        return [];
    }

    public function lesson()
    {

        if(!$this->Authenticate()){
            return $this->NotAuthorized();
        }

        $request = $this->request->getJSON();
        $EntityModel = new \App\Models\EntityModel();
        $SocietyModel = new \App\Models\SocietyModel();

        $Gaming = $EntityModel->Exists($request->level);
        $Gaming['meta'] = $EntityModel->getMeta($request->level);


        $Society = $SocietyModel->ExistsByCode("blocks");

        $Gaming['taxonomy'] = null;
        //$Gaming['society'] = $Society;
        
        $Associates = $SocietyModel->getMeta($Society["idsocietys"], $request->level);

        $End = proccess_model_filter($Society["modelend"], $Society["filterend"], $request->level);

        $rows = [];

        foreach ($Associates as $list) {
            foreach ($End->Lists as $j => $row) {
                if(isset($row[$End->Model->primaryKey()]) && isset($row[$Society['selectedend']])){
                    if($row[$Society['selectedend']]."" === $list['idcolumnend'].""){
                        $ID = $row[$End->Model->primaryKey()];
                        $row["metas"] = $EntityModel->getMeta($ID);//  $End->Model->getMeta($ID);

                        $Panel = (new \App\Models\TabviewModel())->getTabView(4, $ID);

                        if(IS_NULL($Gaming['taxonomy'])){
                            $Gaming['taxonomy'] = $Panel->taxonomy;
                        }

                        $row["pages"] = $Panel->model;
                        $rows[] = (Object) $row;

                        //$render .=society_card_view((Object) $row);
                    }
                }
                
            }            
        }

        $Gaming['associates'] = $rows;

        /*$Account = $this->model->where('enabled', 1)
            ->where('username', strtolower($request->email))
            ->first();

        $Valid = [
            'message' => '¡Correo y contraseña incorrecta!',
            'hash' => false
        ];

        if (!IS_NULL($Account) && password_verify($request->password, $Account['password'])) {
            $IPAddress = $this->request->getIPAddress();
            $payload = [
                'idaccount' => $Account['idaccount'],
                'ip' => $IPAddress,
                'token' => encrypt_token($Account['idaccount'].'|'.$Account['idrole'].'|'.$Account['username'].'|'.$IPAddress)
            ];

            $token = getJWT($payload, '2 days'); // Generar el token con duración de 48h

            
            unset($Valid['message']);
            $Valid['hash'] = true;
            $Valid['token'] = $token;
            $Valid['user'] = array(
                'name' => $Account['name'],
                'surname' => $Account['surname'],
                'fullname' => $Account['name'].' '.$Account['surname'],
                'idrole' => $Account['idrole'],
                'idaccount' => $Account['idaccount'],
                'name' => $Account['name'],
                'groups' => $this->groups($Account)
            );

            $this->model->update($Account['idaccount'],[
                'lastlogin' => date('Y-m-d H:i:s'),
                'lastip' => $IPAddress
            ]);
        }*/

        return $this->Response($Gaming);
    }  
}