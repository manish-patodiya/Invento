<?php

namespace App\Controllers;

class Auth extends BaseController
{
    public $usermodel;
    public $profilemodel;
    public $encrypter;
    public $session;
    public $validation;
    public $branchmodel;
    public $companymodel;
    public function __construct()
    {
        $this->session = \Config\Services::session();
        $this->session->start();
        $uri = service('uri');
        if ($this->session->get('is_login') && !($uri->getTotalSegments() > 1 && ($uri->getSegment(2) == 'logout' || $uri->getSegment(2) == 'mobileExist' || $uri->getSegment(2) == 'loginAs'))) {
            header("Location:" . base_url("dashboard"));
            exit;
        }
        //include validation liabrary
        helper(['form', 'url']);
        $this->validation = \config\Services::validation();

        //include encrption liabrary
        $this->encrypter = \Config\Services::encrypter();

        //initialize model variables
        $this->usermodel = model('UserModel');
        $this->branchmodel = model('Branches');
        $this->companymodel = model('CompanyModel');
    }

    public function index()
    {
        return view('auth/login');
    }

    public function loginAs($activeID = null, $comp_id = null, $branch_id = '')
    {
        if ($activeID && $comp_id) {
            $user_details = $this->session->user_details;
            $user_details['active_role_id'] = $activeID;
            $user_details['company_id'] = $comp_id;
            $user_details['branch_id'] = $branch_id;
            $this->session->set('user_details', $user_details);
            header("Location:" . base_url(config('app')->redirectURLs[$activeID]));
            exit;
        } else {
            return view('auth/login_as', ['session' => $this->session->get('user_details')]);
        }
    }

    public function login()
    {
        // ($this->session->roles);
        $check = $this->validate([
            'username' => "required",
            'password' => "required",
        ]);

        if ($check) {
            $model = $this->usermodel;

            if ($this->request->isAJAX()) {
                $username = $this->request->getPost('username');
                $password = $this->request->getPost('password');
                $user = $model->login($username, $password);
                if ($user) {
                    switch ($user->status) {
                        // case 0:
                        //     echo json_encode([
                        //         'status' => 0,
                        //         'message' => 'Your phone no. is not varified yet. <br>Please <a href="javascript:void(0)" id="verify-phn">verify</a> your phone.',
                        //         'user_id' => $user->id,
                        //         'username' => $this->request->getPost('username'),
                        //     ]);
                        //     break;
                        case 1:
                            $roles = model('UserRoles')->getUserRoles($user->id);
                            sort($roles);
                            if (count($roles) > 1) {
                                $this->setSession($roles, $user->id);
                                echo json_encode([
                                    'status' => 1,
                                    'id' => $user->id,
                                    'roles' => $roles,
                                    'message' => 'Have various roles',
                                ]);

                            } else {
                                $this->setSession($roles, $user->id, $roles[0]->role_id);
                                echo json_encode([
                                    'status' => 1,
                                    'message' => 'Login success',
                                ]);
                            }
                            break;
                        case 2:
                            echo json_encode([
                                'status' => 0,
                                'message' => 'Your account has been deactive. Please contact service provider.',
                            ]);
                            break;
                    }
                } else {
                    echo json_encode([
                        'status' => 0,
                        'message' => 'Your credentials are not valid.',
                    ]);
                }
            } else {
                die("Invalid request");
            }
        } else {
            echo json_encode([
                'status' => 0,
                'msg' => "Form is not validate",
                'errors' => $this->validation->getErrors(),
            ]);
        }
    }

    public function setSession($roles, $id, $active_role = null)
    {
        $user_img = $this->usermodel->getFields("first_name,last_name,user_img", "id='$id'");
        $user_detail = [
            "user_id" => $id,
            "user_img" => $user_img->user_img == "" ? base_url('public/images/avatar/avatar-1.png') : $user_img->user_img,
            "user_roles" => $roles,
            "complete_profile" => 1,
            "active_role_id" => $active_role,
        ];
        if (count($roles) == 1) {
            $user_detail['company_id'] = $roles[0]->company_id;
            $user_detail['branch_id'] = $roles[0]->branch_id;
        }
        //  branch id by get gst no, transaction concept id and branch name
        $brid = $user_detail['branch_id'];
        $gst_no = $this->branchmodel->getFeilds_by_id("email,name,trans_concept_id ,gst_no", "id='$brid'");
        $user_detail['gst_no'] = !empty($gst_no) ? $gst_no->gst_no : "";

        $user_detail['branch_name'] = !empty($gst_no) ? $gst_no->name : '';
        $user_detail['branch_email'] = !empty($gst_no) ? $gst_no->email : '';

        $user_detail['branch_concept_id'] = !empty($gst_no) ? $gst_no->trans_concept_id : '';

        // company id by get transaction concept id
        $compid = $user_detail['company_id'];
        $comp_arr = $this->companymodel->getFeilds_by_id("logo,trans_concept_id", "id='$compid'");
        $user_detail['company_concept_id'] = !empty($comp_arr) ? $comp_arr->trans_concept_id : "";
        $user_detail['comp_logo'] = !empty($comp_arr) && @getimagesize($comp_arr->logo) ? $comp_arr->logo : base_url('public/images/logo-letter.png');
        // ($user_detail['comp_logo']);
        // get by role name for login user
        $user_detail['role_name'] = $roles[0]->role;
        $user_detail['comapny_name'] = $roles[0]->company_name;

        // $staff_id = $this->usermodel->getFeilds_by_id("staff_id", "$id");
        // $user_detail['staff_id'] = !empty($staff_id) ? $staff_id->staff_id : "";

        $user_detail['user_name'] = $user_img->first_name . " " . $user_img->last_name;
        $this->session->set("is_login", 1);
        $this->session->set("user_details", $user_detail);

        // set permissions in sessions
        if ($active_role) {
            set_permissions_in_session($active_role);
        }
    }
    public function forgot_password()
    {

        return view('auth/forgot_password');
    }

    public function forget_password()
    {
        $username = $this->request->getPost('name');
        $umodel = $this->usermodel;
        $user = $umodel->getUserByIdentifier("username='$username' OR email='$username'");
        if ($user) {
            $this->_sendForgetPwdEmail($user);
            echo json_encode([
                "status" => 1,
                "msg" => "We have send an email to your registered email id. Please check your email.",
            ]);
        } else {
            echo json_encode([
                "status" => 0,
                "msg" => " Do not exist",
            ]);
        }
    }
    public function reset_password()
    {
        $data["token"] = $this->request->getGet('token');
        return view('auth/reset_password', $data);
    }
    private function _sendForgetPwdEmail($user)
    {
        if (!$user->email) {
            return false;
        }

        //Get template information
        $link = base_url("auth/reset_password?token=" . $user->id);
        $template = model('SettingModel')->getTemplateById(1);
        $body = str_replace("{base_url}", base_url(), $template->content);
        $body = str_replace("{reset_pwd_link}", $link, $template->content);

        $email = \Config\Services::email();

        $config['protocol'] = 'sendmail';
        $config['mailPath'] = '/usr/sbin/sendmail';
        $config['charset'] = 'iso-8859-1';
        $config['wordWrap'] = true;
        $config['mailType'] = "html";

        $email->initialize($config);
        $email->setFrom(EMAIL_FROM, EMAIL_FROM_NAME);
        $email->setTo($user->email);
        $email->setSubject($template->subject);
        $email->setMessage($body);
        echo $body;

        $email->send();

    }

    public function logout()
    {
        $this->session->set("is_login", 0);
        $this->session->set("user_details", []);

        header("Location:" . base_url());
        exit;
    }

}