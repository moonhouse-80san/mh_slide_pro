<?php
/**
 * @class mh_slide_pro
 * @author 80san (mbg1346@moonhouse.com)
 * @brief 선택된 모듈의 이미지를 흘러가는 형식으로 보여주는 위젯
 * @version 0.2
 **/

class mh_slide_pro extends WidgetHandler {

    /**
     * @brief 위젯의 실행 부분
     *
     * ./widgets/위젯/conf/info.xml 에 선언한 extra_vars를 args로 받는다
     * 결과를 만든후 print가 아니라 return 해주어야 한다
     **/
    function proc($args) {
        // 기본값 설정을 위한 배열
        $defaults = [
            // 배경 칼라 설정
            'bgcol_slide' => 'Y',
            'back_color1' => '#5b927d',
            'back_color2' => '#a8ae7e',
            'back_color3' => '#835531',
            'back_color4' => '#9C4998',
            'back_color5' => '#444',
            'back_color6' => '#f3b96c',
            
            // more 칼라 설정
            'more_color1' => 'orange',
            'more_color2' => 'blue',
            'more_color3' => 'green',
            'more_color4' => 'purple',
            'more_color5' => 'yellow',
            'more_color6' => 'dark',
            
            // 썸네일 설정
            'flowing_images_num' => 6,
            'thumbnail_type' => 'ratio',
            'thumbnail_width' => 600,
            'thumbnail_height' => 400,
            'shuffling' => 'false',
            'boxshadow' => 'Y',
            'img_rotateY' => -50,
            'img_rotateX' => 60,
            'img_rotateZ' => 35,
            
            // 제목 설정
            'title_visibility' => 'true',
            'title_length' => 0,
            'title_size' => '1.1em',
            'title_color' => '#444',
            
            // 내용 설정
            'box_width' => 900,
            'main_height' => 400,
            'background_color' => '#eee',
            'display_summary' => 'Y',
            'content_cut_size' => 500,
            'sum_font_color' => '#fff',
            'sum_font_size' => '1em',
            'content_h_n' => 6,
            'content_l_h' => '2',
            
            // 슬라이드 설정
            'arrows' => 'true',
            'buttons' => 'false',
            'th' => 'true',
            'ti' => 'Y',
            'autoplay' => 'true',
            'imageScaleMode' => 'cover',
            'forceSize' => 'none',
            'autoheight' => 'false',
            'th_location' => 'bottom',
            'autoplay_delay' => 5000,
            'rla_color' => '#fff',
            'tha_color' => '#f00',
            'fade' => 'false',
            'fullscreen' => 'true',
            'fbc' => '#eee'
        ];

        // 기본값과 전달된 인자 병합
        foreach ($defaults as $key => $value) {
            if (!isset($args->$key)) {
                $args->$key = $value;
            }
            // 숫자형 값들은 형변환
            if (is_numeric($value)) {
                $args->$key = (int)$args->$key;
            }
        }

        // 쿼리 객체 생성 및 초기화
        $obj = new stdClass();
        $obj->module_srls = $args->module_srls;
        $obj->category_srl = $args->category_srl;
        $obj->list_count = $args->flowing_images_num;
        
        // 셔플링 설정
        if ($args->shuffling === "true") {
            $obj->list_order = 'rand()';
        }

        // 이미지 목록 조회
        $files_output = executeQueryArray("widgets.mh_slide_pro.getImages", $obj);
        
        // 문서 처리
        $document_list = [];
        if (!empty($files_output->data)) {
            $document_srl_list = array_map(function($item) {
                return $item->document_srl;
            }, $files_output->data);

            if (!empty($document_srl_list)) {
                $oDocumentModel = getModel('document');
                $tmp_document_list = $oDocumentModel->getDocuments($document_srl_list);
                
                if ($tmp_document_list) {
                    $document_list = array_values($tmp_document_list);
                    
                    // 셔플링이 설정된 경우 문서 목록 섞기
                    if ($args->shuffling === "true") {
                        shuffle($document_list);
                    }
                }
            }
        }

        // 결과 데이터 설정
        $args->document_list = $document_list;
        Context::set('b_img', $args->b_img);
        Context::set('widget_info', $args);

        // 템플릿 처리
        $tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
        $tpl_file = 'list';
        $oTemplate = TemplateHandler::getInstance();
        
        return $oTemplate->compile($tpl_path, $tpl_file);
    }
}