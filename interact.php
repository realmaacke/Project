//// likeButton

<?php

  session_start();

  if(!isset($_SESSION['like'])) { $_SESSION['like'] = []; }

  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }

  if(!is_ajax_request()) { exit; }
  $raw_id = isset($_POST['id']) ? $_POST['id'] : '';
  if(preg_match("/post-no-(\d+)/", $raw_id, $matches)){
      $id = $matches[1];
      if(!in_array($id, $_SESSION['like'])){
          $_SESSION['like'][] = $id;
      }
      echo "true";
  }else{
      echo "false";
  }

?>



// unlikeButton

<?php

  session_start();
  if(!isset($_SESSION['like'])) { $_SESSION['like'] = []; }

  // remove the element from the session array
  function remove_ele($ele, $session_array){
      $index = array_search($ele, $session_array);
      array_splice($session_array, $index, 1);
      return $session_array;
  }

  function is_ajax_request() {
    return isset($_SERVER['HTTP_X_REQUESTED_WITH']) &&
      $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest';
  }

  if(!is_ajax_request()) { exit; }
  $raw_id = isset($_POST['id']) ? $_POST['id'] : '';
  if(preg_match("/post-no-(\d+)/", $raw_id, $matches)){
      $id = $matches[1];
      if(in_array($id, $_SESSION['like'])){
          $_SESSION['like'] = remove_ele($id, $_SESSION['like']);
      }
      echo "true";
  }else{
      echo "false";
  }
?>