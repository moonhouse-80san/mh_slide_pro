<?php
	/**
	 * @class mh_slide_pro
	 * @author 80san (mbg1346@naver.com)
	 * @brief 선택된 모듈의 이미지를 흘러가는 형식으로 보여주는 위젯
	 * @version 0.1
	 **/

	class mh_slide_pro extends WidgetHandler {

		/**
		 * @brief 위젯의 실행 부분
		 *
		 * ./widgets/위젯/conf/info.xml 에 선언한 extra_vars를 args로 받는다
		 * 결과를 만든후 print가 아니라 return 해주어야 한다
		 **/

		function proc($args) {

			$args->box_width = isset($args->box_width) ? (int)$args->box_width : 900;
			$args->main_height = isset($args->main_height) ? (int)$args->main_height : 400;
			Context::set('b_img', $args->b_img);
			$args->background_color = isset($args->background_color) ? $args->background_color : '#eee';

			// 배경 칼라 부분
			$args->bgcol_slide = isset($args->bgcol_slide) ? $args->bgcol_slide : 'Y';
			$args->back_color1 = isset($args->back_color1) ? $args->back_color1 : '#5b927d';
			$args->back_color2 = isset($args->back_color2) ? $args->back_color2 : '#a8ae7e';
			$args->back_color3 = isset($args->back_color3) ? $args->back_color3 : '#835531';
			$args->back_color4 = isset($args->back_color4) ? $args->back_color4 : '#9C4998';
			$args->back_color5 = isset($args->back_color5) ? $args->back_color5 : '#444';
			$args->back_color6 = isset($args->back_color6) ? $args->back_color6 : '#f3b96c';

			$args->more_color1 = isset($args->more_color1) ? $args->more_color1 :'orange';
			$args->more_color2 = isset($args->more_color2) ? $args->more_color2 :'blue';
			$args->more_color3 = isset($args->more_color3) ? $args->more_color3 :'green';
			$args->more_color4 = isset($args->more_color4) ? $args->more_color4 :'purple';
			$args->more_color5 = isset($args->more_color5) ? $args->more_color5 :'yellow';
			$args->more_color6 = isset($args->more_color6) ? $args->more_color6 :'dark';

			// 썸네일 부분
			$args->flowing_images_num = isset($args->flowing_images_num) ? (int)$args->flowing_images_num : 6;
			$args->thumbnail_type = isset($args->thumbnail_type) ? $args->thumbnail_type : 'ratio';
			$args->thumbnail_width = isset($args->thumbnail_width) ? (int)$args->thumbnail_width : 600;
			$args->thumbnail_height = isset($args->thumbnail_height) ? (int)$args->thumbnail_height : 400;
			$args->shuffling = isset($args->shuffling) ? $args->shuffling : 'false';
			$args->boxshadow = isset($args->boxshadow) ? $args->boxshadow : 'Y';
			$args->img_rotateY = isset($args->img_rotateY) ? (int)$args->img_rotateY : -50;
			$args->img_rotateX = isset($args->img_rotateX) ? (int)$args->img_rotateX : 0;
			$args->img_rotateZ = isset($args->img_rotateZ) ? (int)$args->img_rotateZ : 0;

			// 제목부분
			$args->title_visibility = isset($args->title_visibility) ? $args->title_visibility : 'true';
			$args->title_length = isset($args->title_length) ? (int)$args->title_length : 0;
			$args->title_size = isset($args->title_size) ? $args->title_size : '18px';
			$args->title_color = isset($args->title_color) ? $args->title_color : '#444';

			// 내용부분
			$args->display_summary = isset($args->display_summary) ? $args->display_summary : 'Y';
			$args->content_cut_size = isset($args->content_cut_size) ? (int)$args->content_cut_size : 500;
			$args->sum_font_color = isset($args->sum_font_color) ? $args->sum_font_color : '#fff';
			$args->sum_font_size = isset($args->sum_font_size) ? $args->sum_font_size : '1em';
			$args->content_h_n = isset($args->content_h_n) ? (int)$args->content_h_n : 6;
			$args->content_l_h = isset($args->content_l_h) ? $args->content_l_h : '2';

			// 슬라이드부분
			$args->arrows = isset($args->arrows) ? $args->arrows : 'true';
			$args->buttons = isset($args->buttons) ? $args->buttons : 'false';
			$args->th = isset($args->th) ? $args->th : 'true';
			$args->ti = isset($args->ti) ? $args->ti : 'Y';
			$args->autoplay = isset($args->autoplay) ? $args->autoplay : 'true';
			$args->imageScaleMode = isset($args->imageScaleMode) ? $args->imageScaleMode : 'cover';
			$args->forceSize = isset($args->forceSize) ? $args->forceSize : 'none';
			$args->autoheight = isset($args->autoheight) ? $args->autoheight : 'false';
			$args->th_location = isset($args->th_location) ? $args->th_location : 'bottom';
			$args->autoplay_delay = isset($args->autoplay_delay) ? (int)$args->autoplay_delay : 5000;
			$args->rla_color = isset($args->rla_color) ? $args->rla_color : '#fff';
			$args->tha_color = isset($args->tha_color) ? $args->tha_color : '#f00';
			$args->fade = isset($args->fade) ? $args->fade : 'false';
			$args->fullscreen = isset($args->fullscreen) ? $args->fullscreen : 'true';
			$args->fbc = isset($args->fbc) ? $args->fbc : '#eee';

			// query args
			$obj->module_srls = $args->module_srls;
			if($args->shuffling == "true") $obj->list_order = 'rand()';
			$obj->list_count = $args->flowing_images_num;

			// get file list
			$obj->category_srl = $args->category_srl;
			$files_output = executeQueryArray("widgets.mh_slide_pro.getImages", $obj);
			unset($obj);

			$document_srl_list = array();
			$document_list = array();
			
			if($files_output->data) foreach($files_output->data as $val){ $document_srl_list[] = $val->document_srl; }

			unset($files_output);

			if($document_srl_list) {
				$oDocumentModel = &getModel('document');
				$tmp_document_list = $oDocumentModel->getDocuments($document_srl_list);
				foreach($tmp_document_list as $val) $document_list[] = $val;
				unset($oDocumentModel);
				unset($tmp_document_list);

				//shuffle
				if($args->shuffling == "true") shuffle($document_list);
				$args->document_list = $document_list;
			}

			Context::set('widget_info', $args);

			// skin
			$tpl_path = sprintf('%sskins/%s', $this->widget_path, $args->skin);
			$tpl_file = 'list';
			$oTemplate = &TemplateHandler::getInstance();
			$output = $oTemplate->compile($tpl_path, $tpl_file);

			return $output;
		}
	}
?>