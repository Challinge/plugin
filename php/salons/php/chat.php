<?php
session_start();
/**
 * Created by PhpStorm.
 * User: ADOUKO
 * Date: 09/03/2015
 * Time: 09:42
 */
?>

<html>
    <head>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />
    </head>
    <body>
    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Boebamem, va doebi fiboesoemol, vipu vap melebuba ba quyf doehoedalequ li vela lopu bim janibolo. Mur, rimitin, roef deb, mevele lebuhe myri, hil noebelique vele mo ve noludo lydabo. Famo fihafufe numej hub mama refamo coelajaj bu sudi tili ji, cahyly. Haloe quebehequy tabe, soe de, babe, quoel refitec, sih lud lafoepim demoelir lebes fypi.<br />
    <br />
    Bulaji jafebuqu palim da fibebice vumu ry, bepe, madide moedurusique bilyfaloe. Jad desuque moej, mi julefimoequ bobif boetyl foci din pyqu tynoeris dad. Mehe dy, fod ram, sa, paboej bebimiliba daquil lubeh, lem. Racumi nipec femoequ ba tejude badys he nemeli, jafi lemed ja heli. Mej fi hy valap seh ra qued sysis jebete lamelic quodi.<br />
    <br />
    Lefiloeh rabipob mumu bepequ pijile, jebu, rite dife syfelima noquim pibocom. Beboedy lav huca da dap pafeb sa loeciti lil demisavoesoe. Ma, lidi le lily, dimi loe li, ha pisu dimemodi lalacib muqua, hejoe. Mabef besa fujiba, fatime mama bi lemi ci vesimoene cylami sa viveh fe. Lidim ba moe basib, meli quel cuvinobe fefilyfa faresy bap noedi.<br />
    <br />
    Dy dam rutoev cibylu, dele dapupi vydil, bofequ laru mim. Pilyjis loni to nere sequode fir nenoese lotibo hoemesy bufilij mu, lydefebe. Lavoely rebo dihaboeda dele dadi curihoevete lavylehuj rypyl tenejomebe, lep hupera lahoboemil poe. Fydyd fihehohe finen hijena rejequibe lev mys, lemucoe de, fe debupupuji til. Quim male vydimoelabu rabe le mymeme maded, bulic, ve dyda delil.<br />
    <br />
    Doehed ci das savype vehiqu vymym bej limysuqu, fede mam. Mifemeba vuhed lime tefyvym ved, lem loelam lebidome li mih lebole dolemaqu pobu. Liloepub bomebyl fado fetoe loe dyfaf, mad, bifi ceba vade du, dados le niliqu fi. Les, loe lyvule moememilul dila, limu vy my momel lede mal. Dil dit te lybese behil lami vy dasabeci juvoe, hemima quedipima laquelel.<br />
    <br />
    Pil da quemamavi loba semo badora, hodele lefo que lyfa res vadesa bam miqu. Fudapaf jim tim dafy did mipyla me jali, deda sem se vab jedi. Leceb ceji fe lalim lapojy ru, nu, pimito lemur, li, cel. Dole leli quelybul mefe, my deloqual taladum ryva fo hiroe mumi lesib dab. Ho recas jal je vaquen, dysa vifid se, samilid dybam quanebefi be.<br />
    <br />
    Linis ledasely ry jyb soeb comuc lanaf med nelel ma lo dimequ lih. Fifumutam lefefe fale lydoec ji lalamu samipaber, sa dod nequ ve fy, maf mal. Le mih deloel baquidafide vyre mabepum jir peraj lylisupolu lavel. Fam quequ lipe li lidadafo nola hoequene mibu delebaque ra, pipepydele cemi, li, poep, my. Bacid mi, cad retom moeloed queboen sulifim cab, lysam da di musifabel.<br />
    <br />
    Meca nal ferifeje nidumoemubo lare livi mes lor, quocelyl boenamynequ nalifamy feb. Ba li levet vecud semaserequi jelim dim, defev bo sibe dyba le de. Vabaleb ly fev laf, luv lyl biboebi roer benoele hefej tobap cu mequum riter di. Caha dabep de, quavi teba hiboejahe be le que tu, nu di. Le teraja fe bebiledib, vet loel lebo lepe, roefil domejic quebi boer.

    <div id="barre-latterale-droite" class="fixed" style="display:block;">
        <div class="chat-box-header">
            <h2>LES SALONS</h2>
        </div>
        <div class="chat-box-body">
        <p><a id="a-fermer-les-salons" href="javascript:;"><img id="fermer-les-salons" src="../img/fermer.png"></a>
            <h3 id="h3-salon-ps4"><a href="#" id="salon-ps4">SALON PS4</a></h3><p id="p-salon-ps4"></p>
        </p>
        <p>
            <h3 id="h3-salon-ps3"><a href="#" id="salon-ps3">SALON PS3</a></h3><p id="p-salon-ps3"></p>
        </p>
        <p>
            <h3><a href="#" id="salon-one">SALON ONE</a></h3><p id="p-salon-one"></p>
        </p>
        <p>
            <h3><a href="#" id="salon-360">SALONS 360</a></h3><p id="p-salon-360"></p>
        </p>
        <p>
            <h3><a href="#" id="salon-pc">SALONS PC</a></h3><p id="p-salon-pc"></p>
        </p>
        </div>
    </div>

    <div id="chat-box">

        <div class="chat-box-header">
            <p id="p-chat-box-header">
                <span id="span-fermer-le-salon" style="font-size:25px; font-family: myFirstFont; font-weight: bold;"></span>

            </p><a id="a-fermer-le-salon" href="#"><img id="img-fermer-le-salon" src="../img/fermer.png"></a>
        </div>

        <div class="chat-content">
            <ul id="ul-chat-content" class="chat-box-body">

            </ul>
        </div>

        <div class="chat-textarea">
            <input id="chat-message" placeholder="Saisissez votre message" class="form-control">
        </div>

    </div>

    <script>
        $(function() {
            var salonclique ;
            var iduser;
            var positionX;
            iduser = <?php echo $_SESSION['userid']?>;



            $( "a" ).click(function(event) {

                // Si on clique sur le bouton fermer de
                if($(this).attr('id') == 'a-fermer-les-salons') {
                    $( "#barre-latterale-droite" ).hide( "slow");
                    $( "#chat-box" ).hide( "slow");
                }
                // Si on clique sur le bouton fermer de
                else if($(this).attr('id') == 'a-fermer-le-salon') {
                    $( "#chat-box" ).hide( "slow");
                }
                else {


                    $("a").removeClass("active");
                    $(this).addClass("active");

                    // Effacer le membre du salon

                    $("#br-pour-chat").remove();

                    salonclique = $(this).attr('id');

                    if (salonclique == "salon-ps4") {
                        $('#span-fermer-le-salon').html('SALON PS3');
                    }

                    if (salonclique == "salon-ps3") {
                        $('#span-fermer-le-salon').html('SALON PS3');
                    }

                    if (salonclique == "salon-one") {
                        $('#span-fermer-le-salon').html('SALON ONE');
                    }

                    if (salonclique == "salon-360") {
                        $('#span-fermer-le-salon').html('SALON 360');
                    }

                    if (salonclique == "salon-pc") {
                        $('#span-fermer-le-salon').html('SALON PC');
                    }

                    charger();

                    // Récupération des utilisateurs connectés
                    $.ajax({

                        url: "recup-utilisateurs.php", // on donne l'URL du fichier de traitement

                        type: "POST", // la requête est de type POST

                        data: "idutilisateur=" + iduser + "&salonutilisateur=" + $(this).attr('id'),// et on envoie nos données

                        success: function (html) {
                            $("#br-pour-chat").remove();

                            if (salonclique == "salon-ps4") {
                                $("#p-salon-ps4").prepend(html);
                            }

                            if (salonclique == "salon-ps3") {
                                $("#p-salon-ps3").prepend(html);
                            }

                            if (salonclique == "salon-one") {
                                $("#p-salon-one").prepend(html);
                            }

                            if (salonclique == "salon-360") {
                                $("#p-salon-360").prepend(html);
                            }

                            if (salonclique == "salon-pc") {
                                $("#p-salon-pc").prepend(html);
                            }

                            // $("#ul-chat-content").animate({scrollTop: 4000},5000);

                        }

                    });



                    // faire apparaitre le box du chat

                    if (salonclique == "salon-ps4") {
                        $("#chat-box").show("slow").css("top", '0px');

                    }

                    if (salonclique == "salon-ps3") {
                        $("#chat-box").show("slow").css("top", '47px');
                    }

                    if (salonclique == "salon-one") {
                        $("#chat-box").show("slow").css("top", '76px');
                    }

                    if (salonclique == "salon-360") {
                        $("#chat-box").show("slow").css("top", '139px');
                    }

                    if (salonclique == "salon-pc") {
                        $("#chat-box").show("slow").css("top", '177px');
                    }

                }
            });

            // Dès qu'un utilisateur a fini de saisir un message et qu'il clique sur ENTER
            $('#chat-message').keypress(function(event){
                var keycode = (event.keyCode ? event.keyCode : event.which);
                if(keycode == '13'){
                    $.ajax({

                        url : "recup-messages.php", // on donne l'URL du fichier de traitement

                        type : "POST", // la requête est de type POST

                        data : "idutilisateur=" + $("#<?php echo $_SESSION['userid']?>").attr('id') + "&salonutilisateur=" + salonclique+ "&messageutilisateur=" + $('#chat-message').val(),// et on envoie nos données

                        success : function(html){
                            $("#ul-chat-content li").remove();
                            $('#chat-message').val('');
                            $("#ul-chat-content").prepend(html);
                            $("#ul-chat-content").scrollTop(4000);

                        },

                        error : function(resultat, statut, erreur){

                            //$("#ul-chat-content").prepend(erreur);
                        }

                    });
                }
            });

            function charger(){

                // Chargement des messages
                setTimeout( function(){
                    // on lance une requête AJAX
                    $.ajax({
                        url : "charger.php",
                        type : "POST", // la requête est de type POST

                        data : "salonutilisateur=" + salonclique,// et on envoie nos données

                        success : function(html){

                            $("#ul-chat-content li").remove();


                            $("#ul-chat-content").prepend(html);

                        },

                        error : function(resultat, statut, erreur){

                            //$("#ul-chat-content").prepend(erreur);
                        }
                    });



                    // Récupértaion des utlisateurs connectés
                    $.ajax({

                        url : "recup-nombre-connectes.php", // on donne l'URL du fichier de traitement

                        type : "POST", // la requête est de type POST

                        data : "idutilisateur=" + iduser + "&salonutilisateur=" + salonclique,// et on envoie nos données

                        success : function(html){
                            //$("#br-pour-chat").remove();
                            $("#ul-chat-content").scrollTop(4000);


                            if (html != '') {

                            $("#p-salon-one").empty();
                                // On vide tous les salons pour un nouvel affichage
                                // PS : ne pas utiliser de remove et de insertafter car le DOM ajoute un nouvel élément
                                // différent de l'id même si c'es le même. Exemple :On a utlisé cela : p-salon-ps4
                                // et après le remove et le insertafter, il va mettre p-salon-ps4-1
                                $("#p-salon-ps4").html("");
                                $("#p-salon-ps3").html("");
                                $("#p-salon-one").html("");
                                $("#p-salon-360").html("");
                                $("#p-salon-pc").html("");

                                if (salonclique == "salon-ps4") {
                                    $("#p-salon-ps4").append(html);
                                }

                                if (salonclique == "salon-ps3") {
                                    $("#p-salon-ps3").append(html);
                                }

                                if (salonclique == "salon-one") {
                                    $("#p-salon-one").prepend(html);
                                }

                                if (salonclique == "salon-360") {
                                    $("#p-salon-360").prepend(html);
                                }

                                if (salonclique == "salon-pc") {
                                    $("#p-salon-pc").prepend(html);
                                }

                            }

                        }

                    });
                    // chargement des gens qui viennnent nouvellement dans le salons
                    charger(); // on relance la fonction

                }, 5000); // on exécute le chargement toutes les 5 secondes

            }


        });

    </script>

    </body>
</html>
