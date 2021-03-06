<?php
/**
 * This is the property view
 */
?>
<div class="promotion-view" id=<?php echo "\"promotion-list-" . $property['property_id'] . "\""; ?>>
    <h2 class="property-title"><?php echo $property['property_name']; ?>
        <?php
        if ($_SESSION['isGod']) {
            ?>
            <span><i data-property-id="<?php echo($property['property_id']); ?>"
                     class="fa fa-list-alt property-option-icon view-subscriptions-btn"></i></span>


            <span><i data-property-id="<?php echo($property['property_id']); ?>"
                     class="fa fa-caret-square-o-down property-isviewing"></i></span>
            <?php
        }
        ?>
    </h2>
    <div id="promotioncontainer<?php echo($property['property_id']); ?>">
        <?php
        if ($permission->hasPermissionById('promotion', $property['property_id'], 'C') || $_SESSION['isGod']){
        ?>
        <div title="New Promotion" data-toggle="tooltip"
             data-point-storage="<?php echo($property['property_point_storage']) ?>"
             id=<?php echo "\"" . $property['property_id'] . "\""; ?> class="add-promotion-btn tile-body tile-insert
        ">
        <img class="tile-icon" src="dependencies/images/clear.png">
        <div class="glyphicon-new-container">
            <span class="glyphicon glyphicon-plus-sign glyphicon-new-tile white" aria-hidden="true"></span>
        </div>
    </div>

    <?php
    }
    $promotionList = $promotion->getAllPromotionsByProperty($property['property_id']);
    if (count($promotionList) > 0) {
        foreach ($promotionList as $row) {
            include 'promotionview.php';
        }
    } ?>
</div>
</div>

