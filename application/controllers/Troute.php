<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Troute extends CI_Controller {

    public static $path = "troute/";
    public $theme = array();

    function __construct() {

        parent::__construct();

        $this->load->model('Scategory_model', 'category');
        $this->load->model('ShrPeople_model', 'hrPeople');
        $this->load->model('Troute_model', 'troute');
        $this->load->model('Tgeneral_model', 'tgeneral');
        $this->load->model('Tlanguage_model', 'tlanguage');
        $this->load->model('Tmenu_model', 'tmenu');
        $this->load->model('Tmedia_model', 'tmedia');
        $this->load->model('Tcontact_model', 'tcontact');
        $this->load->model('Tnews_model', 'tnews');
        $this->load->model('Tmap_model', 'tmap');
        $this->load->model('Tlayout_model', 'tlayout');
        $this->load->model('TcurrencyRate_model', 'tcurrencyRate');
        

        $this->code = $this->uri->segment(1);
        $this->codeLength = strlen($this->code);
        $this->mainMenuLocId = 17;
        $this->footerMenuLocId = 256;
        $this->salesMenuLocId = 398;


        $this->banner1CatId = 54;
        $this->banner2CatId = 54;
        $this->banner3CatId = 236;
        $this->banner4CatId = 54;
        $this->banner5CatId = 54;
        $this->banner6CatId = 54;
        $this->contactId = 1;
        $this->contactMarketId = 3;

    }

    public function manage() {

        if ($this->codeLength > 0 and $this->codeLength == 2) {

            $this->tlanguage->setThemeSession_model(array('code' => $this->code, 'path' => $this->code));
            $this->theme['urlInfo'] = $this->troute->getUrlInfo_model(array('url' => $this->uri->segment(2)));
            $this->contactId = 2;
        } else {

            //default language
            $this->tlanguage->setThemeSession_model(array('code' => 'mn', 'path' => $this->uri->segment(2)));
            $this->theme['urlInfo'] = $this->troute->getUrlInfo_model(array('url' => $this->uri->segment(1)));
        }

        $this->lang->load('theme', $this->session->userdata['themeLanguage']['folderPath']);
        $this->theme['meta'] = $this->tgeneral->getGeneralInfo_model(array('langId' => $this->session->userdata['themeLanguage']['id']));

        if ($this->session->userdata['themeLanguage']['code'] == 'en') {
            $this->mainMenuLocId = 551;
            $this->homeSliderCatId = 557;
            $this->contactId = 2;
            $this->owlSliderPartnerCatId = 558;
            $this->bookingContId = 325;
        }

        if ($this->theme['urlInfo']->mod_id == 0 and $this->theme['urlInfo']->cont_id == 0) {

            $this->_myControllerHome(array('theme' => $this->theme));
        } else if ($this->theme['urlInfo']->mod_id == 1 and $this->theme['urlInfo']->cont_id != 0) {
            //Меню дээр дархад шууд item дуудах үед

            $this->theme['menuInfo'] = $this->troute->getMenuInfo_model(array('id' => $this->theme['urlInfo']->cont_id));

            $this->theme['selectedMenu'] = $this->tmenu->getSelectedMenu_model(array('selectedId' => $this->theme['menuInfo']->id));
            $this->theme['category'] = $this->category->getData_model(array('selectedId' => $this->theme['menuInfo']->cat_id));

            if ($this->theme['menuInfo']->mod_id == 0 and $this->theme['urlInfo']->url == 'tour-calendar') {

                $this->theme['row'] = $this->tnews->getItem_model(array('contId' => $this->bookingContId));

                $this->_myControllerTourCalendar(array('theme' => $this->theme));
            } else if ($this->theme['menuInfo']->mod_id == 0 and $this->theme['urlInfo']->url == 'booking') {

                $this->theme['row'] = $this->tnews->getItem_model(array('contId' => $this->bookingContId));

                $this->_myControllerBookingItem(array('theme' => $this->theme));
            } else if ($this->theme['menuInfo']->mod_id == 12) {

                if ($this->theme['menuInfo']->cat_id != 0 and $this->theme['menuInfo']->cont_id == 0) {

                    $this->_myControllerContactList(array('theme' => $this->theme));
                } else if ($this->theme['menuInfo']->cat_id != 0 and $this->theme['menuInfo']->cont_id != 0) {

                    $this->theme['row'] = $this->tcontact->getItem_model(array('selectedId' => $this->theme['menuInfo']->cont_id));
                    $this->theme['menuInfo'] = $this->troute->getMenuInfo_model(array('contId' => $this->theme['row']->id));
                    $this->theme['selectedMenu'] = $this->tmenu->getSelectedMenu_model(array('selectedId' => $this->theme['menuInfo']->id));

                    $this->_myControllerContactItem(array('theme' => $this->theme));
                }
            } else if ($this->theme['menuInfo']->mod_id == 2) {

                if ($this->theme['menuInfo']->cat_id != 0 and $this->theme['menuInfo']->cont_id == 0) {

                    $this->_myControllerContentList(array('theme' => $this->theme));
                } else if ($this->theme['menuInfo']->cat_id != 0 and $this->theme['menuInfo']->cont_id != 0) {

                    $this->theme['row'] = $this->tnews->getItem_model(array('contId' => $this->theme['menuInfo']->cont_id));

                    $this->_myControllerContentItem(array('theme' => $this->theme));
                }
            } else if ($this->theme['menuInfo']->mod_id == 19) {

                if ($this->theme['menuInfo']->cat_id != 0 and $this->theme['menuInfo']->cont_id == 0) {

                    $this->_myControllerTourList(array('theme' => $this->theme));
                } else if ($this->theme['menuInfo']->cat_id != 0 and $this->theme['menuInfo']->cont_id != 0) {

                    $this->theme['row'] = $this->tcontact->getItem_model(array('selectedId' => $this->theme['menuInfo']->cont_id));
                    $this->theme['menuInfo'] = $this->troute->getMenuInfo_model(array('contId' => $this->theme['row']->id));
                    $this->theme['selectedMenu'] = $this->tmenu->getSelectedMenu_model(array('selectedId' => $this->theme['menuInfo']->id));

                    $this->_myControllerContactItem(array('theme' => $this->theme));
                }
            }
        } else if ($this->theme['urlInfo']->mod_id == 2 and $this->theme['urlInfo']->cont_id != 0) {

            $this->theme['row'] = $this->tnews->getItem_model(array('contId' => $this->theme['urlInfo']->cont_id));
            $this->theme['menuInfo'] = $this->troute->getMenuInfo_model(array('catId' => $this->theme['row']->cat_id));

            if ($this->theme['menuInfo']) {
                $this->theme['selectedMenu'] = $this->tmenu->getSelectedMenu_model(array('selectedId' => $this->theme['menuInfo']->id));
            } else {
                $this->theme['selectedMenu'] = $this->tmenu->getSelectedMenu_model(array('selectedId' => 0));
            }

            $this->_myControllerContentItem(array('theme' => $this->theme));
        } else if ($this->theme['urlInfo']->mod_id == 19 and $this->theme['urlInfo']->cont_id != 0) {

            $this->theme['row'] = $this->ttour->getItem_model(array('contId' => $this->theme['urlInfo']->cont_id));
            $this->theme['menuInfo'] = $this->troute->getMenuInfo_model(array('catId' => $this->theme['row']->cat_id));


            if ($this->theme['menuInfo']) {
                $this->theme['selectedMenu'] = $this->tmenu->getSelectedMenu_model(array('selectedId' => $this->theme['menuInfo']->id));
            } else {
                $this->theme['selectedMenu'] = $this->tmenu->getSelectedMenu_model(array('selectedId' => 0));
            }

            $this->_myControllerTourItem(array('theme' => $this->theme));
        }
    }

    public function _myControllerContactItem($param = array()) {

        $body['theme'] = $param['theme'];
        $body['row'] = $param['theme']['row'];
        $body['row']->media = $this->tnews->getItemMedia_model(array('modId' => $body['row']->mod_id, 'contId' => $body['row']->id));
        $body['map'] = $this->tmap->getCoordinateInfo_model(array('modId' => $body['row']->mod_id, 'contId' => $body['row']->id));

        $header['mainMenu'] = $this->tmenu->mainMenu_model(array('locId' => $this->mainMenuLocId, 'selectedId' => 44, 'isHome' => 1));
        $footer['footerMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->mainMenuLocId, 'parentId' => 0, 'selectedId' => 0, 'class' => '_theme-footer-menu'));
        $body['contact'] = $this->tcontact->getData_model(array('selectedId' => $this->contactId));
        $header['contact'] = $body['contact'];
        $footer['contact'] = $body['contact'];
        $body['contactMarket'] = $this->tcontact->getData_model(array('selectedId' => $this->contactMarketId));

        $body['pageHeader'] = $this->tmedia->pageHeader_model(array('catId' => 550));
        $footer['partialFooterAbout'] = $this->tnews->partial_model(array('partialId' => 4, 'limit' => 1));
        $footer['owlSliderPartner'] = $this->tmedia->footerPartner_model(array('catId' => $this->owlSliderPartnerCatId));

        //Хуудас нээсэн тоог нэмэгдүүлж байгаа
        clickCount(array('table' => 'contact', 'id' => $body['row']->id));

        $header['page']['h1Text'] = $body['row']->h1_text;
        $header['page']['pageTitle'] = $body['row']->page_title;
        $header['page']['metaKey'] = $body['row']->meta_key;
        $header['page']['metaDesc'] = $body['row']->meta_desc;
        $header['page']['metaAuthor'] = $body['row']->full_name;
        $header['page']['ogLocale'] = '';
        $header['page']['ogTitle'] = $body['row']->page_title;
        $header['page']['ogDescription'] = $body['row']->meta_desc;
        $header['page']['ogUrl'] = base_url($_SERVER['REQUEST_URI']);
        $header['page']['ogImage'] = base_url(UPLOADS_CONTENT_PATH . $body['row']->pic);
        $header['page']['ogSiteName'] = '';
        $header['page']['logo'] = base_url('assets/' . DEFAULT_THEME . 'images/logo.png');

        $this->load->view(DEFAULT_THEME . 'header', $header);
        $this->load->view(DEFAULT_THEME . 'contact/' . $body['row']->theme, $body);
        $this->load->view(DEFAULT_THEME . 'footer', $footer);
    }

    public function _myControllerContentItem($param = array()) {

        $header['lastUpdateDate'] = $this->tnews->lastUpdateDate_model();
        
        $body['theme'] = $param['theme'];
        $body['row'] = $param['theme']['row'];
        $body['media'] = $this->tnews->getItemMedia_model(array('modId' => $body['row']->mod_id, 'contId' => $body['row']->id));
        $body['rowMediaPhoto'] = $this->tnews->getItemMedia_model(array('modId' => $body['row']->mod_id, 'contId' => $body['row']->id, 'mediaTypeId' => 1));
        $body['rowMediaFile'] = $this->tnews->getItemMedia_model(array('modId' => $body['row']->mod_id, 'contId' => $body['row']->id, 'mediaTypeId' => 4));
        
        $header['page']['h1Text'] = $body['row']->h1_text;
        $header['page']['pageTitle'] = $body['row']->page_title;
        $header['page']['metaKey'] = $body['row']->meta_key;
        $header['page']['metaDesc'] = $body['row']->meta_desc;
        $header['page']['metaAuthor'] = $body['row']->full_name;
        $header['page']['ogLocale'] = '';
        $header['page']['ogTitle'] = $body['row']->page_title;
        $header['page']['ogDescription'] = $body['row']->meta_desc;
        $header['page']['ogUrl'] = base_url($_SERVER['REQUEST_URI']);
        $header['page']['ogImage'] = base_url(UPLOADS_CONTENT_PATH . $body['row']->pic);
        $header['page']['ogSiteName'] = '';
        $header['page']['logo'] = base_url('assets/' . DEFAULT_THEME . 'images/logo.png');
        
        $header['mainMenu'] = $this->tmenu->mainMenu_model(array('locId' => $this->mainMenuLocId, 'selectedId' => $param['theme']['selectedMenu']->id));
        $footer['footerMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->footerMenuLocId, 'parentId' => 0, 'selectedId' => $param['theme']['selectedMenu']->id, 'class' => '_theme-footer-menu'));
        $footer['salesMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->salesMenuLocId, 'parentId' => 0, 'selectedId' => $param['theme']['selectedMenu']->id, 'class' => '_theme-footer-menu'));

        $body['contact'] = $this->tcontact->getData_model(array('selectedId' => $this->contactId));
        $header['contact'] = $body['contact'];
        $footer['contact'] = $body['contact'];
        $body['contactMarket'] = $this->tcontact->getData_model(array('selectedId' => $this->contactMarketId));
        
        $body['banner1'] = $this->tmedia->banner_model(array('catId' => $this->banner1CatId, 'position' => 'adver_b1'));
        $body['banner2'] = $this->tmedia->banner_model(array('catId' => $this->banner2CatId, 'position' => 'adver_b2'));
        $body['banner3'] = $this->tmedia->banner_model(array('catId' => $this->banner3CatId, 'position' => 'adver_b3'));
        $body['tabNewsListsDate'] = $this->tnews->tabNewsLists_model(array('sortType' => 'date', 'banner' => $body['banner2']));
        $body['tabNewsListsClick'] = $this->tnews->tabNewsLists_model(array('sortType' => 'click', 'banner' => $body['banner2']));
        $body['tabNewsListsComment'] = $this->tnews->tabNewsLists_model(array('sortType' => 'comment', 'banner' => $body['banner2']));

        $body['newsItemHorzintalGetKeywordLists'] = $this->tnews->newsItemHorzintalGetKeywordLists_model(array('keyword' => $body['row']->meta_key, 'sortType' => 'date', 'banner' => $body['banner3']));
        


        //Хуудас нээсэн тоог нэмэгдүүлж байгаа
        clickCount(array('table' => 'content', 'id' => $body['row']->id));

        $this->load->view(DEFAULT_THEME . 'header', $header);
        $this->load->view(DEFAULT_THEME . 'news/' . $body['row']->theme, $body);
        $this->load->view(DEFAULT_THEME . 'footer', $footer);
    }

    public function _myControllerContentList($param = array()) {

        $header['lastUpdateDate'] = $this->tnews->lastUpdateDate_model();
        $header['mainMenu'] = $this->tmenu->mainMenu_model(array('locId' => $this->mainMenuLocId, 'selectedId' => $param['theme']['selectedMenu']->id));
        $footer['footerMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->footerMenuLocId, 'parentId' => 0, 'selectedId' => $param['theme']['selectedMenu']->id, 'class' => '_theme-footer-menu'));
        $footer['salesMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->salesMenuLocId, 'parentId' => 0, 'selectedId' => $param['theme']['selectedMenu']->id, 'class' => '_theme-footer-menu'));

        $body['contact'] = $this->tcontact->getData_model(array('selectedId' => $this->contactId));
        $header['contact'] = $body['contact'];
        $footer['contact'] = $body['contact'];
        $body['contactMarket'] = $this->tcontact->getData_model(array('selectedId' => $this->contactMarketId));

        $body['category'] = $this->category->getData_model(array('selectedId' => $param['theme']['selectedMenu']->cat_id));

        $body['pageHeader'] = $this->tmedia->pageHeader_model(array('catId' => 550));

        $body['banner1'] = $this->tmedia->banner_model(array('catId' => $this->banner1CatId, 'position' => 'adver_b1'));
        $body['banner2'] = $this->tmedia->banner_model(array('catId' => $this->banner2CatId, 'position' => 'adver_b2'));
        $body['tabNewsListsDate'] = $this->tnews->tabNewsLists_model(array('sortType' => 'date', 'banner' => $body['banner2']));
        $body['tabNewsListsClick'] = $this->tnews->tabNewsLists_model(array('sortType' => 'click', 'banner' => $body['banner2']));
        $body['tabNewsListsComment'] = $this->tnews->tabNewsLists_model(array('sortType' => 'comment', 'banner' => $body['banner2']));
        
        
        $config["base_url"] = base_url($this->session->userdata['themeLanguage']['path'] . '/' . $param['theme']['selectedMenu']->url);
        $config["total_rows"] = $this->tnews->listsCount_model(array('catId' => $param['theme']['selectedMenu']->cat_id));

        $page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 0;
        $config["per_page"] = THEME_PAGINATION_PER_PAGE;
        $config["uri_segment"] = 4;

        $config['full_tag_open'] = '<ul class="pagination pagination-flat align-self-center">';
        $config['full_tag_close'] = '</ul>';
        $config['num_links'] = THEME_PAGINATION_NUM_LINKS;
        $config['page_query_string'] = TRUE;
        $config['prev_link'] = '&lt; Өмнөх';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = 'Дараах &gt;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = '<li class="page-item active"><a href="javascript:;" class="page-link">';
        $config["cur_page"] = $page;
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';
        $config['attributes'] = array('class' => 'page-link');
        $config['first_link'] = FALSE;
        $config['last_link'] = FALSE;

        $this->pagination->initialize($config);

        $body['result'] = $this->tnews->lists_model(array(
            'catId' => $param['theme']['selectedMenu']->cat_id,
            'selectedMenu' => $param['theme']['selectedMenu'],
            'limit' => $config["per_page"],
            'page' => $page));

        $body['pagination'] = $this->pagination->create_links();

        $header['page'] = $this->theme['meta'];
        $this->load->view(DEFAULT_THEME . 'header', $header);
        $this->load->view(DEFAULT_THEME . 'news/' . $param['theme']['category']->theme, $body);
        $this->load->view(DEFAULT_THEME . 'footer', $footer);
    }

    public function _myControllerHome($param = array()) {

        $header['lastUpdateDate'] = $this->tnews->lastUpdateDate_model();
        $header['mainMenu'] = $this->tmenu->mainMenu_model(array('locId' => $this->mainMenuLocId));
        $footer['footerMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->footerMenuLocId, 'parentId' => 0, 'selectedId' => 0, 'class' => '_theme-footer-menu'));
        $footer['salesMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->salesMenuLocId, 'parentId' => 0, 'selectedId' => 0, 'class' => '_theme-footer-menu'));

        $body['contact'] = $this->tcontact->getData_model(array('selectedId' => $this->contactId));
        $header['contact'] = $body['contact'];
        $footer['contact'] = $body['contact'];
        $body['contactMarket'] = $this->tcontact->getData_model(array('selectedId' => $this->contactMarketId));
        
        $body['banner1'] = $this->tmedia->banner_model(array('catId' => $this->banner1CatId, 'position' => 'adver_b1'));
        $body['banner2'] = $this->tmedia->banner_model(array('catId' => $this->banner2CatId, 'position' => 'adver_b2'));
        $body['banner3'] = $this->tmedia->banner_model(array('catId' => $this->banner3CatId, 'position' => 'adver_b3'));
        $body['tabNewsListsDate'] = $this->tnews->tabNewsLists_model(array('sortType' => 'date', 'banner' => $body['banner2']));
        $body['tabNewsListsClick'] = $this->tnews->tabNewsLists_model(array('sortType' => 'click', 'banner' => $body['banner2']));
        $body['tabNewsListsComment'] = $this->tnews->tabNewsLists_model(array('sortType' => 'comment', 'banner' => $body['banner2']));
        $body['topInterview'] = $this->tnews->tabNewsLists_model(array('sortType' => 'date', 'catId' => '394, 532', 'banner' => $body['banner3']));
        $body['newsHorzintalLists'] = $this->tnews->newsHorzintalLists_model(array('catId' => '394, 532'));

        $body['topNewsOne'] = $this->tlayout->layoutData_model(array('layoutId' => 9));
        
        $body['topNewsFour'] = $this->tlayout->layoutData_model(array('layoutId' => 10, 'limit' => 4));
        
        $header['page'] = $param['theme']['meta'];

        $this->load->view(DEFAULT_THEME . 'header', $header);
        $this->load->view(DEFAULT_THEME . 'home/index', $body);
        $this->load->view(DEFAULT_THEME . 'footer', $footer);
    }
    
    public function _myControllerCurrencyRate($param = array()) {

        $header['mainMenu'] = $this->tmenu->mainMenu_model(array('locId' => $this->mainMenuLocId, 'selectedId' => $param['theme']['selectedMenu']->id));
        $footer['footerMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->footerMenuLocId, 'parentId' => 0, 'selectedId' => $param['theme']['selectedMenu']->id, 'class' => '_theme-footer-menu'));
        $footer['salesMenu'] = $this->tmenu->footerMenu_model(array('locId' => $this->salesMenuLocId, 'parentId' => 0, 'selectedId' => $param['theme']['selectedMenu']->id, 'class' => '_theme-footer-menu'));

        $body['contact'] = $this->tcontact->getData_model(array('selectedId' => $this->contactId));
        $header['contact'] = $body['contact'];
        $footer['contact'] = $body['contact'];
        $body['contactMarket'] = $this->tcontact->getData_model(array('selectedId' => $this->contactMarketId));
        
        $body['banner1'] = $this->tmedia->banner_model(array('catId' => $this->banner1CatId, 'position' => 'adver_b1'));
        $body['banner2'] = $this->tmedia->banner_model(array('catId' => $this->banner2CatId, 'position' => 'adver_b2'));
        $body['banner3'] = $this->tmedia->banner_model(array('catId' => $this->banner3CatId, 'position' => 'adver_b3'));
        $body['tabNewsListsDate'] = $this->tnews->tabNewsLists_model(array('sortType' => 'date', 'banner' => $body['banner2']));
        $body['tabNewsListsClick'] = $this->tnews->tabNewsLists_model(array('sortType' => 'click', 'banner' => $body['banner2']));
        $body['tabNewsListsComment'] = $this->tnews->tabNewsLists_model(array('sortType' => 'comment', 'banner' => $body['banner2']));
        $body['topInterview'] = $this->tnews->tabNewsLists_model(array('sortType' => 'date', 'catId' => 532, 'banner' => $body['banner3']));
        $body['newsHorzintalLists'] = $this->tnews->newsHorzintalLists_model(array('catId' => 525));

        $body['topNewsOne'] = $this->tlayout->layoutData_model(array('layoutId' => 9));
        
        $body['topNewsFour'] = $this->tlayout->layoutData_model(array('layoutId' => 10, 'limit' => 4));
        
        $header['page'] = $param['theme']['meta'];

        $this->load->view(DEFAULT_THEME . 'header', $header);
        $this->load->view(DEFAULT_THEME . 'currencyRate/index', $body);
        $this->load->view(DEFAULT_THEME . 'footer', $footer);
    }

}
