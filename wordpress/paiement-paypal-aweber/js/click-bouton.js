/**
 * Created by ADOUKO on 08/02/2015.
 */
$(function(){

   $( "button" ).click(function() {

        // insertion de la valeur modifier ou supprimer dans le champ d'id = lebouttonclique
        $( '#lebouttonclique').val($( this ).attr('id').substring(0,4));

    });


});
