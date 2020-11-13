<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

if (!function_exists('getUserPermission')) {

    function getUserPermission($param = array('data' => array(), 'selectedId' => 0)) {

        if (is_array($param['data'])) {

            foreach ($param['data'] as $key => $row) {

                if ($row->id == $param['selectedId']) {
                    return $row;
                }
            }
        }
    }

}

if (!function_exists('systemMenuOneLevel')) {

    function systemMenuOneLevel($param = array('selectedId' => 0)) {

        if (is_null($param['selectedId'])) {
            $param['selectedId'] = 0;
        }
        $data = $menuSelected = $menuHtml = '';
        $ci = & get_instance();

        $data .= '<ul class="nav navbar-nav _system-nav">';
        $data .= '<li ' . ($param['selectedId'] == 0 ? ' class="active" ' : '') . '><a href="dashboard"><i class="icon-display4 position-left"></i> Эхлэл</a></li>';
        $query = $ci->db->query('
                SELECT 
                    MM.id,
                    MM.parent_id,
                    MM.mod_id,
                    MM.title,
                    MM.column_count,
                    MM.icon,
                    MM.menu_class,
                    MM.menu_css,
                    MM.menu_type_id,
                    M.path
                FROM ' . $ci->db->dbprefix . 'module_menu AS MM
                LEFT JOIN ' . $ci->db->dbprefix . 'module AS M ON MM.mod_id = M.id
                WHERE MM.is_active = 1 AND MM.parent_id = 0');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {
                $modPermission = getUserPermission(array('data' => $ci->session->authentication, 'selectedId' => $row->id));

                if ($modPermission != null and $modPermission->isModule == 1) {
                    $data .= '<li class="' . ($param['selectedId'] == $row->id ? 'active' : '') . '">';
                    $data .= '<a href="' . $row->path . '/index/' . $row->id . '" class="dropdown-toggle" data-toggle="dropdown">';
                    $data .= '<i class="' . $row->icon . ' position-left"></i> ' . $row->title . '</span>';
                    $data .= '</a>';
                    $data .= '</li>';
                } else {
                    
                }
            }
        }
        $data .= '</ul>';

        return $data;
    }

}

if (!function_exists('systemVerticalAppMenu')) {

    function systemVerticalAppMenu($param = array('selectedId' => 0, 'parentId' => 0, 'menuClass' => '')) {

        $data = $menuSelected = $menuHtml = '';
        $ci = & get_instance();

        if (is_null($param['selectedId'])) {
            $param['selectedId'] = 0;
        }

        $data .= '<ul class="' . $param['class'] . '">';

        $query = $ci->db->query('
                SELECT 
                    MM.id,
                    MM.parent_id,
                    MM.mod_id,
                    MM.title,
                    MM.column_count,
                    MM.icon,
                    MM.menu_class,
                    MM.menu_css,
                    MM.menu_type_id,
                    M.path
                FROM ' . $ci->db->dbprefix . 'module_menu AS MM
                LEFT JOIN ' . $ci->db->dbprefix . 'module AS M ON MM.mod_id = M.id
                WHERE MM.is_active = 1 AND MM.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            foreach ($query->result() as $key => $row) {

                $modPermission = getUserPermission(array('data' => $ci->session->authentication, 'selectedId' => $row->id));

                if ($modPermission != null and $modPermission->isModule == 1) {
                    if (isChildAppMenu(array('parentId' => $row->id))) {
                        $data .= '<li class="dropdown-submenu ' . ($param['selectedId'] == $row->id ? 'active' : '') . '">';
                        $data .= '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown">' . $row->title . '</a>';
                        $data .= systemVerticalAppMenu(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'class' => 'hidden-ul'));
                        $data .= '</li>';
                    } else {
                        if ($row->menu_type_id == 1) {
                            $row->path = 'javascript:;';
                        } else if ($row->menu_type_id == 2) {
                            $row->path = $row->path . '/index/' . $row->id;
                        } else if ($row->menu_type_id == 3) {
                            $row->path = 'scategory/index/' . $row->id;
                        } else if ($row->menu_type_id == 4) {
                            $row->path = 'sreport/index/' . $row->id;
                        }
                        $data .= '<li  class="' . ($param['selectedId'] == $row->id ? 'active' : '') . '"><a href="' . $row->path . '">' . $row->title . '</a></li>';
                    }
                }
            }
        }
        $data .= '</ul>';

        return $data;
    }

}

