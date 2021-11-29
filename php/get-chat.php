<?php 
    session_start();
    include('../autoload.php');  // loading all classes using spl loader
        include_once "config.php";
        $outgoing_id = $_SESSION['unique_id'];

          $img = DB::query('SELECT profileimg FROM users WHERE id=:id', array(':id'=>$outgoing_id))[0]['profileimg'];
          $hasimage = false;
          $returnImage = "";
          if($img)
          {
            $hasimage = true;
          }

          if($hasimage){
              $returnImage = $img;
          }else {
              $returnImage = "Visual/img/avatar.png";
          }


        $incoming_id = mysqli_real_escape_string($conn, $_POST['incoming_id']);
        $output = "";
        $sql = "SELECT * FROM messages LEFT JOIN users ON users.id = messages.outgoing_msg_id
                WHERE (outgoing_msg_id = {$outgoing_id} AND incoming_msg_id = {$incoming_id})
                OR (outgoing_msg_id = {$incoming_id} AND incoming_msg_id = {$outgoing_id}) ORDER BY msg_id";
        $query = mysqli_query($conn, $sql);
        if(mysqli_num_rows($query) > 0){
            while($row = mysqli_fetch_assoc($query)){
                if($row['outgoing_msg_id'] === $outgoing_id){
                    $output .= '<div class="chat outgoing">
                                <div class="details">
                                    <p>'. $row['msg'] .'</p>
                                </div>
                                </div>';
                }else{
                    $output .= '<div class="chat incoming">
                                <img src="'.$returnImage.'" alt="">
                                <div class="details">
                                    <p>'. $row['msg'] .'</p>
                                </div>
                                </div>';
                }
            }
        }else{
            $output .= '<div class="text">No messages are available. Once you send message they will appear here.</div>';
        }
        echo $output;

?>