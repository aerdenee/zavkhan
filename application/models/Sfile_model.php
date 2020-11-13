<?php

class Sfile_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Slog_model', 'slog');
    }

    public function fileUpload_model($param = array('uploadFieldName' => '', 'uploadPath' => UPLOADS_CONTENT_PATH)) {


        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'])) {

            mkdir($_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'], 0777, true);
            chmod($_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'], 0777);
        }
        
        $file = explode(".", $_FILES[$param['prefix'] . 'AttachFileUpload']['name']);
        $config['file_name'] = getFileUID() . '.' . end($file);
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'];
        $config['allowed_types'] = UPLOAD_ALL_FILE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($param['prefix'] . 'AttachFileUpload')) {

            $this->removeUploadFile_model(array('uploadFile' => $param['oldFile'], 'uploadPath' => $param['uploadPath']));

            $fileResult = $this->upload->data();

            return array('status' => 'success', 'message' => 'Амжилттай хуулагдлаа', 'fileName' => $fileResult['file_name'], 'fileType' => $fileResult['file_type'], 'fileSize' => $fileResult['file_size']);
        }

        return array('status' => 'error', 'message' => 'Файл хуулах үед алдаа гарлаа', 'data' => $this->upload->data());
    }

    public function fileDelete_model($param = array('modId' => 0, 'selectedId' => 0)) {

        $query = $this->db->query('
                    SELECT 
                        mod_id,
                        attach_file
                    FROM `' . $this->db->dbprefix . $param['table'] . '`
                    WHERE id = ' . $param['selectedId']);

        if ($query->num_rows() > 0) {

            $row = $query->row();

            $this->removeUploadFile_model(array('uploadFile' => $row->attach_file, 'uploadPath' => $param['uploadPath']));

            $data = array(
                'attach_file' => '',
                'modified_date' => date('Y-m-d H:i:s'),
                'modified_user_id' => $this->session->adminUserId);
            $this->db->where('id', $param['selectedId']);

            if ($this->db->update($this->db->dbprefix . $param['table'], $data)) {

                $this->slog->log_model(array(
                    'modId' => $row->mod_id,
                    'createdUserId' => $this->session->adminUserId,
                    'type' => LOG_TYPE_DELETE,
                    'data' => json_encode($row)));
            }
        }

        if ($param['oldFile'] != '') {
            $this->removeUploadFile_model(array('uploadFile' => $param['oldFile'], 'uploadPath' => $param['uploadPath']));
        }

        return array('status' => 'success', 'message' => 'Файлыг амжилттай устлаа');
    }

    public function removeUploadFile_model($param = array('uploadFile' => '', 'uploadPath' => UPLOADS_CONTENT_PATH)) {

        $orginalFile = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $param['uploadFile'];

        if (is_file($orginalFile)) {
            unlink($orginalFile);
        }
    }

}
