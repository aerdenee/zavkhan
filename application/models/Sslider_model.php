<?php

class Sslider_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('string');
        $this->load->helper('date');
        $this->load->library('image_lib');
    }

    public function contentPublish_model($contId, $publish) {
        $this->db->where('id', $contId);
        $this->db->update($this->db->dbprefix . 'slider', array('publish' => $publish));
    }

    public function contentUpdate_model($contId) {
        $data = array(
            'catid' => $this->input->post('catid'),
            'file' => $this->input->post('file'),
            'title' => $this->input->post('title'),
            'linktitle' => $this->input->post('linktitle'),
            'url' => $this->input->post('url'),
            'createdate' => date('Y-m-d H:i:s'),
            'publish' => $this->input->post('publish'),
            'ordering' => $this->input->post('ordering'),
            'target' => $this->input->post('target'),
            'type' => $this->input->post('type'),
            'description' => $this->input->post('description'),
            'memberid' => $this->session->memberId,
            'lang' => $this->session->lang
        );
        $this->db->where('id', $contId);
        $this->db->update($this->db->dbprefix . 'slider', $data);
    }

    public function contentEdit_model($id) {

        $this->db->where('id', $id);
        $query = $this->db->get($this->db->dbprefix . 'slider');
        $result = $query->result();
        if (count($result) > 0) {
            return $result = (array) $result[0];
        } else {

            return array(
                'id' => 0,
                'catid' => 0,
                'file' => '',
                'title' => '',
                'linktitle' => '',
                'url' => '',
                'createdate' => date('Y-m-d H:i:s'),
                'publish' => 1,
                'ordering' => self::getOrdering(),
                'target' => '_parent',
                'type' => 1,
                'description' => '',
                'hits' => 0,
                'memberid' => $this->session->memberId,
                'lang' => $this->session->lang
            );
        }
    }

    public function contentInsert_model($modId) {

        $data = array(
            array(
                'catid' => $this->input->post('catid'),
                'modid' => $modId,
                'file' => $this->input->post('file'),
                'title' => $this->input->post('title'),
                'linktitle' => $this->input->post('linktitle'),
                'url' => $this->input->post('url'),
                'createdate' => date('Y-m-d H:i:s'),
                'publish' => $this->input->post('publish'),
                'ordering' => $this->input->post('ordering'),
                'target' => $this->input->post('target'),
                'type' => $this->input->post('type'),
                'description' => $this->input->post('description'),
                'memberid' => $this->session->memberId,
                'lang' => $this->session->lang
            )
        );
        return $this->db->insert_batch($this->db->dbprefix . 'slider', $data);
    }

    public function deleteContent_model($contId) {
        foreach ($contId as $id) {
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'slider');
        }
        return array('status' => 'success', 'message' => 'Амжилттай');
    }

    public function contentList_model($modId, $catId) {
        if ($catId != '') {
            $this->db->where('catid', $catId);
        }
        $this->db->where('modid', $modId);
        $this->db->where('parentid', 0);
        $this->db->where('lang', $this->session->lang);
        $this->db->order_by('ordering', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'slider');
        $result = $query->result();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $row = (array) $value;
                array_push($data, $row);
            }
        }
        return $data;
    }

    public function getOrdering() {
        $this->db->where('lang', $this->session->lang);
        $this->db->order_by('ordering', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'slider');
        $result = $query->result();
        if (count($result) > 0) {
            $row = (array) $result['0'];
            return $row['ordering'] + 1;
        } else {
            return 1;
        }
    }

    public function uploadPhotos_model() {

        $config['upload_path'] = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH;
        $config['allowed_types'] = 'gif|jpg|png|jpeg';
        $config['max_size'] = 10000000;
        $config['max_width'] = 2048;
        $config['max_height'] = 2048;
        $config['remove_spaces'] = true;
        (Integer) $error = 0;
        //print_r($_FILES);
        $_FILES['picUpload']['name'] = $_FILES['picUpload']['name'];
        $_FILES['picUpload']['type'] = $_FILES['picUpload']['type'];
        $_FILES['picUpload']['tmp_name'] = $_FILES['picUpload']['tmp_name'];
        $_FILES['picUpload']['error'] = $_FILES['picUpload']['error'];
        $_FILES['picUpload']['size'] = $_FILES['picUpload']['size'];


        if (!empty($_FILES['pic']['name'])) {
            $this->load->library('upload', $config);
            if ($this->upload->do_upload('picUpload')) {
                $return = $this->upload->data();
                self::resizeImage($config['upload_path'] . $return['file_name'], SMALL_WIDTH, SMALL_HEIGHT, $config['upload_path'] . 's_' . $return['file_name']);
                self::resizeImage($config['upload_path'] . $return['file_name'], MEDIUM_WIDTH, MEDIUM_HEIGHT, $config['upload_path'] . 'm_' . $return['file_name']);
                self::resizeImage($config['upload_path'] . $return['file_name'], LARGE_WIDTH, LARGE_HEIGHT, $config['upload_path'] . 'l_' . $return['file_name']);
                unlink($config['upload_path'] . $return['file_name']);

                $data = array(array(
                        'modid' => $this->input->post('modId'),
                        'parentid' => $this->input->post('contId'),
                        'title' => $this->input->post('title'),
                        'linktitle' => $this->input->post('title'),
                        'introtext' => $this->input->post('title'),
                        'ordering' => self::getOrdering(),
                        'pic' => $return['file_name'],
                        'picshow' => 1,
                        'picshowinside' => 1,
                        'publish' => $this->input->post('publish'),
                        'memberid' => $this->session->memberId,
                        'lang' => $this->session->lang,
                        'createdate' => date('Y-m-d H:i:s'),
                        'publishdate' => date('Y-m-d H:i:s')
                ));
                $this->db->insert_batch($this->db->dbprefix . 'content', $data);

                $error += 0;
            } else {
                $error += 1;
            }
        }
    }

    public function resizeImage($filename, $newwidth, $newheight, $newFile) {
        list($width, $height) = getimagesize($filename);
        if ($width > $height && $newheight < $height) {
            $newheight = $height / ($width / $newwidth);
        } else if ($width < $height && $newwidth < $width) {
            $newwidth = $width / ($height / $newheight);
        } else {
            $newwidth = $width;
            $newheight = $height;
        }
        $thumb = imagecreatetruecolor($newwidth, $newheight);
        $source = imagecreatefromjpeg($filename);
        imagecopyresized($thumb, $source, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
        imagejpeg($thumb, $newFile);
    }

    public function contentPhotosList_model($contId, $modId) {
        $this->db->where('modid', $modId);
        $this->db->where('parentid', $contId);
        $this->db->where('lang', $this->session->lang);
        $this->db->order_by('ordering', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'content');
        $result = $query->result();
        $data = array();
        if (count($result) > 0) {
            foreach ($result as $key => $value) {
                $row = (array) $value;
                array_push($data, $row);
            }
        }
        return $data;
    }

    public function contentPhotosRemove_model($id, $pic) {
        $this->db->where('id', $id);
        $result = $this->db->delete($this->db->dbprefix . 'content');
        $sPic = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH . 's_' . $pic;
        $mPic = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH . 'm_' . $pic;
        $lPic = $_SERVER['DOCUMENT_ROOT'] . UPLOADS_CONTENT_PATH . 'l_' . $pic;
        if (is_file($sPic))
            unlink($sPic);
        if (is_file($mPic))
            unlink($mPic);
        if (is_file($lPic))
            unlink($lPic);
        return array('status' => 'success', 'message' => 'Амжилттай');
    }

    public function contentPhotosUpdate_model($id, $text, $ordering) {
        $this->db->where('id', $id);
        $data = array(
            'title' => $text,
            'linktitle' => $text,
            'title' => $text,
            'introtext' => $text,
            'ordering' => $ordering
        );

        if ($this->db->update($this->db->dbprefix . 'content', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function contentPhotosPublish_model($id, $publish) {
        $this->db->where('id', $id);
        $data = array('publish' => $publish);
        if ($this->db->update($this->db->dbprefix . 'content', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function contentGalleryShow_model($id, $showgallery) {
        $this->db->where('id', $id);
        $data = array('showgallery' => $showgallery);
        if ($this->db->update($this->db->dbprefix . 'content', $data)) {
            return array('status' => 'success', 'message' => 'Амжилттай');
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

    public function contentMarkerPublish_model($id, $showMarker) {
        $this->db->where('id', $id);
        $data = array('showmarker' => $showMarker);
        if ($this->db->update($this->db->dbprefix . 'content', $data)) {
            if ($showMarker == 1) {
                return array('status' => 'success', 'message' => 'Амжилттай нээгдлээ');
            } else {
                return array('status' => 'success', 'message' => 'Амжилттай хаалаа');
            }
        }
        return array('status' => 'error', 'message' => 'Алдаа гарлаа');
    }

}

?>