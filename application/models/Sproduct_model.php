<?php

class Sproduct_model extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
        $this->load->helper('string');
        $this->load->helper('date');
        $this->load->library('image_lib');
    }

    public function contentPublish_model($contId, $publish) {
        $this->db->where('id', $contId);
        $this->db->update($this->db->dbprefix . 'content', array('publish' => $publish));
    }

    public function contentUpdate_model($contId) {
        
        $data = array(
            'catid' => $this->input->post('catid'),
            'title' => $this->input->post('title'),
            'linktitle' => $this->input->post('linktitle'),
            'url' => $this->input->post('url'),
            'ordering' => $this->input->post('ordering'),
            'publish' => $this->input->post('publish'),
            'commentshow' => $this->input->post('commentshow'),
            'pic' => ($this->input->post('pic') != "" ? $this->input->post('pic') : $this->input->post('oldPic')),
            'picshow' => $this->input->post('picshow'),
            'picshowinside' => $this->input->post('picshowinside'),
            'showaccessories' => $this->input->post('showaccessories'),
            'showsocial' => $this->input->post('showsocial'),
            'showitemlayout' => $this->input->post('showitemlayout'),
            'introtext' => $this->input->post('introtext'),
            'fulltext' => $this->input->post('fulltext'),
            'metakey' => $this->input->post('metakey'),
            'pagetitle' => $this->input->post('pagetitle'),
            'metadesc' => $this->input->post('metadesc'),
            'h1text' => $this->input->post('h1text'),
            'organizationid' => $this->input->post('organizationid'),
            'price' => $this->input->post('price'),
            'priceonline' => $this->input->post('priceonline')
        );
        $this->db->where('id', $contId);
        $this->db->update($this->db->dbprefix . 'content', $data);
    }

    public function contentEdit_model($id) {

        $this->db->where('id', $id);
        $query = $this->db->get($this->db->dbprefix . 'content');
        $result = $query->result();

        if (count($result) > 0) {
            return $result = (array) $result[0];
        } else {

            return array(
                'id' => 0,
                'parentid' => 0,
                'modid' => 11,
                'catid' => 0,
                'pic' => '',
                'title' => '',
                'introtext' => '',
                'fulltext' => '',
                'pagetitle' => '',
                'metakey' => '',
                'metadesc' => '',
                'h1text' => '',
                'createdate' => date('Y-m-d H:i:s'),
                'publishdate' => date('Y-m-d H:i:s'),
                'memberid' => $this->session->memberId,
                'organizationid' => 0,
                'commentshow' => 1,
                'commentcount' => 0,
                'hits' => 0,
                'hitsreal' => 0,
                'publish' => 1,
                'ordering' => self::getOrdering(),
                'showsocial' => 1,
                'lang' => $this->session->lang,
                'price' => '',
                'priceonline' => '',
                'showvideo' => 0,
                'showgallery' => 0,
                'showfile' => 0,
                'param' => '',
                'paramtype' => 0,
            );
        }
    }

    public function contentInsert_model($modId) {

        $data = array(
            array(
                'modid' => $modId,
                'catid' => $this->input->post('catid'),
                'title' => $this->input->post('title'),
                'linktitle' => $this->input->post('title'),
                'ordering' => $this->input->post('ordering'),
                'commentshow' => $this->input->post('commentshow'),
                'pic' => $this->input->post('pic'),
                'picshow' => 1,
                'showsocial' => $this->input->post('showsocial'),
                'publish' => $this->input->post('publish'),
                'introtext' => $this->input->post('introtext'),
                'fulltext' => $this->input->post('fulltext'),
                'metakey' => $this->input->post('metakey'),
                'pagetitle' => $this->input->post('pagetitle'),
                'metadesc' => $this->input->post('metadesc'),
                'h1text' => $this->input->post('h1text'),
                'memberid' => $this->session->memberId,
                'lang' => $this->session->lang,
                'createdate' => date('Y-m-d H:i:s'),
                'publishdate' => date('Y-m-d H:i:s'),
                'organizationid' => $this->input->post('organizationid'),
                'price' => $this->input->post('price'),
                'priceonline' => $this->input->post('priceonline')
            )
        );
        return $this->db->insert_batch($this->db->dbprefix . 'content', $data);
    }

    public function deleteContent_model($contId, $upload_path = UPLOADS_CONTENT_PATH) {
        $upload_path = $_SERVER['DOCUMENT_ROOT'] . $upload_path;
        foreach ($contId as $id) {
            $small = "";
            $medium = "";
            $large = "";
            $this->db->where('id', $id);
            $query = $this->db->get($this->db->dbprefix . 'content');
            $result = $query->result();
            $data = array();
            if (count($result) > 0) {
                foreach ($result as $key => $value) {
                    $row = (array) $value;
                    array_push($data, $row);
                }
            }
            
            $small = $upload_path . CROP_SMALL . $data['0']['pic'];
            $medium = $upload_path . CROP_MEDIUM . $data['0']['pic'];
            $large = $upload_path . CROP_LARGE . $data['0']['pic'];
            if (is_file($small)) unlink($small);
            if (is_file($medium)) unlink($medium);
            if (is_file($large)) unlink($large);
            $this->db->where('id', $id);
            $this->db->delete($this->db->dbprefix . 'content');
            
        }
        return array('status' => 'success', 'message' => 'Амжилттай');
    }

    public function contentList_model($modId, $catId = '') {
        if ($catId != '') {
            $this->db->where('catid', $catId);
        }
        $this->db->where('modid', $modId);
        $this->db->where('parentid', 0);
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

    public function getOrdering() {
        $this->db->where('lang', $this->session->lang);
        $this->db->order_by('ordering', 'DESC');
        $query = $this->db->get($this->db->dbprefix . 'content');
        $result = $query->result();
        $row = (array) $result['0'];
        return $row['ordering'] + 1;
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
        $files = $_FILES;
        foreach ($files['picUpload']['name'] as $key => $value) {

            $_FILES['picUpload']['name'] = $files['picUpload']['name'][$key];
            $_FILES['picUpload']['type'] = $files['picUpload']['type'][$key];
            $_FILES['picUpload']['tmp_name'] = $files['picUpload']['tmp_name'][$key];
            $_FILES['picUpload']['error'] = $files['picUpload']['error'][$key];
            $_FILES['picUpload']['size'] = $files['picUpload']['size'][$key];


            if (!empty($_FILES['picUpload']['name'][$key])) {
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
                            'title' => $this->input->post('introtext'),
                            'linktitle' => $this->input->post('introtext'),
                            'ordering' => self::getOrdering(),
                            'pic' => $return['file_name'],
                            'picshow' => 1,
                            'picshowinside' => 1,
                            'publish' => $this->input->post('publish'),
                            'introtext' => $this->input->post('introtext'),
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
        if ($error > 0) {
            return FALSE;
        } else {
            return TRUE;
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

    public function contentListCombo_model($modId) {
        $this->db->where('modid', $modId);
        $this->db->where('parentid', 0);
        $this->db->where('publish', 1);
        $this->db->where('lang', $this->session->lang);
        $this->db->order_by('title', 'ASC');
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
}

?>