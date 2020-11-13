<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Simage extends CI_Controller {

    public static $path = "simage/";

    function __construct() {

        parent::__construct();
        $this->load->model('Simage_model', 'simage');
    }

    public function imageBigUpload() {
        $oldPic = $this->input->post('oldPic');
        $picUpload = 'picUpload';

        if ($this->input->post('uploadType') == 'cover') {
            $oldPic = $this->input->post('oldCover');
            $picUpload = 'coverUpload';
        }
        echo json_encode($this->simage->imageBigUpload_model(array(
                    'uploadPath' => $this->input->post('uploadPath'),
                    'uploadType' => $this->input->post('uploadType'),
                    'oldPic' => $oldPic,
                    'picUpload' => $picUpload)));
    }

    public function imageUpload() {

        echo json_encode($this->simage->imageUpload_model(array(
                    'uploadPath' => $this->input->post('uploadPath'),
                    'oldPic' => $this->input->post($this->input->post('prefix') . 'OldPic'),
                    'prefix' => $this->input->post('prefix'))));
    }

    public function imageDelete() {
        echo json_encode($this->simage->imageDelete_model(array(
                    'table' => $this->input->post('table'),
                    'selectedId' => $this->input->post('selectedId'),
                    'uploadPath' => $this->input->post('uploadPath'),
                    'pic' => ($this->input->post($this->input->post('prefix') . 'Pic') != '' ? $this->input->post($this->input->post('prefix') . 'Pic') : $this->input->post($this->input->post('prefix') . 'OldPic')))));
    }

    public function imageCrop($param = array('uploadPath' => UPLOADS_CONTENT_PATH,)) {

        $this->newFile = $this->input->post('pic');
        $this->uploadPath = $this->input->post('uploadPath');

        $this->imageCropResult = $this->simage->imageCrop_model(array(
            'sourceImage' => $this->newFile,
            'newImage' => $this->newFile,
            'cropWidth' => $this->input->post('cropWidth'),
            'cropHeight' => $this->input->post('cropHeight'),
            'cropX' => $this->input->post('cropX'),
            'cropY' => $this->input->post('cropY'),
            'uploadPath' => $this->uploadPath
        ));

        if ($this->imageCropResult['status'] === 'success') {
            $this->simage->imageReSize_model(array(
                'uploadPath' => $this->uploadPath,
                'sourceImage' => $this->newFile,
                'newImage' => CROP_SMALL . $this->newFile,
                'height' => SMALL_HEIGHT,
                'width' => SMALL_WIDTH
            ));

            if (IS_IMAGE_WATERMARK) {
                $config['source_image'] = $_SERVER['DOCUMENT_ROOT'] . $this->uploadPath . $this->newFile;
                $config['new_image'] = $_SERVER['DOCUMENT_ROOT'] . $this->uploadPath . $this->newFile;

                $config['dynamic_output'] = FALSE;
                $config['create_thumb'] = FALSE;
                $config['maintain_ratio'] = FALSE;
                $config['wm_type'] = 'overlay';
                $config['quality'] = 100;
                $config['wm_vrt_alignment'] = 'bottom';
                $config['wm_hor_alignment'] = 'right';
                $config['wm_overlay_path'] = $_SERVER['DOCUMENT_ROOT'] . '/assets' . DEFAULT_THEME . 'img/watermark.png';

                $this->image_lib->initialize($config);
                $this->image_lib->watermark();
                $this->image_lib->clear();
            }

            echo json_encode(array(
                'pic' => $this->newFile,
                'fileType' => mime_content_type($_SERVER['DOCUMENT_ROOT'] . $this->uploadPath . $this->newFile),
                'fileSize' => filesize($_SERVER['DOCUMENT_ROOT'] . $this->uploadPath . $this->newFile),
                'status' => 'success',
                'message' => 'Амжилттай хайчиллаа'));
        } else {
            echo json_encode(array('status' => 'error', 'message' => 'Алдаа гарлаа'));
        }
    }

}
