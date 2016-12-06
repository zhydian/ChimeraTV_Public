<?php
/**
 * This is the create high hand form
 */
?>

<div id="add-promotion">
    <div id="first-page">
        <form>
            <br>

            <input id="title-message" name="title_message" type="text" placeholder="Title Message"/>
            <label for="title-message">Title Message</label>
            <br>
            <br>

            <input id="horn-timer" name="horn_timer" type="number" placeholder="Horn Timer"/>
            <label for="horn-timer">Horn Timer</label>
            <br>
            <br>

            <input id="payout-value" name="payout_value" type="number" placeholder="Default Payout"/>
            <label for="payout-value">Default Payout</label>
            <br>
            <br>

            <input id="session-timer" name="session_timer" type="number" placeholder="Session Timer"/>
            <label for="session-timer">Session Timer</label>
            <br>
            <br>
            <label>Show Multiple Hands</label> <br>
            <div class="option-group">
                <input class="high-hand-radio" value="0" name="multiple_hands" id="disabled" type="radio" checked/>
                <label for="disabled"> Disabled </label>
                <br/>
                <input class="high-hand-radio" value="1" name="multiple_hands" id="previous" type="radio"/>
                <label for="previous"> Previous Winners </label>
                <br/>
                <input class="high-hand-radio" value="2" name="multiple_hands" id="ranked" type="radio"/>
                <label for="ranked"> Ranked Hands </label>
                <br/>
            </div>
            <br>

            <input class="high-hand-checkbox" id="high-hand-gold" name="high_hand_gold" type="checkbox"/>
            <label class="high-hand-label">High Hand Gold</label>
            <br>

            <input class="high-hand-checkbox" id="use-joker" name="use_joker" type="checkbox"/>
            <label class="high-hand-label">Use Joker</label>
            <input type="hidden" name="scene_id" value="2"/>
            <br>

            <input class="high-hand-checkbox" id="custom-payout" name="custom_payout" type="checkbox"/>
            <label class="high-hand-label">Custom Payout</label>
        </form>
    </div>
</div>
<script src="dependencies/js/promotion/formhelperfunctions.js"></script>
<script src="dependencies/js/promotion/highhand.js"></script>
<script>
    getTemplate();
</script>
<?php
if ($_POST['promotion_settings']) {
    echo "<script>getModalData(" . $_POST['promotion_id'] . "," . $_POST['promotion_type'] . ");</script>";
}
?>
