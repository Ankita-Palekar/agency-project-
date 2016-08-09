<?php

class moviesController extends Controller {
	function filter($params){
    $opts = $this->process_params($params);
		$result = Movie::filter($opts);
    $this->_template->render_json($result);
	}

  function movies_list(){
    $this->get_movies_list();
    $this->_template->render();     
  }

  function add_movie(){
    $this->get_movies_list();
    $this->_template->render();
  }

  function add($params){
    unset($params['url']);
    $m = new Movie();
    $message = $m->add($params);
    $this->set('message', $message);
    $this->_template->render();
  }

  private function get_movies_list(){
    $languages_list = Movie::get_languages_list();
    $genres_list = Movie::get_genres_list();
    $result = Array("genre_list" => $genres_list, "language_list" => $languages_list);
    $this->set('result', $result);
  }

  private function process_pagination($params){
    $params['limit'] = 2 ;
    if (isset($params['page']) && ((int)$params['page'] > 0)){
      $params['offset'] = ((int)$params['page'] - 1) * 2;
    } else
    {
      $params['offset'] = 0;
    }
    return $params;
  }

  private function process_params($params){
    $result = Array();
    if($params[0] == Controller::JSON_RESP) {
      array_shift($params);
      while (sizeof($params) > 1) {
        $result[$params[0]] = $params[1];
        $params = array_slice($params, 2);
      }
    }  
    
    $result = $this->process_pagination($result);
    return $result;
  }

}
