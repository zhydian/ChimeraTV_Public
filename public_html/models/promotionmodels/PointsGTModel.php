    <?php

/**
 * Class PointsGTModel
 * This file contains the SQL statements required to define
 * the pointsGT
 */
class PointsGTModel{

   protected $db;

   /**
   * Constructs a new Points GT with a given PDO object.
   */
   public function __construct(PDO $db){
     $this->db= $db;

   }

   /**
   * Inserts a Points GT record into the database.
   * @param values
   */
   public function add($values){
     //Adds Points GT.
if(!isset($values['updateSettings'])){
    $values['updateSettings']=false;
}
    $this->addPointsGT($values);
    if($values['updateSettings']){
//echo('updating Settings of PointsGT');
    //Adds instant winners for Points GT.
    $sql = "REPLACE INTO points_gt_instant_winner (
        pgt_instant_winner_id,
        pgt_points,
        pgt_prize_amount,
        pgt_color,
        pgt_id,
        pgt_account_id,
        pgt_enable_instant_winner
      ) VALUES
      (:pgt_instant_winner_id1,:pgt_points1,:pgt_prize_amount1,:pgt_color1,:pgt_id,:pgt_account_id,:pgt_enable_instant_winner1),
      (:pgt_instant_winner_id2,:pgt_points2,:pgt_prize_amount2,:pgt_color2,:pgt_id,:pgt_account_id,:pgt_enable_instant_winner2),
      (:pgt_instant_winner_id3,:pgt_points3,:pgt_prize_amount3,:pgt_color3,:pgt_id,:pgt_account_id,:pgt_enable_instant_winner3);";
    $result = $this->db->prepare($sql);
    $result->bindValue(':pgt_id', $values['promotionId'], PDO::PARAM_STR);
    $result->bindValue(':pgt_account_id', $values['accountId'], PDO::PARAM_STR);

       if(!isset($values['pgt_points1'])){
           $values['pgt_points1']='0';
       }
       if(!isset($values['pgt_prize_amount1'])){
           $values['pgt_prize_amount1']='0';
       }
       if(!isset($values['pgt_color1'])){
           $values['pgt_color1']='0';
       }
       if(!isset($values['pgt_points2'])){
           $values['pgt_points2']='0';
       }
       if(!isset($values['pgt_prize_amount2'])){
           $values['pgt_prize_amount2']='0';
       }
       if(!isset($values['pgt_color2'])){
           $values['pgt_color2']='0';
       }
       if(!isset($values['pgt_points3'])){
           $values['pgt_points3']='0';
       }
       if(!isset($values['pgt_prize_amount3'])){
           $values['pgt_prize_amount3']='0';
       }
       if(!isset($values['pgt_color3'])){
           $values['pgt_color3']='0';
       }
       if(!isset($values['pgt_instant_winner_id1'])){
           $values['pgt_instant_winner_id1']='';
       }
       if(!isset($values['pgt_enable_instant_winner1'])){
           $values['pgt_enable_instant_winner2']='0';
       }
       if(!isset($values['pgt_instant_winner_id2'])){
           $values['pgt_instant_winner_id2']='';
       }
       if(!isset($values['pgt_enable_instant_winner2'])){
           $values['pgt_enable_instant_winner2']='0';
       }
       if(!isset($values['pgt_instant_winner_id3'])){
           $values['pgt_instant_winner_id3']='';
       }
       if(!isset($values['pgt_enable_instant_winner3'])){
           $values['pgt_enable_instant_winner3']='0';
       }
    $result->bindValue(':pgt_instant_winner_id1', $values['pgt_instant_winner_id1'], PDO::PARAM_STR);
    $result->bindValue(':pgt_points1', $values['pgt_points1'], PDO::PARAM_STR);
    $result->bindValue(':pgt_prize_amount1', $values['pgt_prize_amount1'], PDO::PARAM_STR);
    $result->bindValue(':pgt_color1', str_replace('#','',$values['pgt_color1']) , PDO::PARAM_STR);
    $result->bindValue(':pgt_enable_instant_winner1', $values['pgt_enable_instant_winner1'], PDO::PARAM_STR);

    $result->bindValue(':pgt_instant_winner_id2', $values['pgt_instant_winner_id2'], PDO::PARAM_STR);
    $result->bindValue(':pgt_points2', $values['pgt_points2'], PDO::PARAM_STR);
    $result->bindValue(':pgt_prize_amount2', $values['pgt_prize_amount2'], PDO::PARAM_STR);
    $result->bindValue(':pgt_color2', str_replace('#','',$values['pgt_color2']), PDO::PARAM_STR);
    $result->bindValue(':pgt_enable_instant_winner2', $values['pgt_enable_instant_winner2'], PDO::PARAM_STR);


    $result->bindValue(':pgt_instant_winner_id3', $values['pgt_instant_winner_id3'], PDO::PARAM_STR);
    $result->bindValue(':pgt_points3', $values['pgt_points3'], PDO::PARAM_STR);
    $result->bindValue(':pgt_prize_amount3', $values['pgt_prize_amount3'], PDO::PARAM_STR);
    $result->bindValue(':pgt_color3', str_replace('#','',$values['pgt_color3']), PDO::PARAM_STR);
    $result->bindValue(':pgt_enable_instant_winner3', $values['pgt_enable_instant_winner3'], PDO::PARAM_STR);

    $result->execute();
    }else{
        $this->updatePlayers($values);
    }


   }
   public function updatePlayers($values){
       //echo('updating Players of PointsGT');
       //echo($values['pastedReport']);
       $sql = "insert INTO pgt_reports (
        pgt_reports_promoid,
        pgt_reports_report
      ) VALUES
      (:pgt_promoid,:pgt_report)";
       $result = $this->db->prepare($sql);
       $result->bindValue(':pgt_promoid', $values['promotionId'], PDO::PARAM_STR);
       $result->bindValue(':pgt_report', $values['pastedReport'], PDO::PARAM_STR);
       $result->execute();

       $sql = "replace INTO points_gt_players (
        pgt_player_id,
        pgt_player_name,
        pgt_current_points,
        pgt_car_icon,
        pgt_id,
        pgt_account_id
      ) VALUES";
       for($i = 1; $i <= $values['playerCount']; $i++){
           $sql .= "(:pgt_player_id$i,:pgt_player_name$i, :pgt_current_points$i, :pgt_car_icon$i, :pgt_id, :pgt_account_id)";
           $sql .= $i < $values['playerCount'] ? "," : ";";

       }
        echo('playercount:'.$values['playerCount']);
       $result = $this->db->prepare($sql);
       for($i = 1; $i <= $values['playerCount']; $i++){
           if(!isset($values["pgt_player_id$i"])){
               $values["pgt_player_id$i"]='';
           }
           if(!isset($values["pgt_player_name$i"])){
               $values["pgt_player_name$i"]='';
           }
           if(!isset($values["pgt_current_points$i"])){
               $values["pgt_current_points$i"]='0';
           }
           if(!isset($values['pgt_car_icon'])){
               $values["pgt_car_icon$i"]='0';
           }
           //echo("binding:".$values["pgt_player_name$i"]);
           $result->bindValue(":pgt_player_id$i", $values["pgt_player_id$i"], PDO::PARAM_STR);
           $result->bindValue(":pgt_player_name$i", $values["pgt_player_name$i"], PDO::PARAM_STR);
           $result->bindValue(":pgt_current_points$i", $values["pgt_current_points$i"], PDO::PARAM_STR);
           $result->bindValue(":pgt_car_icon$i", $values["pgt_car_icon$i"], PDO::PARAM_STR);
       }
       $result->bindValue(':pgt_id', $values['promotionId'], PDO::PARAM_STR);
       $result->bindValue(':pgt_account_id', $values['accountId'], PDO::PARAM_STR);
       $result->execute();
   }
