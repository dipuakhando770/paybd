<?php

namespace Admin\Controllers;
use Admin\Controllers\BaseController;
use Admin\Models\AdminModel;
use Admin\Models\StaffModel;
use App\Libraries\Mysqldump;
use Installer\Controllers\InstallerController;

class Settings extends AdminController
{
    public $data = [];
    public $model,$main_model,$table;


    public function __construct()
    {
        parent::__construct();
        $this->model = new AdminModel();
        $this->main_model = new StaffModel();
        $this->controller_name = 'settings';
        helper('files');

    }

    public function index()
    {
        $this->data['item'] = $this->main_model->get_item(null,['task'=>'get-current-staff']);
        $this->template->view('settings/profile',$this->data)->render();
    }
       

    /**
     * Update the specified resource in storage.
     *
     * @param $id
     */
    public function settings($tab='')
    {
        helper('email');

        $path              = APPPATH.'Modules/Admin/Views/settings/elements';
        $elements = get_name_files_from_dir($path);
        if (!in_array($tab, $elements) || $tab == "") {
            $tab = 'website_setting';
        }
        $this->data = array(
            "controller_name"   => $this->controller_name,
            "tab"               => $tab,
        );
        $this->template->view('settings/settings',$this->data)->render(); 
    } 
    public function store($id = '')
{
    _is_ajax();

    
    $data = $this->request->getPost();

    if (isset($data['home_page'])) {
        if (!empty($data['homepage_code'])) {
            $homepage_code = $data['homepage_code'];
            $file_path = APPPATH . "Modules/Home/Views/index.php";
            if (file_put_contents($file_path, $homepage_code) !== false) {
                ms(["status" => "success", "message" => 'Homepage file updated successfully']);
            } else {
                       ms(["status" => "error", "message" => 'Failed to update homepage file']);
            }
        } else {
            ms(["status" => "error", "message" => 'Homepage code is empty']);
        }
    }

    if (isset($data['dev_page'])) {
        if (!empty($data['devpage_code'])) {
            $devpage_code = $data['devpage_code'];
            $file_path = APPPATH . "Modules/Home/Views/developers/docs.php";
            if (file_put_contents($file_path, $devpage_code) !== false) {
                ms(["status" => "success", "message" => 'Developer file updated successfully']);
            } else {
                ms(["status" => "error", "message" => 'Failed to update Developer Page file']);
            }
        } else {
            ms(["status" => "error", "message" => 'Developer Page code is empty']);
        }
    }

    if (is_array($data)) {
        if (!empty($data['sms_setting'])) {
            $headerDataKeys = [];
            $paramKeys = [];
            $formDataKeys = [];
            
            if (!empty($data['headerDataKeys'])) {
                foreach($data['headerDataKeys'] as $key => $val){
                    $headerDataKeys[$val] = $data['headerDataValues'][$key];
                }
                unset($data['headerDataKeys']);
                unset($data['headerDataValues']);
            }
            
            if (!empty($data['paramKeys'])) {
                foreach($data['paramKeys'] as $key => $val){
                    $paramKeys[$val] = $data['paramValues'][$key];
                }
                unset($data['paramKeys']);
                unset($data['paramValues']);
            }
            
            if (!empty($data['formDataKeys'])) {
                foreach($data['formDataKeys'] as $key => $val){
                    $formDataKeys[$val] = $data['formDataValues'][$key];
                }
                unset($data['formDataKeys']);
                unset($data['formDataValues']);
            }
            
            update_option('sms_api_header_data', json_encode($headerDataKeys));
            update_option('sms_api_params', json_encode($paramKeys));
            update_option('sms_api_formdata', json_encode($formDataKeys));
        }
        
        foreach ($data as $key => $value) {
            if (in_array($key, ['embed_javascript', 'currency_code', 'embed_head_javascript', 'manual_payment_content'])) {
                $value = htmlspecialchars($value, ENT_QUOTES);
            }
            update_option($key, $value);
        }
        
        if (!empty($data['update_file'])) {
            foreach ($data as $key => $value) {
                update_site_config($key, $value);
            }
        }
    }

    ms(["status" => "success", "message" => 'Update successfully']);
}

    public function update($id=null)
    {
        $validation = \Config\Services::validation();
        if(post('type')=='account'){
            $validation->setRules([
                'email'    => 'required|valid_email',
                'first_name'     => 'required|min_length[2]',
                'last_name'     => 'required|min_length[2]',
                'avatar'   => 'required'
            ]);
            $data = [
                'first_name' => post('first_name'),
                'last_name' => post('last_name'),
                'avatar' => post('avatar'),
            ];
        }elseif(post('type')=='password'){

            $validation->setRules([
                'old_password'    => 'required',
                'password' => 'required|differs[old_password]|min_length[5]',
                'confirm_password' => [
                    'rules'  => 'required|matches[password]',
                    'errors' => [
                        'matches' => 'Confirm Password field must match with Password field.',
                    ],
                ],
            ]);
            $data = [
                'password' => password_hash(post('password'), PASSWORD_BCRYPT)
            ];

            if(!$this->model->verify_access(post('old_password'))){
                ms(['status'=>'error','message'=>'Old Password is incorrect'.post('old_password')]);
            }
        }
            
        if (!$validation->withRequest($this->request)->run()) {
            $message='';
            foreach($validation->getErrors() as $va ){
                $message .=$va.'<br>'; 
            }
            ms(['status'=>'error','message'=>$message]);
        }

        $result = $this->model->where('id', session('sid'))->builder()->update($data);

        if ($result) {
            ms(['status' => 'success', 'message' => 'Changed successfully']);
        } else {
            $message = '';
            foreach ($this->model->errors() as $va) {
                $message .= $va . '<br>'; 
            }
            ms(['status' => 'error', 'message' => $message]);
        }
    }

