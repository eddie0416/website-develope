<?php
  function selectItem($table, $text_field, $value_field, $tag_name) {
    global $link; //connMySQL.php內的那個$link
    $sql = "SELECT $text_field,$value_field FROM $table";
    $result = mysqli_query($link, $sql);
    echo "<select name='".$tag_name."'>"."\n";   
    while($row = mysqli_fetch_array($result)) {
      echo "  <option value='".$row[$value_field]."'>".$row[$text_field]."</option>"."\n";
    }
    echo "</select>\n";
  }

  function update_dept($id) {
    global $link; //connMySQL.php內的那個$link
    $sql = "SELECT deptNo FROM `score` WHERE stNo='$id'";
    $result = mysqli_query($link, $sql);
    $row = mysqli_fetch_assoc($result);
    $deptNo = $row['deptNo'];
    $sql = "SELECT * FROM `department`";
    $result = mysqli_query($link, $sql);
    echo "<select name='department'>"."\n";   
    while($row = mysqli_fetch_assoc($result)) {
      if($row['deptNo'] == $deptNo){
        echo "  <option value='".$row['deptNo']."'selected>".$row['deptTitle']."</option>"."\n";
      }else{
        echo "  <option value='".$row['deptNo']."'>".$row['deptTitle']."</option>"."\n";
      }
    }
    echo "</select>\n";
  }
  
  function update_club($id){
    global $link;
    $sql = "SELECT club_code FROM `student_club` WHERE stNo='$id'";
    $result = mysqli_query($link, $sql);
    $user_clubcode = [];
    while($row = mysqli_fetch_assoc($result)){
      $user_clubcode[] = $row['club_code'];
    }
    $sql = "SELECT * FROM clubs";
    $result = mysqli_query($link, $sql);
    while ($row = mysqli_fetch_assoc($result)) {
      $club_Code = $row['club_code'];
      $club_Name = $row['club_name'];
      $checked = (in_array($club_Code, $user_clubcode)) ? 'checked="checked"' : '';
      echo '<input type="checkbox" name="club[]" id="' . $club_Code . '" value="' . $club_Code . '" ' . $checked . '> ' . $club_Name . ' '; //因此是post club_code到process.php
    }
  }
?>
