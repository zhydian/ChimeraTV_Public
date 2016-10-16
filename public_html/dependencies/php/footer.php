<?php
/*
* Footer
* Author: Stephen King
* Version 2016.10.5.1
*
* This page controls the footer and closing material for the website
*/
?>
    <footer>


    </footer>
   </body>

   <script>

   $(document).ready(function(){
       var loggedIn = <?php echo $_SESSION["loggedIn"]; ?>
       alert(loggedIn);

       //Show option bar
       $(".tile-body").hover(function(){
           $(this).children(".tile-menu-bar").removeClass("hidden");
       }, function(){
           $(this).children(".tile-menu-bar").addClass("hidden");

       });

       //highlight option under mouse
		$(".tile-menu-item").hover(function(){
        $(this).addClass("tile-menu-item-hover");
        }, function(){
        $(this).removeClass("tile-menu-item-hover");

		});


       $(".settingsBtn").unbind('click').click(function(){
           settingsModal.dialog('open');
       });

       $("#add-promotion-btn").click(function(){
           addPromotionModal.dialog('open');
       });

       //Open add/remove user panel
       $(".userBtn").unbind('click').click(function(){
           editUsersModal.dialog('open');
       });

       /*
        These are the modal windows that can be opened. Note that these need
        to be moved to their own file. Most likely they should just be aggregated
        as they are 90% similar.
        */

       var editUsersModal = $("#editUsers").dialog({
           autoOpen: false,
           height: 400,
           width: 350,
           modal: true,
           buttons: {
               Submit: function () {
                   //submit changes to db through options modal class
                   editUsers.dialog('close');
               }
           }
       });

       //Adds a new tile to the view with the image that is passed into the function
       var addPromotion = function(data) {
           $('#promotion-list').append(
               '<div class="tile-body">'+
                    '<img class="tile-icon" src="dependencies/images/' + data + '">'+
                    '<div class="tile-menu-bar hidden">'+
                        '<div class="tile-menu-item settingsBtn">'+
                            '<span class="glyphicon glyphicon-cog glyphicon-menu black" aria-hidden="true"></span>'+
                        '</div>'+
                        '<div class="tile-menu-item">'+
                            '<span class="glyphicon glyphicon-pause glyphicon-menu black" aria-hidden="true"></span>'+
                        '</div>'+
                        '<div class="tile-menu-item">'+
                            '<span class="glyphicon glyphicon-user glyphicon-menu black" aria-hidden="true"></span>'+
                        '</div>'+
                    '</div>'+
               '</div>'
           );
       };

       var validateLogin = function() {
           //assign variables from form data
           var s_name = $("#userName").val();
           var s_password = $("#password").val();

           //ajax call to validation controller
           $.ajax({
               url: 'controllers/validationScript.php',
               type: 'post',
               data: {userName: s_name, password : s_password},
               cache: false,
               success: function(json) {
                   //if returned data is formatted correctly
                   if (json.valid === "yes"){
                       $("#errorMessage").empty();
                       $("#errorMessage").hide();
                       loginModal.dialog('close');
                       $('#page').show();
                   }else{
                       //display error message
                       ul = document.getElementById("errorMessage");
                       $("#errorMessage").empty();
                       $("#errorMessage").show();
                       $.each(json.errorMessage, function(index, item){
                           var li = document.createElement("li");
                           li.appendChild(document.createTextNode(item));
                           ul.appendChild(li);
                       });
                   }
               },
               error: function(xhr, desc, err){
                   console.log(xhr + "\n" + err);
               }
           });
       };

       var addPromotionModal = $("#addPromotion").dialog({
           autoOpen: false,
           height: 400,
           width: 350,
           modal: true,
           buttons: {
               Submit: function () {
                   var id = $('select[name=promoId]').val();
               //Ajax call to update database with new promotion
                 $.ajax({
                        url: 'controllers/addPromotion.php',
                        type: 'post',
                        data: {id: id},
                        cache: false,
                        success:function(response){
                            console.log(response);

                            //update view with new promotion
                            addPromotion(response.image);
                            addPromotionModal.dialog('close');
                        },
                        error: function(xhr, desc, err){
                         console.log(xhr + "\n" + err);
                        }
                    });
               }
           }
       });

       var settingsModal = $("#settings").dialog({
           autoOpen: false,
           height: 400,
           width: 350,
           modal: true,
           buttons: {
               Submit: function () {
                   //submit changes to db through options modal class
                   settingsModal.dialog('close');
               }
           }
       });

       var loginModal = $("#loginModal").dialog({
           autoOpen: false,
           height: 400,
           width: 359,
           modal: true,
           buttons: {
               Submit: function() {
                   validateLogin();
               }
            }
       });

       var createCasinoModal = $('#createCasino').dialog({
           autoOpen: false,
           height: 400,
           width: 350,
           modal: true,
           buttons: {
               Submit: function() {
                   alert("Casino Created");
                   createCasinoModal.dialog('close');
               }
           }
       });

       if(!loggedIn) {
           $('#page').hide();
           loginModal.dialog('open');
       }
   });

   </script>
</html>