if (!function_exists('systemRootGetMenuId')) {

    function systemRootGetMenuId($param = array('selectedId' => 0)) {

        $ci = & get_instance();
        if ($param['selectedId'] != null) {
            $query = $ci->db->query('
                SELECT 
                    id,
                    parent_id
                FROM ' . $ci->db->dbprefix . 'module_menu WHERE is_active = 1 AND id = ' . $param['selectedId']);

            if ($query->num_rows() > 0) {

                $row = $query->row();

                if ($row->parent_id == 0) {

                    return $row->id;
                } else if ($row->parent_id != 0) {

                    return systemRootGetMenuId($param = array('selectedId' => $row->parent_id));
                }
            }
        }
        return 0;
    }

}

if (!function_exists('systemMenu')) {

    function systemMenu($param = array('selectedId' => 0)) {

        if (is_null($param['selectedId'])) {
            $param['selectedId'] = 0;
        }
        $data = $menuSelected = $menuHtml = '';
        $ci = & get_instance();

        $data .= '<ul class="navbar-nav">';

        $data .= '<li class="nav-item"><a class="navbar-nav-link ' . ($param['selectedId'] == 0 ? 'active' : '') . '" href="dashboard"><i class="icon-home4 mr-2"></i> Эхлэл</a></li>';
        $query = $ci->db->query('
                SELECT 
                    MM.id,
                    MM.parent_id,
                    MM.mod_id,
                    MM.cat_id,
                    MM.cont_id,
                    MM.title,
                    MM.column_count,
                    MM.icon,
                    MM.menu_class,
                    MM.menu_css,
                    MM.menu_type_id,
                    M.path,
                    M.path_list,
                    M.path_item
                FROM ' . $ci->db->dbprefix . 'module_menu AS MM
                LEFT JOIN ' . $ci->db->dbprefix . 'module AS M ON MM.mod_id = M.id
                WHERE MM.is_active = 1 AND MM.parent_id = 0 ORDER BY MM.order_num ASC');

        if ($query->num_rows() > 0) {
            foreach ($query->result() as $key => $row) {

                $auth = authentication(array(
                    'permission' => $ci->session->authentication,
                    'role' => 'isModule',
                    'moduleMenuId' => $row->id,
                    'createdUserId' => 0,
                    'currentUserId' => 0));

                if ($auth->permission) {

                    $isChildAppMenu = isChildAppMenu(array('parentId' => $row->id));

                    if ($isChildAppMenu) {
                        if ($row->column_count == 1 or $row->column_count == 0) {
                            $data .= '<li class="nav-item dropdown ' . ($param['selectedId'] == $row->id ? 'active' : '') . '">';
                            $data .= '<a href="javascript:;" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown" aria-expanded="false">';
                            $data .= '<i class="' . $row->icon . ' mr-2"></i> ' . $row->title . '<span class="caret"></span>';
                            $data .= '</a>';
                            $data .= childAppMenu(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'menuClass' => $row->menu_class));
                            $data .= '</li>';
                        } else {
                            $data .= '<li class="nav-item nav-item-levels mega-menu-full">';
                            $data .= '<a href="javascript:;" class="navbar-nav-link dropdown-toggle ' . ($param['selectedId'] == $row->id ? 'active' : '') . '" data-toggle="dropdown" aria-expanded="true">';
                            $data .= '<i class="' . $row->icon . ' mr-2"></i> ' . $row->title . '<span class="caret"></span>';
                            $data .= '</a>';
                            $data .= childAppMenuWide(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'columnCount' => $row->column_count));
                            $data .= '</li>';
                        }
                    } else {

                        if ($row->menu_type_id == 1) {
                            $row->path = 'javascript:;';
                        } else if ($row->menu_type_id == 2) {
                            $row->path = $row->path . '/index/' . $row->id;
                        } else if ($row->menu_type_id == 3) {
                            $row->path = 'scategory/index/' . $row->id;
                        } else if ($row->menu_type_id == 4) {
                            $row->path = 'sreport/index/' . $row->id;
                        } else if ($row->menu_type_id == 5) {

                            if ($row->cat_id != 0 and $row->cont_id == 0) {

                                $row->path = $row->path . '/' . $row->path_list . '/' . $row->id . '/' . $row->cat_id;
                            } else if ($row->cat_id != 0 and $row->cont_id != 0) {

                                $row->path = $row->path . '/' . $row->path_item . '/' . $row->id . '/' . $row->cont_id;
                            } else {

                                $row->path = 'javascript:;';
                            }
                        }


                        $data .= '<li class="nav-item"><a href="' . $row->path . '" class="navbar-nav-link ' . ($param['selectedId'] == $row->id ? 'active' : '') . '"><i class="' . $row->icon . ' position-left"></i> ' . $row->title . '</a></li>';
                    }
                }
            }
        }
        $data .= '</ul>';

        return $data;
    }

}

