<?php

namespace App\Controllers;

class Dashboard extends BaseController
{
    public $userroles;
    public function __construct()
    {
        check_auth();
        // check_access('dashboard');
        $this->userroles = model('UserRoles');
    }

    public function test()
    {
        $file = file_get_contents(FCPATH . 'db.json');
        $file = json_decode($file);
        $db = db_connect();
        $patients = $file->patients;
        foreach ($patients as $key => $p) {
            $p = (array) $p;
            unset($p['refto']);
            $keys = array_keys($p);
            $keys = array_unique($keys);
            if (!$db->tableExists(strtolower("tbl_patient"))) {
                $keys = implode(',', $keys);
                $keys = str_replace(",", " varchar(100), ", $keys);
                $sql = "CREATE TABLE tbl_patient ($keys varchar(100))";
                $db->query($sql);
            } else {
                foreach ($keys as $key) {
                    if (!$db->fieldExists($key, 'tbl_patient')) {
                        $db->query("ALTER TABLE tbl_patient ADD $key varchar(100)");
                    }
                }
            }
            foreach ($p as $k => $v) {
                if (is_array($v)) {
                    unset($p[$k]);
                    $fields = array_merge(['PR_patientid'], isset($v[0]) ? array_keys((array) $v[0]) : []);
                    $fields = array_unique($fields);
                    $tbl = strtolower("tbl_$k");
                    if (!$db->tableExists($tbl)) {
                        $fields = implode(',', $fields);
                        $fields = str_replace(",", " varchar(255), ", $fields);
                        $sql = "CREATE TABLE $tbl ($fields varchar(255))";
                        $db->query($sql);
                    } else {
                        foreach ($fields as $field) {
                            if (!$db->fieldExists($field, $tbl)) {
                                $db->query("ALTER TABLE $tbl ADD $field varchar(100)");
                            }
                        }
                    }
                    foreach ($v as $data) {
                        $data = array_merge(['PR_patientid' => $p['PR_patientid']], (array) $data);
                        $db->table("tbl_$k")->insert((array) $data);
                    }
                }
            }
            $db->table("tbl_patient")->insert($p);
        }
    }

    public function index()
    {
        // ($this->session->user_details['active_role_id']);
        switch ($this->session->user_details['active_role_id']) {
            case 1:
                return $this->memberDashboard();
                break;
            case 2:
                return $this->memberDashboard();
                break;
            case 3:
                return $this->memberDashboard();
                break;
            case 7:
                return $this->memberDashboard();
                break;
        }
    }

    private function memberDashboard()
    {
        $id = $this->session->get("user_details")["user_id"];
        $data = [
            'session' => $this->session,
        ];
        return view('dashboard/content/dashboard_member', $data);
    }

}