    public function addons()
    {
        $this->columns     =  array(
            "name"         => ['name' => 'Name', 'class' => 'text-center'],
            "price"         => ['name' => 'Price', 'class' => 'text-center'],
            "status"           => ['name' => 'Status',  'class' => 'text-center'],
        );
        $items = $this->main_model->fetch('*','addons',[],'id','DESC','','',true);
        $this->data = array(
            "controller_name"   => $this->controller_name,
            "items"   => $items,
            "columns" =>$this->columns,
            "addons" => get_name_folders_from_dir()
        );
        $this->template->view('settings/addons/index',$this->data)->render(); 
    }
    public function useraddon($action,$param='') {
        _is_ajax();

        switch ($action) {
            case 'bulk_action':
                $params = [
                    'ids'       => post('ids'),
                    'type'      => $param,
                ];
                $response = $this->model->save_item($params, ['task' => 'bulk-action']);
                ms($response);
                break;
            
            case 'delete':
                $params['id'] = $param;
                $response = $this->model->delete_item($params, ['task' => 'delete-item']);
                ms($response);
                break;
        }      
    }
    
    public function AddonUpdate($id = null){
        _is_ajax();
        $item = null;
        if ($id !== null) {
            $this->params = ['id' => $id];
            $item = $this->main_model->get('*','addons',['id'=>$id],'','',true);
        }
        $data = array(
            "controller_name"   => $this->controller_name,
            "item"              => $item,
        );
        echo view('Admin\Views\settings\addons\update',$data);

    }
    public function addonStore(){
        _is_ajax();
        $rules = [
            'name' => 'trim|required|xss_clean',
            'price' => 'trim|required|xss_clean',
            'version' => 'trim|required|xss_clean',
            'image' => 'trim|required|xss_clean',
            'status' => 'trim|required|in_list[0,1]|xss_clean',
        ];
        if (!$this->validate($rules)) {
            $errors = $this->validator->listErrors();
            ms(['status' => 'error', 'message' => $errors]);
        }

        if (!empty(post('id'))) {
            $data = [
                "name"              => post("name"),
                "price"             => post("price"),
                "version"           => post("version"),
                "status"            => (int)post("status"),
                "image"             => post('image'),
            ];
            $this->db->table('addons')->where('id',post('id'))->update($data);
            ms(["status"  => "success", "message" => 'Addon Updated successfully']);
        }else{
            $data = [
                "name"              => post("name"),
                "price"             => post("price"),
                "version"           => post("version"),
                "status"            => (int)post("status"),
                "image"             => post('image'),
                "unique_identifier" => str_replace(" ", "_", post('name'))
            ];

            $this->db->table('addons')->insert($data);
            ms(["status"  => "success", "message" => 'Addon added successfully']);
        }

        $response = $this->main_model->save_item( $this->params, ['task' => $task]);
        ms($response);
    }

    public function change_status($id='')
    {
        if(post('status')==1){
            update_option('enable_'.$id, post('status'));
            $result = shell_exec("php spark module:migrate all");
            if($result){
                $cofigPath = APPPATH.'Modules/Blocks/Database/Migartions/'.$id.'.php';
                if(file_exists($cofigPath)){
                    unlink($cofigPath);
                }
            }


            $filePath = APPPATH.'Modules/Blocks/Config/Routes.php';
            $fileContent = file_get_contents($filePath);

            $cofigPath = APPPATH.'Modules/Blocks/Addons/'.$id.'/config.php';
            if(file_exists($cofigPath)){
                $codeToInsert = file_get_contents($cofigPath);

                if ($codeToInsert != FALSE) {

                    if (strpos($fileContent, $codeToInsert) === false) {
                        // The code block is not found in the file, so we append it.
                        $writeResult = file_put_contents($filePath, $codeToInsert, FILE_APPEND);
                        
                        if ($writeResult !== false) {
                            unlink($cofigPath);
                            ms(['status'=>'success','message'=>'Addons Installed Successfully']);
                        }
                    } 
                } 
            }
           
            //code to table insert           

            ms(['status'=>'success','message'=>'Addons Activated Successfully']);
        }else{
            update_option('enable_'.$id, post('status'));
            ms(['status'=>'success','message'=>'Addons Deactivated Successfully']);
        }
    }

    public function addonsExtract()
    {
        extract_zip_file((post('file')),APPPATH.'Modules/Blocks');
    }
    public function cache() 
    {
        $cache = \Config\Services::cache();
        $cache->clean();
        set_flashdata('message',['status'=>'success','message'=>'Cache cleaned Successfully']);
        return redirect()->to(previous_url());
    }
   
    public function backupDatabase()
    {
        $db = db_connect();
        $backupDirectory = WRITEPATH . 'backups/';
        $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
        if (!is_dir($backupDirectory)) {
            mkdir($backupDirectory, 0777, true);
        }
        
        $backupFile = $backupDirectory . $filename;
    
        // Check if the backup directory is writable
        if (!is_writable($backupDirectory)) {
            die('Backup directory is not writable');
        }
    
        $dump = new Mysqldump("mysql:host={$db->hostname};dbname={$db->database}", "{$db->username}", "{$db->password}");
        $dump->start($backupFile);
    
        // Set appropriate headers to force download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . basename($backupFile));
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($backupFile));
        ob_clean();
        flush();
        readfile($backupFile);

        unlink($backupFile);

        exit;
    }
    

    

}