public function checkforbindvalues($val){

    if(!isset($val)){
        $tmpval='0';
    }else{
        $tmpval=$val;
    }
    return $tmpval;
}
   private function addPointsGT($values){
     $sql = "INSERT INTO points_gt (
        pgt_title,
        pgt_subtitle,
        pgt_left_title,
        pgt_left_content,
        pgt_right_title,
        pgt_right_content,
        pgt_payout,
        pgt_race_begin,
        pgt_race_end,
        pgt_account_id,
        pgt_promotion_id,
        pgt_enable_instant_winners,
        pgt_atlas
      ) VALUES (
        :add_title,
        :add_subtitle,
        :add_left_title,
        :add_left_content,
        :add_right_title,
        :add_right_content,
        :add_payout,
        :add_race_begin,
        :add_race_end,
        :add_account_id,
        :add_promotion_id,
        :add_instant_winners,
        :add_atlas
        );";

    $result = $this->db->prepare($sql);
    $result->bindValue(':add_title', $values['pgt_title'], PDO::PARAM_STR);
    $result->bindValue(':add_subtitle', $values['pgt_subtitle'], PDO::PARAM_STR);
    $result->bindValue(':add_left_title', $values['pgt_left_title'], PDO::PARAM_STR);
    $result->bindValue(':add_left_content', $values['pgt_left_content'], PDO::PARAM_STR);
    $result->bindValue(':add_right_title', $values['pgt_right_title'], PDO::PARAM_STR);
    $result->bindValue(':add_right_content', $values['pgt_right_content'], PDO::PARAM_STR);
    $result->bindValue(':add_payout', $values['pgt_payout'], PDO::PARAM_STR);
    $result->bindValue(':add_race_begin', $values['pgt_race_begin'], PDO::PARAM_STR);
    $result->bindValue(':add_race_end', $values['pgt_race_end'], PDO::PARAM_STR);
    $result->bindValue(':add_account_id', $values['accountId'], PDO::PARAM_STR);
    $result->bindValue(':add_promotion_id', $values['promotionId'], PDO::PARAM_STR);
    $result->bindValue(':add_instant_winners', $values['pgt_enable_instant_winners'], PDO::PARAM_STR);
    $result->bindValue(':add_atlas', $values['pgt_atlas'], PDO::PARAM_STR);


    $result->execute();
    return $this->db->lastInsertId();


   }

   /**
   * Gets the most up-to-date record for the Points GT by promtion id (1 record total).
   */
   public function get($id){
     $sql = "SELECT
               *
             FROM
               points_gt
             WHERE
               pgt_promotion_id=:id
             ORDER BY
               pgt_id DESC
             LIMIT 1;";
     $result = $this->db->prepare($sql);
     $result->bindValue(':id', $id, PDO::PARAM_STR);
     $result->execute();

       $promoResult = $result->fetch(PDO::FETCH_ASSOC);
       $timestamp = strtotime($promoResult['pgt_race_begin']);
       $promoResult['pgt_race_begin'] = date("Y-m-d H:i:00", $timestamp);
       $timestamp = strtotime($promoResult['pgt_race_end']);
       $promoResult['pgt_race_end'] = date("Y-m-d H:i:00", $timestamp);
     $result->closeCursor();
     $instantWinners = $this->getPointsGTInstantWinners($id);

     $players = $this->getPointsGTPlayers($id);

     $result = array_merge($promoResult, $instantWinners, $players);
     return $result;
   }

    /**
     * Update the data
     * @param $values
     */
   public function update($values){
     $this->add($values);

   }

   /**
   * Gets the most up-to-date record for the Points GT instant winners by Points GT id (3 records total).
   */
   public function getPointsGTInstantWinners($id){

     $sql = "SELECT `pgt_instant_winner_id`,`pgt_points`,`pgt_prize_amount`,`pgt_id`,`pgt_enable_instant_winner`,`pgt_timestamp`,CONCAT('#',points_gt_instant_winner.pgt_color) as pgt_color 
             FROM
               points_gt_instant_winner
             WHERE
               pgt_id=:id
             ORDER BY
               pgt_points DESC 
                            LIMIT 3;";
     $result = $this->db->prepare($sql);

      $result->bindValue(':id', $id, PDO::PARAM_STR);
     $result->execute();

     $promoResult = $result->fetchAll(PDO::FETCH_ASSOC);

     $result->closeCursor();

     return $this->formatRowWithNumberIndex($promoResult);
   }

   /**
   * Gets the most up-to-date record for the Points GT instant winners by Points GT id (3 records total).
   */
   public function getPointsGTPlayers($id){

     $sql = "SELECT
               *
             FROM
               points_gt_players
             WHERE
               pgt_id=:id
             ORDER BY
               pgt_current_points desc;";
     $result = $this->db->prepare($sql);
     $result->bindValue(':id', $id, PDO::PARAM_STR);
     $result->execute();

     $promoResult = $result->fetchAll(PDO::FETCH_ASSOC);

     $result->closeCursor();
     return $this->formatRowWithNumberIndex($promoResult);
   }

    /**
     * This keeps track of all the instant winners and players by
     * assigning them numbers
     * @param $rows
     * @return array
     */
   public function formatRowWithNumberIndex($rows){
     $result = array();
     $i = 0;
     foreach($rows as $row){
         $i++;
         foreach($row as $key=>$field){
         $result[$key . $i] = $field;
       }
     }
        $result['pgtPlayerCount']= $i;
     return $result;
   }
}
?>