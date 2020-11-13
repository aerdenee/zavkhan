<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tassets extends CI_Controller {

    public function minifyCss() {
        $cssFiles = array(
            "assets/system/fonts/opensans/opensans.css",
            "assets/system/fonts/roboto/roboto.css",
            "assets/system/fonts/ptsans/ptsans.css",
            "assets/system/icons/icomoon/icomoon.css",
            "assets/system/icons/material/material.css",
            "assets/system/icons/fontawesome/fontawesome.css",
            "assets/system/plugins/fancybox/source/jquery.fancybox.css",
            "assets/system/css/animate.css",
            "assets/system/plugins/jquery-easyui-1.8.1/themes/default/easyui.css",
            "assets/system/plugins/jquery-easyui-1.8.1/themes/default/datagrid.css",
            "assets/system/css/bootstrap.css",
            "assets/system/css/bootstrap_limitless.css",
            "assets/system/css/layout.css",
            "assets/system/css/components.css",
            "assets/system/css/colors.css",
            "assets/system/plugins/videojs/videojs.css",
            "assets/system/plugins/jquery-easyui-1.8.1/themes/icon.css",
            "assets/system/plugins/jquery-contextmenu/jquery.contextMenu.css",
            "assets/system/css/custom.css",
            "assets/system/plugins/owlcarousel/assets/owl.carousel.css",
            "assets/system/plugins/owlcarousel/assets/owl.theme.default.css",
            
            "assets/theme/zavkhan/css/custom.css",
            "assets/theme/zavkhan/css/mmenul.css",
            "assets/theme/zavkhan/css/responsive.css",
            "assets/theme/zavkhan/css/toggle-nav.css"
            
            
        );

        /**
         * Ideally, you wouldn't need to change any code beyond this point.
         */
        $buffer = "";
        foreach ($cssFiles as $cssFile) {
            $buffer .= file_get_contents($cssFile);
        }
        // Remove comments
        $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
        // Remove space after colons
        $buffer = str_replace(': ', ':', $buffer);
        // Remove whitespace
        $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
        // Enable GZip encoding.
        ob_start("ob_gzhandler");
        // Enable caching
        header('Cache-Control: public');
        // Expire in one day
        header('Expires: ' . gmdate('D, d M Y H:i:s', time() + 86400) . ' GMT');
        // Set the correct MIME type, because Apache won't set it for us
        header("Content-type: text/css");
        // Write everything out
        echo($buffer);
    }

    public function minifyJs() {
        $jsFiles = array(
            "assets/system/js/jquery.min.js",
            "assets/system/js/bootstrap.bundle.min.js",
            "assets/system/js/plugins/loaders/blockui.min.js",
            "assets/system/js/plugins/loaders/pace.min.js",
            "assets/system/js/plugins/loaders/progressbar.min.js",
            "assets/system/plugins/videojs/videojs-ie8.min.js",
            "assets/system/plugins/videojs/video.js",
            "assets/system/js/plugins/forms/styling/uniform.min.js",
            "assets/system/js/plugins/forms/styling/switchery.min.js",
            "assets/system/js/plugins/forms/styling/switch.min.js",
            "assets/system/js/plugins/forms/inputs/autosize.min.js",
            "assets/system/js/plugins/forms/inputs/formatter.min.js",
            "assets/system/js/plugins/forms/inputs/inputmask.js",
            "assets/system/js/plugins/forms/inputs/maxlength.min.js",
            "assets/system/js/plugins/forms/inputs/passy.js",
            "assets/system/js/plugins/forms/inputs/touchspin.min.js",
            "assets/system/js/plugins/forms/selects/select2.min.js",
            "assets/system/js/plugins/forms/selects/bootstrap_multiselect.js",
            "assets/system/js/plugins/forms/tags/tagsinput.min.js",
            "assets/system/js/plugins/forms/tags/tokenfield.min.js",
            "assets/system/js/plugins/forms/validation/validate.min.js",
            "assets/system/js/plugins/forms/validation/additional_methods.min.js",
            "assets/system/js/plugins/forms/wizards/steps.min.js",
            
            "assets/system/plugins/jquery-easyui-1.8.1/jquery.easyui.min.js",
            //"assets/system/plugins/jquery-easyui-1.8.1/plugins/datagrid-filter.js",
            
            
            "assets/system/js/plugins/media/cropper.min.js",
            "assets/system/js/plugins/media/fancybox.min.js",
            "assets/system/js/plugins/ui/sticky.min.js",
            "assets/system/js/plugins/ui/slinky.min.js",
            "assets/system/js/plugins/notifications/pnotify.min.js",
            "assets/system/js/plugins/extensions/rowlink.js",
            "assets/system/js/plugins/extensions/cookie.js",
            "assets/system/js/plugins/extensions/session_timeout.min.js",
            "assets/system/js/plugins/visualization/echarts/echarts.min.js",
            "assets/system/js/plugins/ui/moment/moment.min.js",
            "assets/system/js/plugins/pickers/daterangepicker.js",
            "assets/system/js/plugins/pickers/anytime.min.js",
            "assets/system/js/plugins/pickers/pickadate/picker.js",
            "assets/system/js/plugins/pickers/pickadate/picker.date.js",
            "assets/system/js/plugins/pickers/pickadate/picker.time.js",
            "assets/system/js/plugins/pickers/pickadate/legacy.js",
            "assets/system/js/plugins/ui/fullcalendar/fullcalendar.min.js",
            //"assets/system/js/plugins/extensions/contextmenu.js",
            "assets/system/plugins/jquery-contextmenu/jquery.contextMenu.js",
            "assets/system/plugins/jquery-contextmenu/jquery.ui.position.js",
            
            "assets/system/js/plugins/extensions/jquery_ui/widgets.min.js",
            "assets/system/js/plugins/extensions/jquery_ui/effects.min.js",
//            "assets/system/plugins/jquery-ui-1.12.1.custom/jquery-ui.js",
            
            "assets/system/js/plugins/autoNumeric.js",
            "assets/system/js/plugins/jquery.hotkeys.js",
            "assets/system/js/plugins/jquery.form.min.js",
            "assets/system/js/plugins/jquery.fileDownload.js",
            "assets/system/js/plugins/jquery.parseparams.js",
            
            "assets/system/js/plugins/uploaders/dropzone.min.js",
            "assets/system/js/plugins/forms/styling/uniform.min.js",
            "assets/system/js/plugins/forms/inputs/inputmask.js",
            "assets/system/js/plugins/forms/inputs/formatter.min.js",
            "assets/system/js/plugins/editors/ace/ace.js",
            
            "assets/system/js/plugins/ui/sticky.min.js",
            "assets/system/plugins/owlcarousel/owl.carousel.min.js",
            "assets/system/plugins/jquery-vticker/jquery.vticker-min.js",
            
            
            "assets/system/js/app.js",
            "assets/system/js/config.js",
            "assets/system/core/_image.js",
            "assets/system/core/_file.js",
            "assets/system/core/_reaction.js",
            "assets/system/plugins/jquery-slim-scroll/jquery.slimscroll.min.js",
            
            "assets/theme/zavkhan/js/comment.js",
            "assets/theme/zavkhan/js/custom.js"

        );

        $modified = 0;
        $buffer = '';
        foreach ($jsFiles as $jsFile) {
            $buffer .= file_get_contents($jsFile);
        }
        $offset = 60 * 60 * 24 * 7; // Cache for 1 weeks
        header('Expires: ' . gmdate("D, d M Y H:i:s", time() + $offset) . ' GMT');

        if (isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) >= $modified) {
            header("HTTP/1.0 304 Not Modified");
            header('Cache-Control:');
        } else {
            header('Cache-Control: max-age=' . $offset);
            header('Content-type: text/javascript; charset=UTF-8');
            header('Pragma:');
            header("Last-Modified: " . gmdate("D, d M Y H:i:s", $modified) . " GMT");
            // Remove comments
//            $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
//            // Remove space after colons
//            $buffer = str_replace(': ', ':', $buffer);
            // Remove whitespace
//            $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
            // Enable GZip encoding.
            ob_start("ob_gzhandler");
            echo($buffer);
        }
    }

    private function compress($buffer) {
        /* remove comments */
        $buffer = preg_replace("/((?:\/\*(?:[^*]|(?:\*+[^*\/]))*\*+\/)|(?:\/\/.*))/", "", $buffer);
        /* remove tabs, spaces, newlines, etc. */
        $buffer = str_replace(array("\r\n", "\r", "\t", "\n", '  ', '    ', '     '), '', $buffer);
        /* remove other spaces before/after ) */
        $buffer = preg_replace(array('(( )+\))', '(\)( )+)'), ')', $buffer);
        return $buffer;
    }

}