if (!function_exists('childAppMenu')) {

    function childAppMenu($param = array('selectedId' => 0, 'parentId' => 0, 'menuClass' => '')) {
        $data = $menuSelected = $menuHtml = '';
        $ci = & get_instance();

        $query = $ci->db->query('
                SELECT 
                    MM.id,
                    MM.parent_id,
                    MM.mod_id,
                    MM.cat_id,
                    MM.cont_id,
                    MM.title,
                    MM.column_count,
                    MM.icon,
                    MM.menu_class,
                    MM.menu_css,
                    MM.menu_type_id,
                    M.path,
                    M.path_list,
                    M.path_item
                FROM ' . $ci->db->dbprefix . 'module_menu AS MM
                LEFT JOIN ' . $ci->db->dbprefix . 'module AS M ON MM.mod_id = M.id
                WHERE MM.is_active = 1 AND MM.parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {

            $data .= '<div class="dropdown-menu">';

            foreach ($query->result() as $key => $row) {

                $modPermission = getUserPermission(array('data' => $ci->session->authentication, 'selectedId' => $row->id));

                if ($modPermission != null and $modPermission->isModule == 1) {
                    if (isChildAppMenu(array('parentId' => $row->id))) {
                        $data .= '<div class="dropdown-submenu">';
                        $data .= '<a href="javascript:;" class="dropdown-item dropdown-toggle ' . ($param['selectedId'] == $row->id ? 'active' : '') . '" data-toggle="dropdown"><i class="' . $row->icon . '"></i> ' . $row->title . '</a>';
                        $data .= childAppMenu(array('selectedId' => $param['selectedId'], 'parentId' => $row->id, 'menuClass' => $row->menu_class));
                        $data .= '</div>';
                    } else {
                        if ($row->menu_type_id == 1) {
                            $row->path = 'javascript:;';
                        } else if ($row->menu_type_id == 2) {
                            $row->path = $row->path . '/index/' . $row->id;
                        } else if ($row->menu_type_id == 3) {
                            $row->path = 'scategory/index/' . $row->id;
                        } else if ($row->menu_type_id == 4) {
                            $row->path = 'sreport/index/' . $row->id;
                        } else if ($row->menu_type_id == 5) {

                            if ($row->cat_id != 0 and $row->cont_id == 0) {

                                $row->path = $row->path . '/' . $row->path_list . '/' . $row->id . '/' . $row->cat_id;
                            } else if ($row->cat_id != 0 and $row->cont_id != 0) {

                                $row->path = $row->path . '/' . $row->path_item . '/' . $row->id . '/' . $row->cont_id;
                            } else {

                                $row->path = 'javascript:;';
                            }
                        }

                        $data .= '<a href="' . $row->path . '" class="dropdown-item ' . ($param['selectedId'] == $row->id ? 'active' : '') . '"><i class="' . $row->icon . ' position-left"></i> ' . $row->title . '</a>';
                    }
                }
            }

            $data .= '</div>';
        }

        return $data;
    }

}

if (!function_exists('childAppMenuWide')) {

    function childAppMenuWide($param = array('selectedId' => 0, 'parentId' => 0, 'columnCount' => 0)) {

        $data = $menuSelected = $menuHtml = $columnClass = '';
        $ci = & get_instance();

        switch ($param['columnCount']) {
            case '2': {
                    $columnClass = 'col-md-6';
                };
                break;
            case '3': {
                    $columnClass = 'col-md-4';
                };
                break;
            case '4': {
                    $columnClass = 'col-md-3';
                };
                break;
            case '6': {
                    $columnClass = 'col-md-2';
                };
                break;
            default : {
                    $columnClass = 'col-md-6';
                }
        }

        $query = $ci->db->query('
                SELECT 
                    MM.id,
                    MM.parent_id,
                    MM.mod_id,
                    MM.title,
                    MM.column_count,
                    MM.icon,
                    MM.menu_type_id,
                    M.path
                FROM ' . $ci->db->dbprefix . 'module_menu AS MM
                LEFT JOIN ' . $ci->db->dbprefix . 'module AS M ON MM.mod_id = M.id
                WHERE MM.is_active = 1 AND MM.parent_id = ' . $param['parentId']);

        $numRows = $query->num_rows();

        $rowNum = 5;

        if ($numRows > 20 and $numRows < 80) {
            $rowNum = 12;
        } else if ($numRows > 80 and $numRows < 100) {
            $rowNum = 13;
        }

        if ($query->num_rows() > 0) {

            $data .= '<div class="dropdown-menu dropdown-content">';
            $data .= '<div class="dropdown-content-body">';
            $data .= '<div class="row">';

            $i = 1;
            foreach ($query->result() as $key => $row) {

                $modPermission = getUserPermission(array('data' => $ci->session->authentication, 'selectedId' => $row->id));

                if ($i == 1) {
                    $data .= '<div class="col-md-3">'; //' . $columnClass . '
                    $data .= '<div class="dropdown-item-group mb-3 mb-md-0">';
                    $data .= '<ul class="list-unstyled">';
                }

                if ($modPermission != null and $modPermission->isModule == 1) {
                    $data .= '<li><a href="' . ($row->menu_type_id == 2 ? $row->path : 'scategory') . '/index/' . $row->id . '" class="dropdown-item rounded ' . ($row->id == $param['selectedId'] ? 'active' : '') . '"><i class="' . $row->icon . '"></i> ' . $row->title . '</a></li>';
                }

                if ($i == $rowNum or $query->num_rows() == $i) {
                    $data .= '</ul>';
                    $data .= '</div>';
                    $data .= '</div>';
                    $i = 0;
                }
                $i++;
            }

            $data .= '</div>';
            $data .= '</div>';
            $data .= '</div>';
        }


        return $data;
    }

}

if (!function_exists('isChildAppMenu')) {

    function isChildAppMenu($param = array('parentId' => 0)) {

        $ci = & get_instance();

        $query = $ci->db->query('
                SELECT 
                    id
                FROM ' . $ci->db->dbprefix . 'module_menu WHERE is_active = 1 AND parent_id = ' . $param['parentId']);

        if ($query->num_rows() > 0) {
            return true;
        }

        return false;
    }

}