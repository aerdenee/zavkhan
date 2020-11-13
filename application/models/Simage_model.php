<?php

class Simage_model extends CI_Model {

    private static $list = array();

    function __construct() {
        parent::__construct();
        $this->load->model('Slog_model', 'slog');
    }

    public function imageBigUpload_model($param = array('uploadFieldName' => '', 'uploadPath' => UPLOADS_CONTENT_PATH)) {

        $pic = explode(".", $_FILES[$param['picUpload']]['name']);
        $config['file_name'] = getFileUID() . '.' . end($pic);
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'];
        $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
        $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($param['picUpload'])) {

            $this->removeUploadImage_model(array('uploadImage' => $param['oldPic'], 'uploadPath' => $param['uploadPath']));
            
            $picResult = $this->upload->data();

            $this->imageReSizeResult = $this->imageReSize_model(
                    array(
                        'sourceImage' => $picResult['file_name'],
                        'newImage' => CROP_SMALL . $picResult['file_name'],
                        'height' => SMALL_HEIGHT,
                        'width' => SMALL_WIDTH,
                        'uploadPath' => $param['uploadPath']));

            if ($this->imageReSizeResult['status'] == 'success') {

                list($imageReSizeWidth, $imageReSizeHeight) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $picResult['file_name']);

                return array(
                    'status' => 'success',
                    'message' => 'Амжилттай хуулагдлаа',
                    'fileType' => $picResult['file_type'],
                    'fileSize' => filesize($_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $picResult['file_name']),
                    'pic' => $picResult['file_name'],
                    'width' => $imageReSizeWidth,
                    'height' => $imageReSizeHeight);
            }
        }

        return array('status' => 'error', 'message' => 'Зураг хуулах үед алдаа гарлаа', 'data' => $this->upload->data());
    }

    public function imageUpload_model($param = array('uploadFieldName' => '', 'uploadPath' => UPLOADS_CONTENT_PATH)) {

        $pic = explode(".", $_FILES[$param['prefix'] . 'PicUpload']['name']);
        $config['file_name'] = getFileUID() . '.' . end($pic);
        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'];
        $config['allowed_types'] = UPLOAD_IMAGE_TYPE;
        $config['max_size'] = UPLOAD_FILE_MAX_SIZE;
        $config['max_width'] = UPLOAD_IMAGE_MAX_WIDTH;
        $config['max_height'] = UPLOAD_IMAGE_MAX_HEIGHT;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ($this->upload->do_upload($param['prefix'] . 'PicUpload')) {

            $this->removeUploadImage_model(array('uploadImage' => $param['oldPic'], 'uploadPath' => $param['uploadPath']));
            
            $picResult = $this->upload->data();

            if ($picResult['image_width'] > BIG_WIDTH) {

                $this->imageReSizeResult = $this->imageReSize_model(
                        array(
                            'sourceImage' => $picResult['file_name'],
                            'newImage' => $picResult['file_name'],
                            'height' => BIG_HEIGHT,
                            'width' => BIG_WIDTH,
                            'uploadPath' => $param['uploadPath']));

                if ($this->imageReSizeResult['status'] == 'success') {

                    list($imageReSizeWidth, $imageReSizeHeight) = getimagesize($_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $picResult['file_name']);

                    return array(
                        'status' => 'success',
                        'message' => 'Амжилттай хуулагдлаа',
                        'fileType' => $picResult['file_type'],
                        'fileSize' => filesize($_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $picResult['file_name']),
                        'pic' => $picResult['file_name'],
                        'width' => $imageReSizeWidth,
                        'height' => $imageReSizeHeight);
                }
            }

            return array(
                'status' => 'success',
                'message' => 'Амжилттай хуулагдлаа',
                'fileType' => $picResult['file_type'],
                'fileSize' => $picResult['file_size'],
                'pic' => $picResult['file_name'],
                'width' => $picResult['image_width'],
                'height' => $picResult['image_height']);
        }

        return array('status' => 'error', 'message' => 'Зураг хуулах үед алдаа гарлаа', 'data' => $this->upload->data());
    }

    public function imageDelete_model($param = array('modId' => 0, 'selectedId' => 0)) {

        if ($param['selectedId'] != '0') {

            $this->removeUploadImage_model(array('uploadImage' => $param['pic'], 'uploadPath' => $param['uploadPath']));

            $query = $this->db->query('
                    SELECT 
                        mod_id,
                        pic
                    FROM `' . $this->db->dbprefix . $param['table'] . '`
                    WHERE id = ' . $param['selectedId']);

            if ($query->num_rows() > 0) {

                $row = $query->row();
                
                $data = array(
                    'modified_date' => date('Y-m-d H:i:s'),
                    'modified_user_id' => $this->session->userdata['adminUserId']);
                
                $this->db->where('id', $this->input->post('selectedId'));

                if ($this->db->update($this->db->dbprefix . $param['table'], $data)) {

                    $this->slog->log_model(array(
                        'modId' => $row->mod_id,
                        'createdUserId' => $this->session->userdata['adminUserId'],
                        'type' => LOG_TYPE_DELETE,
                        'data' => json_encode($row)));

                    return array('status' => 'success', 'message' => 'Амжилттай устлаа', 'pic' => self::returnDefaultImage_model(array('table' => $param['table'])));
                }
            }

            return array('status' => 'error', 'message' => 'Устгах зураг байхгүй');
        } else {
            
            $this->removeUploadImage_model(array('uploadImage' => $param['pic'], 'uploadPath' => $param['uploadPath']));
            
            return array('status' => 'success', 'message' => 'Амжилттай устгалаа', 'pic' => self::returnDefaultImage_model(array('table' => $param['table'])));
        }
        

        return array('status' => 'error', 'message' => 'Устгах зураг байхгүй', 'pic' => self::returnDefaultImage_model(array('table' => $param['table'])));
    }

    public function returnDefaultImage_model($param = array('table' => 'content')) {
        switch ($param['table']) {
            case 'menu': {
                    return '/assets/system/img/default.svg';
                };
                break;
            case 'category': {
                    return '/assets/system/img/default.svg';
                };
                break;
            case 'content': {
                    return '/assets/system/img/default.svg';
                };
                break;
            case 'content_media': {
                    return '/assets/system/img/default.svg';
                };
                break;
            case 'hr_people': {
                    return '/assets/system/img/iconUser.svg';
                };
                break;
            case 'media': {
                    return '/assets/system/img/default.svg';
                };
                break;
            default : {
                    return '/assets/system/img/default.svg';
                }
        }
    }

    public function imageReSize_model($param = array()) {

        $config['image_library'] = 'gd2';
        $config['source_image'] = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $param['sourceImage'];
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = TRUE;
        $config['height'] = round($param['height']);
        $config['width'] = round($param['width']);
        $config['new_image'] = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $param['newImage'];

        $this->image_lib->initialize($config);

        if ($this->image_lib->resize()) {
            $this->image_lib->clear();
            return array('status' => 'success', 'message' => 'Зургийн хэмжээг ' . round($param['width']) . ' амжилттай болголоо');
        }
        return array('status' => 'error', 'message' => 'Зургийн хэмжээсийг ' . round($param['width']) . ' болгох үед алдаа гарлаа.', 'response' => $this->image_lib->display_errors());
    }

    public function imageCrop_model($param = array()) {

        $config['image_library'] = 'gd2';
        $config['source_image'] = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $param['sourceImage'];
        $config['new_image'] = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $param['newImage'];
        $config['quality'] = 100;
        $config['create_thumb'] = FALSE;
        $config['maintain_ratio'] = false;
        $config['width'] = round($param['cropWidth']);
        $config['height'] = round($param['cropHeight']);
        $config['x_axis'] = round($param['cropX']);
        $config['y_axis'] = round($param['cropY']);

        $this->image_lib->initialize($config);

        if ($this->image_lib->crop()) {
            $this->image_lib->clear();
            return array('status' => 'success', 'response' => 'Амжилттай хайчиллаа');
        }
        return array('status' => 'error', 'response' => 'Зургийг крополсон хэсгийгээр хайжлах үед алдаа гарлаа', 'response' => $this->image_lib->display_errors());
    }

    public function removeBaseImage_model($param = array('modId' => 0, 'selectedId' => 0)) {

        $this->queryModule = $this->db->query('
            SELECT 
                `table`
            FROM `gaz_module`
            WHERE id = ' . $param['modId']);

        if ($this->queryModule->num_rows() > 0) {

            $this->rowModule = $this->queryModule->row();

            $this->tableName = $this->db->dbprefix . $this->rowModule->table . ($param['isMedia'] == 1 ? '_media' : '');

            $this->query = $this->db->query('
                SELECT 
                    ' . $param['dbFieldName'] . '
                FROM `' . $this->tableName . '`
                WHERE id = ' . $param['selectedId']);

            $this->row = $this->query->row();

            $this->removeImage = $this->removeUploadImage_model(array('uploadImage' => $this->row->$param['dbFieldName'], 'uploadPath' => $param['uploadPath']));

            if ($this->removeImage['status'] == 'success') {
                $this->data = array($param['dbFieldName'] => '');
                $this->db->where('id', $param['selectedId']);
                if ($this->db->update($this->tableName, $this->data)) {
                    return array('status' => 'success', 'message' => 'Амжилттай устлаа');
                }
            }

            return array('status' => 'error', 'message' => 'Устгах үед алдаа гарлаа');
        }
    }

    public function removeUploadImage_model($param = array('uploadImage' => '', 'uploadPath' => UPLOADS_CONTENT_PATH)) {

        $this->removeUploadImageDetail_model(array('uploadImage' => $param['uploadImage'], 'uploadPath' => $param['uploadPath']));

        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

    public function removeUploadImageDetail_model($param = array('uploadImage' => '', 'uploadPath' => UPLOADS_CONTENT_PATH)) {

        $this->orginalFile = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . $param['uploadImage'];
        $this->bigFile = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . CROP_BIG . $param['uploadImage'];
        $this->largeFile = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . CROP_LARGE . $param['uploadImage'];
        $this->mediumFile = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . CROP_MEDIUM . $param['uploadImage'];
        $this->smallFile = $_SERVER['DOCUMENT_ROOT'] . $param['uploadPath'] . CROP_SMALL . $param['uploadImage'];

        if (is_file($this->orginalFile)) {
            unlink($this->orginalFile);
        }
        if (is_file($this->bigFile)) {
            unlink($this->bigFile);
        }
        if (is_file($this->largeFile)) {
            unlink($this->largeFile);
        }
        if (is_file($this->mediumFile)) {
            unlink($this->mediumFile);
        }
        if (is_file($this->smallFile)) {
            unlink($this->smallFile);
        }
        
        return array('status' => 'success', 'message' => 'Амжилттай устгалаа');
    }

}
