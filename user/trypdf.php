<?php

session_start();

require_once '../databaseConnection.php';

// include autoloader
require_once 'dompdf/dompdf/autoload.inc.php';

// reference the Dompdf namespace
use Dompdf\Dompdf;

// instantiate and use the dompdf class
$dompdf = new Dompdf();

#######################  functions  #################################

function socialMedia($social,$socialName){
    if($social===''){
        $returnvalue='';
    }else{
        $returnvalue='
        <div class="contact" >
        <a href="'.$social.'" target="_blank" style="font-size:1.75em;color: rgb(212, 216, 219);font-weight:500;" >
            '.$socialName.'
        </a>
        </div>';
    }
    return $returnvalue ;
}

function checkValue($val,$state){
    if(isset($val)){
        $returnvalue=$val;
    }else{
        if($state){
            $returnvalue='';
        }else{
            $returnvalue=0;
        }
    }
    return $returnvalue ;
}

function starsValue($val){
    // excellent very good good average basic beginner
    if($val==5){
        $returnvalue='excellent';
    }elseif($val==4){
        $returnvalue='very good';
    }elseif($val==3){
        $returnvalue='good';
    }elseif($val==2){
        $returnvalue='average';
    }elseif($val==1){
        $returnvalue='basic';
    }else{
        $returnvalue='beginner';
    }
    return $returnvalue ;
}

function languages($langarray,$starsarray){
    $returnvalue='';
    for($i=0;$i<sizeof($langarray);$i++){
        $starval=checkValue($starsarray[$i],false);
        $returnvalue.='
        <div  class="language" >
            <h4>'.$langarray[$i].'</h4>
            <div class="main-rate" >
                <div class="rate" style="width: '.($starval*20).'%;" ></div>
            </div>
            <h4  class="h4-rate" >'.starsValue($starval).'</h4>
        </div>
        ';
    }
    return $returnvalue ;
}

function skillCheck($val){
    if($val==='skipped'){
        $returnvalue='none';
    }else{
        $returnvalue='flex';
    }

    return $returnvalue;
}

function skillCheck1($val){
    if($val==='skipped'){
        $returnvalue='none';
    }else{
        $returnvalue='block';
    }

    return $returnvalue;
}

function skillCheck2($val){
    if($val===''){
        $returnvalue='none';
    }else{
        $returnvalue='block';
    }

    return $returnvalue;
}

function skillColor($val,$defvalue){
    if($defvalue<=$val){
        $returnvalue='#0045ff';
    }else{
        $returnvalue='rgba(0, 0, 0, 0.8)';
    }
    return $returnvalue ;
}

function addJobdesc($jobarray){
    $returnvalue='';
    for($i=0;$i<sizeof($jobarray);$i++){
        $returnvalue.='<li>'.$jobarray[$i].'</li>';
    }
    return $returnvalue ;
}

function gsdate($val){
    $returnvalue='';
    if($val!=='0000-00-00'){
        $returnvalue.='
        <h4>graduated on '.$val.'</h4>
        ';
    }
    return $returnvalue ;
}

function typeline($val){
    if($val){
        $returnvalue='online course';
    }else{
        $returnvalue='offline course';
    }
    return $returnvalue ;
}

function cert($certarray){
    $returnvalue='';
    for($i=0;$i<sizeof($certarray);$i++){
        $returnvalue.='
        <div class="certificate" >
            <h1>'.$certarray[$i]->certName.'  '.$certarray[$i]->certDate.'</h1>
            <h4>'.typeline($certarray[$i]->online).' , '.$certarray[$i]->academyName.'</h4>
        </div>
        ';
    }
    return $returnvalue ;
}

#####################################################################

if(isset($_SESSION['user'])){
    if($_SESSION['user']->role === "user"){

        // // include autoloader
        // require_once 'dompdf/dompdf/autoload.inc.php';

        // // reference the Dompdf namespace
        // use Dompdf\Dompdf;

        // // instantiate and use the dompdf class
        // $dompdf = new Dompdf();




        $userID=$_SESSION['user']->id;

        $get = $database->prepare("SELECT * FROM cv  WHERE cv_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get->bindParam("cv_id",$userID);
        $get->execute();
        $cv = $get->fetchObject();

        $get2 = $database->prepare("SELECT * FROM cv2  WHERE cv1_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get2->bindParam("cv_id",$userID);
        $get2->execute();
        $cv2 = $get2->fetchObject();

        $get3 = $database->prepare("SELECT * FROM cv3  WHERE cv3_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get3->bindParam("cv_id",$userID);
        $get3->execute();
        $cv3 = $get3->fetchObject();

        $get4 = $database->prepare("SELECT * FROM cv4  WHERE cv4_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get4->bindParam("cv_id",$userID);
        $get4->execute();
        $json4 = $get4->fetchObject();
        $jsondata4=$json4->skillJSON;
        $cv4 = json_decode($jsondata4);

        $get5 = $database->prepare("SELECT * FROM cv5  WHERE cv5_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get5->bindParam("cv_id",$userID);
        $get5->execute();
        $json5 = $get5->fetchObject();
        $jsondata5=$json5->languagesJSON;
        $cv5 = json_decode($jsondata5);
        //echo sizeof($cv5->language);

        $get6 = $database->prepare("SELECT * FROM cv6  WHERE cv6_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get6->bindParam("cv_id",$userID);
        $get6->execute();
        $cv6 = $get6->fetchObject();
        $jsondata6=$cv6->summary;
        $cv6 = json_decode($jsondata6);

        $get7 = $database->prepare("SELECT * FROM cv7  WHERE cv7_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get7->bindParam("cv_id",$userID);
        $get7->execute();
        $cv7 = $get7->fetchObject();
        $descJSON=$cv7->jobdesc;
        $jobdesc=json_decode($descJSON);

        $get8 = $database->prepare("SELECT * FROM cv8  WHERE cv8_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get8->bindParam("cv_id",$userID);
        $get8->execute();
        $cv8 = $get8->fetchObject();

        $get9 = $database->prepare("SELECT * FROM cv9  WHERE cv9_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get9->bindParam("cv_id",$userID);
        $get9->execute();
        $json9 = $get9->fetchObject();
        $jsondata9=$json9->interestsJSON;
        $cv9 = json_decode($jsondata9);

        $get10 = $database->prepare("SELECT * FROM profilepicture  WHERE pic_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get10->bindParam("cv_id",$userID);
        $get10->execute();
        $cv10 = $get10->fetchObject();
        $file=$cv10->file;
        $fileType=$cv10->type;
        $picSRC = "data:" . $fileType . ";base64,".base64_encode($file);

        $get11 = $database->prepare("SELECT * FROM cv11  WHERE cv11_id=:cv_id ORDER BY id DESC LIMIT 1");
        $get11->bindParam("cv_id",$userID);
        $get11->execute();
        $json11 = $get11->fetchObject();
        $jsondata11=$json11->certJSON;
        $cv11 = json_decode($jsondata11);

        $getc = $database->prepare("SELECT * FROM color  WHERE c_id=:cv_id ORDER BY id DESC LIMIT 1");
        $getc->bindParam("cv_id",$userID);
        $getc->execute();
        $getColor = $getc->fetchObject();
        if($getColor->color==='grey'){
            echo "<script>
            var r = document.querySelector(':root');
            r.style.setProperty('--primary-color', '#141a29');
            r.style.setProperty('--secondary-color', '#000000');
            </script>";
        }

        //echo var_dump($jobdesc);
        //echo '<img src="' .$picSRC . '" width="300px" />';
        //echo $cv4->skill[0];

$html='
        <style>
        @import url("https://fonts.googleapis.com/css2?family=Dancing+Script&family=Playfair+Display:ital,wght@1,500&family=Roboto:wght@100&display=swap");
            
            *, *::before, *::after {
                box-sizing: border-box;
                font-family: Arial, Helvetica, sans-serif;
            }
            
            :root{
                --primary-font-size:1.75rem;
                --secondary-font-size:0.9rem;
                --third-font-size:1.5rem;
            
                --primary-color:#0b2468;
            }
            
            #main{
                
                width:100%;
            }

            .page-break {
                page-break-before: always;
            }
            
            .section1{
                /* width: 35%; */
                width:250px;
                /* min-height: 100vh; */
                background-color: #0b2468;
            }
            
            .section2{
                /* width: 75%; */
                min-height: 100vh;
                background-color: rgb(255, 255, 255);
            }
            
            
            #part1{
                align-items: center;
            }
            
            #part1 img{
                margin-top: 15px;
                display: block;
                margin-left: auto;
                margin-right: auto;
            }
            
            #part1 h4 , #part1 h1{
                text-align: center;
                margin: 0;
                padding: 0;
            }
            
            h1{   /* #part1 */
                font-size: var(--primary-font-size);
                font-weight: 500px;
                color: rgb(212, 216, 219);
                margin: 0;
                word-break: break-all
            }
            
            h4{   /* #part1 */
                font-size: var(--secondary-font-size);
                font-weight: 100px;
                color: rgb(164, 164, 164);
                margin: 0;
                word-break: break-all
            }
            
            
            .bar{
                background-color:rgb(0, 0, 69) ;
                margin: 30px 0 10px 0;
            }
            .bar h2{
                font-size: var(--third-font-size);
                font-weight: 200px;
                margin: 0 0 0 20px;
                padding: 5px 0;
                color: rgb(212, 216, 219);
            }
            
            .contact{
                
                margin: 20px 20px 0 20px ;
            }
            
            .contact h4 , .contact h1{
                margin: 0;
                padding: 0;
            }
            
            #part3{
                gap: 20px !important;
            }
            
            #part4{
                gap: 20px !important;
            }
            
            .language{

                padding: 4px 5%;
            }
            
            .language .h4-rate{
                text-align: right;
            }
            
            .main-rate{
                width: 100%;
                height: 15px;
                background-color: rgb(0, 0, 69);
            }
            
            .rate{
                height: 15px;
                background-color: aliceblue;
            }
            
            
            .container{
                width: 94%;
                margin: auto;
                padding: 10px 0 30px 0;
                border-bottom: 1px solid black;
            }
            
            .text{
                font-size: var(--secondary-font-size);
                font-weight: 500px;
                color: rgb(69, 69, 69);
                margin: 0;
                font-family: "Combo", cursive;
            }
            
            .container-bar{
                width: 94%;
                margin: auto;
                padding: 10px 0 10px 0;
                border-bottom: 1px solid black;
            }
            
            .container-bar h1{
                color: #003060;
            }
            
            
            
            .skill{
                width: 94%;
                margin: auto;
            }
            
            
            
            
            .work .work-date{
                width: 20%;
                margin-top: 20px;
            }
            
            .work .main-work{
                width: 80%;
                margin-top: 20px;
            }
            
            .main-work h1{
                font-size: var(--primary-font-size);
                font-weight: 500px;
                color: black;
                margin-bottom: 10px;
                padding: 0;
            }
            
            .main-work h4{
                font-size: var(--secondary-font-size);
                font-weight: 500px;
                color: rgb(33, 33, 33);
                margin-bottom: 30px;
                padding: 0;
            }
            
            .main-work ul{
                margin: 0;
                padding-left: 15px;
            }
            
            .main-work ul li{
                margin: 10px 0;
            }
            
            .education h1{
                font-size: var(--primary-font-size);
                font-weight: 500px;
                color: black;
                margin-bottom: 10px;
                padding: 0;
                text-align: center;
                margin-top: 5px;
            }
            
            .education h4{
                font-size: var(--secondary-font-size);
                font-weight: 500px;
                color: rgb(33, 33, 33);
                margin-bottom: 20px;
                padding: 0;
                text-align: center;
            }
            
            .certificate{
                margin:20px 0;
            }
            
            .certificate h1{
                font-size: var(--third-font-size);
                font-weight: 500px;
                color: black;
                margin-bottom: 10px;
                padding: 0;
                text-align: center;
                margin-top: 5px;
            }
            
            .certificate h4{
                font-size: var(--secondary-font-size);
                font-weight: 500px;
                color: rgb(33, 33, 33);
                margin-bottom: 20px;
                padding: 0;
                text-align: center;
            }
            
            .interests li {
                font-size: var(--secondary-font-size);
                font-weight: 500px;
                color: rgb(47, 43, 43);
                margin-bottom: 20px;
                padding: 0;
            }
            .stars{
                margin-bottom: 7px;
                margin-left: 100px;
            }
        </style>
        

        <table id="main" style="margin:auto;">
            <tr style="width:100%;" >
                <td class="section1 page-break">
                    <div id="part1" class="part1" >
                        <img src="' .$picSRC . '" width="200" style="display: block; margin-bottom: 10px; margin-top: 15px; padding:0 25px 0 25px;" >
                        <h1>'.$cv->firstName.' '.$cv->lastName.'</h1>
                        <h4>'.$cv->profession.'</h4>
                        <h4 style="margin-top: 15px; margin-bottom:-15px" >born in '.$cv->birth.'</h4>
                    </div>
                    <div class="bar" ><h2>Contact</h2></div>
                    <div class="part1" >
                        <div class="contact" >
                            <h1>Adress</h1>
                            <h4>'.$cv->city.','.$cv->country.','.$cv2->postalCode.'</h4>
                        </div>
                        <div class="contact" >
                            <h1>Phone</h1>
                            <h4>'.$cv2->phone.'</h4>
                        </div>
                        <div class="contact" >
                            <h1>Email</h1>
                            <h4>'.$cv2->email.'</h4>
                        </div>
                        '.socialMedia($cv2->twitter,'twitter').socialMedia($cv2->fcb,'facebook').socialMedia($cv2->github,'github').socialMedia($cv2->linkedin,'linkedin').'
                    </div>
                    


                    <!--
                    <div class="bar" ><h2>Languages</h2></div>
                    <div class="part1" id="part3" >
                        '.languages($cv5->language,$cv5->numofstars).'
                    </div>
                    <div class="bar" style="display:'.skillCheck($cv4->skill[0]).' !important;" ><h2>Software</h2></div>
                    <div class="part1" id="part4" style="display:'.skillCheck($cv4->skill[0]).' !important;" >
                        '.languages($cv4->skill,$cv4->numofstars).'
                    </div>
                    -->


                    
                </td>
                <td class="section2 page-break" >
                    <div class="container">
                        <p class="text" >'.$cv6->summary.'</p>
                    </div>
                    <div class="container-bar"><h1>Skills</h1></div>
                    <div class="container skills">
                        <div class="skill" >
                            <table>
                                <tr>
                                    <td>
                                        <p style="margin-top: 5px; padding:0" >&#9679 self-motivated</p>
                                        <p style="margin-top: 5px; padding:0" >&#9679 software-proficiency</p>
                                        <p style="margin-top: 5px; padding:0" >&#9679 dependable and responsible</p>
                                        <p style="margin-top: 5px; padding:0" >&#9679 critical thinking</p>
                                        <p style="margin-top: 5px; padding:0" >&#9679 multitasking abilities</p>
                                        <p style="margin-top: 5px; padding:0" >&#9679 training and development</p>
                                        <p style="margin-top: 5px; padding:0" >&#9679 excellent communication</p>
                                        <p style="margin-top: 5px; padding:0" >&#9679 problem-solving</p>
                                    </td>
                                    <td>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->self_motivated,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->self_motivated,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->self_motivated,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->self_motivated,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->self_motivated,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                        </div>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->software_proficiency,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->software_proficiency,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->software_proficiency,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->software_proficiency,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->software_proficiency,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                        </div>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->dependable_responsible,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->dependable_responsible,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->dependable_responsible,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->dependable_responsible,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->dependable_responsible,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                        </div>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->critical_thinking,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->critical_thinking,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->critical_thinking,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->critical_thinking,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->critical_thinking,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                        </div>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                        </div>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->training_development,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->training_development,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->training_development,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->training_development,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->training_development,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                        </div>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->excellent_communication,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->excellent_communication,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->excellent_communication,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->excellent_communication,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->excellent_communication,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg> 
                                        </div>
                                        <div class="stars" >
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->problem_solving,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->problem_solving,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->problem_solving,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->problem_solving,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                            <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->problem_solving,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                        </div>
                                    </td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    <div class="container-bar" style="display:'.skillCheck($jobdesc->jobdesc[0]).' !important;" ><h1>Work history</h1></div>
                    <div class="container work" style="display:'.skillCheck($jobdesc->jobdesc[0]).' !important;"  >
                        <div class="work-date" >
                            '.$cv7->start_date.'
                        </div>
                        <div class="main-work" >
                            <h1>'.$cv7->job_title.'</h1>
                            <h4>'.$cv7->employer.','.$cv7->country.','.$cv7->city.'</h4>
                            <ul>
                                '.addJobdesc($jobdesc->jobdesc).'
                            </ul>
                        </div>
                    </div>
                    <div class="container-bar"><h1>Education</h1></div>
                    <div class="container education" >
                        <h1>'.$cv8->degree.': '.$cv8->field_of_study.'</h1>
                        '.gsdate($cv8->g_s_date).'
                        <h4>'.$cv8->school_name.'-'.$cv8->school_location.'</h4>
                        <h4 style="word-break: break-word; ">I have excellent grades especially in computer science and mathematics</h4>
                    </div>
                    


                    <!--
                    <div class="container-bar" style="display:'.skillCheck1($cv11->certificate[0]->certName).' !important;" ><h1>Certifications</h1></div>
                    <div class="container" style="display:'.skillCheck1($cv11->certificate[0]->certName).' !important;" >
                        '.cert($cv11->certificate).'
                    </div>
                    <div class="container-bar" style="display:'.skillCheck2($cv9->interest[0]).' !important;" ><h1>Hobbies</h1></div>
                    <div class="container interests " style="display:'.skillCheck2($cv9->interest[0]).' !important;" >
                        <ul>
                            '.addJobdesc($cv9->interest).'
                        </ul>
                    </div>
                    -->
                   
                    
                </td>

            </tr>




            <!--
            <tr style="width:100%;" >
                <td class="section1">
                    <div class="bar" style="margin-top:0;" ><h2>Languages</h2></div>
                    <div class="part1" id="part3" >
                        '.languages($cv5->language,$cv5->numofstars).'
                    </div>
                    <div class="bar" style="display:'.skillCheck($cv4->skill[0]).' !important;" ><h2>Software</h2></div>
                    <div class="part1" id="part4" style="display:'.skillCheck($cv4->skill[0]).' !important;" >
                        '.languages($cv4->skill,$cv4->numofstars).'
                    </div>
                </td>
                <td class="section2" >
                    
                    
                    <div class="container-bar" style="display:'.skillCheck1($cv11->certificate[0]->certName).' !important;" ><h1>Certifications</h1></div>
                    <div class="container" style="display:'.skillCheck1($cv11->certificate[0]->certName).' !important;" >
                        '.cert($cv11->certificate).'
                    </div>
                    <div class="container-bar" style="display:'.skillCheck2($cv9->interest[0]).' !important;" ><h1>Hobbies</h1></div>
                    <div class="container interests " style="display:'.skillCheck2($cv9->interest[0]).' !important;" >
                        <ul>
                            '.addJobdesc($cv9->interest).'
                        </ul>
                    </div>
                    
                </td>
            </tr>
            -->
            
        </table>
        
        <script>
            var sections2=document.getElementById("gg");
            section2.innerHTML+="shsdhsdhgsddgdgdgdgdgdgdgdgdgdgd"
        </script>
';

$try=' hi
<div class="stars" >
<svg xmlns="http://www.w3.org/2000/svg"  id="star10" width="24" height="24" style="fill:'.skillColor($cv3->self_motivated,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
</div>';
// echo $html;

$dompdf->loadHtml($html);

// (Optional) Setup the paper size and orientation
$dompdf->setPaper('A4', 'portrait');

// Render the HTML as PDF
$dompdf->render();

// Output the generated PDF to Browser
$dompdf->stream("",array("Attachment" => false));

    }else{
        header("location:http://localhost/server/login.php",true); 
        die("");
    }
}else{
    header("location:http://localhost/server/login.php",true); 
    die(""); 
}




// <!--
//                             <ul>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 self-motivated</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->self_motivated,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->self_motivated,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->self_motivated,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->self_motivated,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->self_motivated,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                     </div>
//                                 </li>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 software-proficiency</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->software_proficiency,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->software_proficiency,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->software_proficiency,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->software_proficiency,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->software_proficiency,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                     </div>
//                                 </li>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 dependable and responsible</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->dependable_responsible,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->dependable_responsible,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->dependable_responsible,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->dependable_responsible,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->dependable_responsible,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                     </div>
//                                 </li>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 critical thinking</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->critical_thinking,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->critical_thinking,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->critical_thinking,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->critical_thinking,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->critical_thinking,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                     </div>
//                                 </li>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 multitasking abilities</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->multitasking_abilities,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                     </div>
//                                 </li>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 training and development</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->training_development,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->training_development,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->training_development,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->training_development,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->training_development,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                     </div>
//                                 </li>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 excellent communication</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->excellent_communication,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->excellent_communication,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->excellent_communication,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->excellent_communication,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->excellent_communication,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg> 
//                                     </div>
//                                 </li>
//                                 <li>
//                                     <p style="margin: 0; padding:0" >&#9679 problem-solving</p>
//                                     <div>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" onclick="starClick(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($cv3->problem_solving,1).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" onclick="starClick(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($cv3->problem_solving,2).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)" onclick="starClick(2,1)" width="24" id="star12" height="24" style="fill:'.skillColor($cv3->problem_solving,3).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" onclick="starClick(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($cv3->problem_solving,4).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                         <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)" onclick="starClick(4,1)" width="24" id="star14" height="24" style="fill:'.skillColor($cv3->problem_solving,5).'"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
//                                     </div>
//                                 </li>
//                             </ul>
//                             -->