<?php
session_start();
require_once 'databaseConnection.php';
require_once "websiteURL.php";
function getTables($database,$user_id){
    global $getResumeHeading, $getSummary, $getLanguage,$getContact,$getColor
    ,$skills,$projects,$services,$work,$edu,$cert,$getContactPic,$getBg,$getResume;

        $getResumeHeading = $database->prepare("SELECT * FROM cv  WHERE cv_id=:id ");
        $getResumeHeading->bindParam("id",$user_id);
        $getResumeHeading->execute();

        $getSummary = $database->prepare("SELECT * FROM cv6  WHERE cv6_id=:id ");
        $getSummary->bindParam("id",$user_id);
        $getSummary->execute();

        $getLanguage = $database->prepare("SELECT * FROM cv5  WHERE cv5_id=:id ");
        $getLanguage->bindParam("id",$user_id);
        $getLanguage->execute();

        $getContact = $database->prepare("SELECT * FROM cv2  WHERE cv1_id=:id ");
        $getContact->bindParam("id",$user_id);
        $getContact->execute();

        $getColor = $database->prepare("SELECT * FROM color  WHERE user_id=:id ");
        $getColor->bindParam("id",$user_id);
        $getColor->execute();

        $getResumeSkills = $database->prepare("SELECT * FROM cv4  WHERE cv4_id=:id ");
        $getResumeSkills->bindParam("id",$user_id);
        $getResumeSkills->execute();
        if($getResumeSkills->rowCount()>0){
            $skills=json_decode($getResumeSkills->fetchObject()->skillJSON);
        }else{
            $skills="";
        }

        $getPortf = $database->prepare("SELECT * FROM projects  WHERE user_id=:id ");
        $getPortf->bindParam("id",$user_id);
        $getPortf->execute();
        if($getPortf->rowCount()>0){
            $projects=json_decode($getPortf->fetchObject()->projects_data);
        }else{
            $projects="";
        }

        $getServices = $database->prepare("SELECT * FROM services  WHERE user_id=:id ");
        $getServices->bindParam("id",$user_id);
        $getServices->execute();
        if($getServices->rowCount()>0){
            $services=json_decode($getServices->fetchObject()->services_data);
        }else{
            $services="";
        }

        $getWork = $database->prepare("SELECT * FROM cv7  WHERE cv7_id=:id ");
        $getWork->bindParam("id",$user_id);
        $getWork->execute();
        if($getWork->rowCount()>0){
            $work=json_decode($getWork->fetchObject()->job_data);
        }else{
            $work="";
        }

        $getEdu = $database->prepare("SELECT * FROM cv8  WHERE cv8_id=:id ");
        $getEdu->bindParam("id",$user_id);
        $getEdu->execute();
        if($getEdu->rowCount()>0){
            $edu=json_decode($getEdu->fetchObject()->edu_data);
        }else{
            $edu="";
        }

        $getCert = $database->prepare("SELECT * FROM cv11  WHERE cv11_id=:id ");
        $getCert->bindParam("id",$user_id);
        $getCert->execute();
        if($getCert->rowCount()>0){
            $cert=json_decode($getCert->fetchObject()->certJSON)->certificate;
        }else{
            $cert="";
        }

        $getContactPic = $database->prepare("SELECT * FROM profilepicture  WHERE pic_id=:cv_id ORDER BY id DESC LIMIT 1");
        $getContactPic->bindParam("cv_id",$user_id);
        $getContactPic->execute();

        $getBg = $database->prepare("SELECT * FROM img_vid  WHERE user_id=:cv_id ORDER BY id DESC LIMIT 1");
        $getBg->bindParam("cv_id",$user_id);
        $getBg->execute();

        $getResume = $database->prepare("SELECT * FROM resume  WHERE cv_id=:cv_id ORDER BY id DESC LIMIT 1");
        $getResume->bindParam("cv_id",$user_id);
        $getResume->execute();
}
$typeOfVisitor=null;
if(isset($_GET['token'])){
    $user_id=substr($_GET["token"],strrpos($_GET["token"],".")+1);
    if(isset($_SESSION['user'])){
        if($_SESSION['user']->firstName.'.'.$_SESSION['user']->lastName.".".$_SESSION['user']->id==$_GET['token']){  
            $typeOfVisitor="user";    
            $getUser = $database->prepare("SELECT * FROM users  WHERE id=:id ");
            $getUser->bindParam("id",$user_id);
            $getUser->execute();
            if($getUser->rowCount()>0){
               getTables($database,$user_id);
    
                if($getResumeHeading->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getSummary->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getLanguage->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getContact->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getColor->rowCount()==0){
                    header('Location: login.php' , true);
                }else{
                    $ResumeHeading=$getResumeHeading->fetchObject() ;
                    $summary=json_decode($getSummary->fetchObject()->summary)->summary ;
                    $languages=json_decode($getLanguage->fetchObject()->languagesJSON);
                    $contact=$getContact->fetchObject();
                    $color=json_decode($getColor->fetchObject()->color);

                    $getContactPicObj = $getContactPic->fetchObject();
                    $file=$getContactPicObj->file;
                    $fileType=$getContactPicObj->type;
                    $contactPicSRC = "data:" . $fileType . ";base64,".base64_encode($file);

                    $getBgObj = $getBg->fetchObject();
                    $file=$getBgObj->file;
                    $fileType=$getBgObj->type;
                    $bgPicSRC = "data:" . $fileType . ";base64,".base64_encode($file);

                    if($getResume->rowCount()>0){
                        $getResumeObj = $getResume->fetchObject();
                        $file=$getResumeObj->file;
                        $fileType=$getResumeObj->type;
                        $resumeSRC = "data:" . $fileType . ";base64,".base64_encode($file);
                    }

                }
            }else{
                header('Location: login.php' , true);
            }
        }else{
            $getUser = $database->prepare("SELECT * FROM users  WHERE id=:id ");
            $getUser->bindParam("id",$user_id);
            $getUser->execute();
            if($getUser->rowCount()>0){
                getTables($database,$user_id);
                if($getResumeHeading->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getSummary->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getLanguage->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getContact->rowCount()==0){
                    header('Location: login.php' , true);
                }elseif($getColor->rowCount()==0){
                    header('Location: login.php' , true);
                }else{
                    $ResumeHeading=$getResumeHeading->fetchObject() ;
                    $summary=json_decode($getSummary->fetchObject()->summary)->summary ;
                    $languages=json_decode($getLanguage->fetchObject()->languagesJSON);
                    $contact=$getContact->fetchObject();
                    $color=json_decode($getColor->fetchObject()->color);

                    $getContactPicObj = $getContactPic->fetchObject();
                    $file=$getContactPicObj->file;
                    $fileType=$getContactPicObj->type;
                    $contactPicSRC = "data:" . $fileType . ";base64,".base64_encode($file);

                    $getBgObj = $getBg->fetchObject();
                    $file=$getBgObj->file;
                    $fileType=$getBgObj->type;
                    $bgPicSRC = "data:" . $fileType . ";base64,".base64_encode($file);

                    if($getResume->rowCount()>0){
                        $getResumeObj = $getResume->fetchObject();
                        $file=$getResumeObj->file;
                        $fileType=$getResumeObj->type;
                        $resumeSRC = "data:" . $fileType . ";base64,".base64_encode($file);
                    }
                }
            }else{
                header('Location: login.php' , true);
            }
        }
    }else{
        $typeOfVisitor="stranger";
        $user_id=substr($_GET["token"],strrpos($_GET["token"],".")+1);
        $getUser = $database->prepare("SELECT * FROM users  WHERE id=:id ");
        $getUser->bindParam("id",$user_id);
        $getUser->execute();
        if($getUser->rowCount()>0){
            getTables($database,$user_id);
            if($getResumeHeading->rowCount()==0){
                header('Location: login.php' , true);
            }elseif($getSummary->rowCount()==0){
                header('Location: login.php' , true);
            }elseif($getLanguage->rowCount()==0){
                header('Location: login.php' , true);
            }elseif($getContact->rowCount()==0){
                header('Location: login.php' , true);
            }elseif($getColor->rowCount()==0){
                header('Location: login.php' , true);
            }else{
                $ResumeHeading=$getResumeHeading->fetchObject() ;
                $summary=json_decode($getSummary->fetchObject()->summary)->summary ;
                $languages=json_decode($getLanguage->fetchObject()->languagesJSON);
                $contact=$getContact->fetchObject();
                $color=json_decode($getColor->fetchObject()->color);

                $getContactPicObj = $getContactPic->fetchObject();
                    $file=$getContactPicObj->file;
                    $fileType=$getContactPicObj->type;
                    $contactPicSRC = "data:" . $fileType . ";base64,".base64_encode($file);

                    $getBgObj = $getBg->fetchObject();
                    $file=$getBgObj->file;
                    $fileType=$getBgObj->type;
                    $bgPicSRC = "data:" . $fileType . ";base64,".base64_encode($file);

                    if($getResume->rowCount()>0){
                        $getResumeObj = $getResume->fetchObject();
                        $file=$getResumeObj->file;
                        $fileType=$getResumeObj->type;
                        $resumeSRC = "data:" . $fileType . ";base64,".base64_encode($file);
                    }
            }
        }else{
            header('Location: login.php' , true);
        }
    }
}else{
    header('Location: login.php' , true);
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

function skillColor($val,$defvalue){
    if($defvalue<=$val){
        $returnvalue='var(--secondaryColor)';
    }else{
        $returnvalue='var(--primaryColor)';
    }
    return $returnvalue ;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PX-Turing</title>
</head>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Combo&family=Dancing+Script&family=Playfair+Display:ital,wght@1,500&family=Roboto:wght@100&display=swap');
*, *::before, *::after {
    box-sizing: border-box;
    /* font-family: Arial, Helvetica, sans-serif; */
  }

  :root{
    --primaryColor:<?php echo $color->primaryColor  ?>;
    --secondaryColor:<?php echo $color->secondaryColor  ?>; /*#3498db */
    --secondaryColorOpacity:<?php echo $color->secondaryColorOpacity  ?>;/*#3498db7d */
    --secondaryColorOpacity1:<?php echo $color->secondaryColorOpacity1  ?>;/*#3498db33 */
    --forthColor:#7f7f7f;
    --thirdColor:rgb(22, 21, 21);
    --thirdColorOpacity:rgba(22, 21, 21, 0.8);
    --primary-font:2em;
    --secondary-font:1.4em;
    --header-height:60px;
    --a-opacity:0;
  }

  body {
    margin: 0;
  }
  
  .full-screen-container {
    /* background-image: url("landscape1.jpg");
    background-position: center;
    background-repeat: no-repeat;
    background-attachment: fixed;
    background-size: cover; */

    min-height:100vh ;
    width: 100%;
    position: relative;


    display: flex;
    justify-content: flex-start;
    align-items: center;
    flex-direction: column;
    gap:0px;
  }
  #bg-vid{
    object-fit: cover;
    width: 100%;
    height: 100%;
    position: fixed;
    top:0;
    left:0;
  }

  header{
    width: 100%;
    height:var(--header-height);
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-direction: row;
    padding: 30px 0;
    position: fixed;
    top: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0);
    transition: background-color 1s;
    z-index: 100;
  }

  #nav-phone{
    width: 100vw;
    height: 100vh;
    position: fixed;
    top: 0;
    left: -100vw;
    background: rgba(0, 0, 0, 0.8);
    transition: left 1s;
    z-index: 101;
  }
  #nav-phone nav{
    width: 100%;
    height:100%;
    display: flex;
    justify-content: space-around;
    align-items: center;
    flex-direction: column;
    gap:10px;
  }

  #nav-phone nav p {
    margin: 0 ;
    padding: 0;
  }

  header #svg-nav{
    padding-right: 30px;
    display: none;
    cursor: pointer;
  }

  header #name-container span{
    padding-left: 30px;
    color:var(--secondaryColor);
    font-size:var(--primary-font) ;
  }

  header #name-container p{
    display: inline-block;
    color:var(--primaryColor);
    font-size:var(--primary-font) ;
    font-weight: 500;
    cursor: pointer;
  }

  header nav {
    padding-right: 30px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    gap:20px;
  }

  header nav p{
    color:var(--primaryColor);
    font-size:var(--secondary-font) ;
    cursor: pointer;
    font-weight:400;
  }

  #nav-phone nav p{
    color:var(--primaryColor);
    font-size:var(--primary-font) ;
    cursor: pointer;
    font-weight:600;
  }

  #nav-phone nav p:hover {
    color:var(--secondaryColor) ;
  }

  header nav p:hover {
    color:var(--secondaryColor) ;
  }

  #content-container{
    width:100%;
    height:100vh;
    display: flex;
    justify-content: flex-start;
    align-items: center;
    z-index: 2;
    position: relative;
  }

  #content-container div {
    display: flex;
    justify-content: center;
    align-items: flex-start;
    flex-direction: column;
    gap:10px;
    margin-left: 40px;
  }

  #content-container #occupation{
    font-size: 1.8em;
    color:var(--primaryColor);
    margin: 0;
  }
  #content-container #birth{
    font-size: 1.1em;
    color:var(--primaryColor);
    margin: 0;
  }
  
  #content-container #name-country{
    font-size: 2.8em;
    color:var(--primaryColor);
    margin: 0;
    font-weight: 600;
    word-break: keep-all;
    /* font-family: 'Combo', cursive; */
    /* font-family: 'Dancing Script', cursive; */
    font-family: 'Playfair Display', serif;
    /* font-family: 'Roboto', sans-serif; */
  }

  
  #content-container #name-country span{
    color:var(--secondaryColor);
  }


  #about{
    width:100%;
    min-height:calc( 100vh - var(--header-height) ) ;
    display: flex;
    flex-direction: row;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
    gap: 50px;
    background-color: var(--thirdColor);
    padding: 50px;
    z-index: 2;
  }
  #about #about-image{
    width: 80%;
    max-width: 350px;
    /* padding-left: 50px; */
  }
  #about #about-image img{
    border-radius:3px ;
    width: 100%;
  }

  #about #about-container{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    width: 90%;
    max-width: 750px;
    /* padding-right: 50px; */
  }

  #about #about-container #about-nav{
    display: flex;
    justify-content: center;
    align-items: center;
    gap:10px;
    padding: 10px;
    margin: 15px;
    background: rgba(255, 255, 255, 0.063);;
  }

  #about #about-container #about-nav p{
    color:var(--primaryColor);
    font-size: var(--secondary-font);
    cursor: pointer;
    margin: 0;
  }

  #about #about-container #about-nav .p-focuced{
    border-bottom: solid 2px var(--secondaryColor);
  }

  #about #about-container #about-me{
    text-align:center;
    font-size: var(--primary-font);
    color:var(--secondaryColor);
    margin-bottom: 10px;
  }

  #about #about-container #summary{
    text-align:start;
    font-size: var(--secondary-font);
    color:var(--primaryColor);
  }

  #edu-part{
    display: none;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }

  #certificate-part{
    display: none;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }
  #exp-part{
    display: flex;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }
  #lang-part{
    display: none;
    justify-content: center;
    align-items: center;
    flex-wrap: wrap;
  }
  

  #about #about-container .exp-edu-certificate{
    width: 100%;
    gap:10px;
    padding: 10px;
    /* background-color: var(--secondaryColorOpacity1); */
    border-radius: 5px;
  }

  #about #about-container .exp-edu-certificate .exp-edu-part{
    width:270px;
    padding: 6px 4px;
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: row;
    gap:5px;
    background-color: var(--secondaryColorOpacity);
    border-radius: 5px;
  }

  #about #about-container .exp-edu-certificate .exp-edu-part .work-date{
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    align-items: center;
    color :var(--primaryColor);
    gap:50px;
    width: 90px;
    padding-right: 3px;
  }
  #about #about-container .exp-edu-certificate .exp-edu-part .work-date p{
    margin: 0;
  }

  #about #about-container .exp-edu-certificate .exp-edu-part .work-detail{
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: flex-start;
    color :var(--primaryColor);
    gap:10px;
    width: 200px;
    border-left: solid 2px black;
    padding-left: 5px;
  }
  #about #about-container .exp-edu-certificate .exp-edu-part .work-detail p{
    margin: 0;
  }
  
  #about #about-container .exp-edu-certificate .exp-edu-part .work-detail .work-detail-occup{
    font-size:1.5rem;
    font-weight: 600;
  }


  #services{
    background-color: var(--forthColor);
    width: 100%;
    /* min-height:calc( 100vh - var(--header-height) ) ; */
    padding:3% ;
    display: <?php if($services!="") { echo "flex"; } else echo "none"  ?>;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap: 20px;
    z-index: 2;
  }

  #services #My-services p{
    font-size: var(--primary-font);
    color: var(--thirdColor);
    font-weight: 900;
  }

  #services #services-div{
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    align-items: center;
    width: 90%;
    gap: 20px;
  }

  #services #services-div .service{
    padding: 20px;
    background-color: var(--thirdColor);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    gap:20px;
    border-radius: 5px;
    width:340px;
  }
  #services #services-div .service:hover{
    background-color: var(--thirdColorOpacity);
  }

  #services #services-div .service h3{
    font-size: var(--primary-font);
    color:var(--secondaryColor);
    margin: 0;
  }

  #services #services-div .service p{
    font-size: 1em;
    color:var(--primaryColor);
    margin: 0;
  }




  .language{
    display: flex;
    flex-direction: column;
    gap: 2px;
    padding: 10px 10px;
    background: rgba(255, 255, 255, 0.063);
    }

    .language h4{
        color:var(--primaryColor);
        margin: 0;
    }

    .language .h4-rate{
        text-align: end;
    }

    .main-rate{
        width: 200px;
        height: 15px;
        background-color: var(--primaryColor);
    }

    .rate{
        height: 15px;
        background-color:var(--secondaryColor);
    }


    #skills{
        width: 100%;
        background-color: var(--thirdColor) ;
        display: <?php if($skills!="") { echo "flex"; } else echo "none"  ?>;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        gap:15px;
        padding: 40px 30px;
        z-index: 2;
        border-top: 20px solid  var(--forthColor) ;
    }

    #skills #skills-p p{
        font-size: var(--primary-font);
        font-weight: 600;
        color: var(--secondaryColor);
        margin-bottom: 20px;
        padding:0;
    }

    #skills .skill p {
        font-size: var(--secondary-font);
        color: var(--primaryColor);
        margin: 0;
        padding:0;
        word-break: break-all;
        max-width: 150px;
    }
    .skill{
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        align-items: center;
        gap: 10px;
        width: 90%;
        max-width: 300px;
    }







    #portfolio{
        background-color: var(--forthColor);
        width: 100%;
        /* min-height:calc( 100vh - var(--header-height) ) ; */
        position: relative;
        z-index: 2;
        display: <?php if($projects=="") {echo 'none';}else echo "block"  ?>;
        border-top: 20px solid var(--thirdColor)  ;
    }
    #portfolio h1{
        font-size: var(--primary-font);
        color: var(--thirdColor);
        font-weight: 700;
        text-align: center;
        margin: 20px 0;
    }

    #left{
        /* width: 50px;
        height: 50px; */
        position: absolute;
        top: 50%;
        left:10px;
        transform: translateY(-50%);
        z-index: 20;
        cursor: pointer;
        opacity: 0.5;

    }
    #right{
        /* width: 50px;
        height: 50px; */
        position: absolute;
        top: 50%;
        right:10px;
        transform: translateY(-50%);
        z-index: 20;
        cursor: pointer;
    }



    #portfolio-div{
        margin: auto;
        width: 100%;
        display: flex;
        overflow-x: hidden;
        background-color: var(--forthColor);
        position: relative;
    }
    .article{
        margin: 20px 10px;
        flex: 0 0 calc(100% / 3 - 26px);
        /* padding: 20px; */
        height: 500px;
        border-radius: 10px;
        background: var(--thirdColor);
        position: relative;
    }
    .article img{
        width: 100%; 
        height:100%; 
        object-fit: cover; 
        border-radius: 10px;
    }
    .article a{
       text-decoration: none;
       position: absolute;
       top:0;
       left:0;
       width: 100%;
       height:100%;
       padding: 30px;
       display: flex;
       flex-direction: column;
       justify-content: center;
       align-items: center;
       gap:30px;
       border-radius: 10px;
       background-color:var(--thirdColorOpacity) ;
       opacity: var(--a-opacity); /* GG */
       transition: opacity 0.5s;
    }
    .article a:hover{
        opacity: 1;
    }
    .article a h2{
        font-size: var(--primary-font);
        color: var(--primaryColor);
        margin:0;
        font-weight: 600;
    }
    .article a p{
        font-size: 1.2em;
        color: var(--primaryColor);
        margin: 0;
    }
    .article:nth-child(1) {
        transition: margin-left 1s;
        margin-left:20px;
    }



    #contact{
        width: 100%;
        min-height:calc( 100vh - var(--header-height) ) ;
        background-color: var(--thirdColor) ;
        display: flex;
        justify-content: center;
        align-items:center;
        flex-direction: column;
        gap:20px;
        z-index: 2;
        border-top: 20px solid var(--forthColor)  ;
        padding-bottom: 42px;
    }

    #contact-title{
        font-size: var(--primary-font);
        font-weight: 600;
        color: var(--secondaryColor);
        padding:0;
        padding-top:40px; 
    }

    #contact-container{
        width: 100%;
        padding: 20px;
        display: flex;
        justify-content: center;
        align-items:center;
        flex-direction: row;
        flex-wrap: wrap;
        gap:50px;
    }

    #contact-contact{
        display: flex;
        justify-content: center;
        align-items:center;
        flex-direction: column;
        gap:10px;
    }

    
    #contact-contact div{
        display: flex;
        justify-content: center;
        align-items:center;
        flex-direction: row;
        flex-wrap: wrap;
        gap:20px;
    }

    #contact-contact div p{
        font-size: var(--secondary-font);
        color:var(--primaryColor);
        word-break: break-all;
    }

    #contact-contact #download-cv{
        text-decoration: none;
        font-size: var(--secondary-font);
        color: var(--thirdColor);
        background-color: var(--secondaryColor);
        padding: 10px;
        border-radius: 5px;

    }
    #contact-contact #download-cv:hover{
        opacity: 0.9;
    }
    
    #contact-contact #social-media svg:hover {
        animation: animate 1s linear infinite;
    }
    @keyframes animate{
        0%{
            transform: scale(1);
        }
        50%{
            transform: scale(1.3);
        }
        100%{
            transform: scale(1);
        }
    }

    #contact-form {
        width: 100%;
        max-width: 600px;
    }
    #contact-form form {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        gap:20px;
    }

    #contact-form form input , textarea{
        width: 95%;
        border:none;
        outline:none ;
        font-size:1.2em;
        padding: 15px;
        color: var(--thirdColor);
        border:2px solid rgba(0, 0, 0, 0);
    }
    #contact-form form input:focus , textarea:focus{
      border:2px solid var(--secondaryColor);
    }
    #contact-form form input{
        height: 30px;
    }

    #contact-form form textarea{
        max-width: calc(600px * .95);
        min-width: 150px;
        height: 200px;

    }
    #contact-form form button{
        width: 95%;
        padding:.4em.4em;
        border:none;
        outline:none ;
        font-size: var(--secondary-font);
        color: var(--thirdColor);
        background-color: var(--secondaryColor);
        transition: opacity 1s;
        cursor: pointer;
    }

    #contact-form form button:hover{
        opacity: 0.8;
    }

    .edit{
        position: absolute;
    }
    .try{
        position: fixed;
        z-index: 100;
        animation: animate1 15s linear infinite;
    }
    .edit,.try{
        bottom: 10%;
        right: 0;
        /* transform: translateY(-50%); */
        padding: 10px 15px 10px 20px;
        border-radius: 20px 0 0 20px;
        background-color: var(--secondaryColor);
        cursor: pointer;
    }

    @keyframes animate1{
        0%{
            transform: translateX(0%);
        }
        30%{
            transform: translateY(0%);
        }
        40%{
            transform: translateX(100%);
        }
        80%{
            transform: translateX(100%);
        }
        100%{
            transform: translateX(0%);
        }
    }


























    @media only screen and (max-width: 800px) {
        .article{
            flex: 0 0 calc(50% - 30px);
        }
    }

  @media only screen and (max-width: 700px) {
    header nav{
        display: none;
    }
    
    header #svg-nav {
        display: block;
    }
    #about #about-container #about-nav p{
        font-size: 1.2em;
    }
  }

  @media only screen and (max-width: 500px) {
    .article{
        flex: 0 0 calc(100% - 40px);
    }
  }

  @media only screen and (max-width: 389px) {
    #about #about-container #about-nav{
        flex-direction: column;
      }
      .skill{
        flex-direction: column;
      }
  }

  @media only screen and (max-width: 300px) {
    #content-container #name-country{
        word-break: break-all;
        font-size: 1.7em;
    }
  }

</style>
<body>
    <?php 
    if ($typeOfVisitor==="stranger")
    echo '
    <div id="try" class="try" >
        <a href="'.$webURL.'/user/portfHeading.php" style="text-decoration:none; color:var(--thirdColor);font-size:1.5em;font-weight:700; " >
            Create your <br> own portfolio !
        </a>
    </div>' ;
    ?>
    <div id="nav-phone" >
        <nav >
            <p onclick="navClicked(this,1)" >home</p>
            <p onclick="navClicked(this,2)">about</p>
            <?php if ($services!=""){ echo  '<p onclick="navClicked(this,3)">services</p>' ;}else{ echo  '<p  style="display:none;"  ></p>' ;} ?>
            <?php if ($skills!="") {echo  '<p onclick="navClicked(this,4)">skills</p>' ;}else{ echo  '<p  style="display:none;"></p>' ;} ?>
            <?php if ($projects!="") {echo  '<p onclick="navClicked(this,5)">portfolio</p>';}else{ echo  '<p style="display:none;" ></p>' ;}  ?>
            <p onclick="navClicked(this,6)">contact</p>
            <svg style="margin-bottom:20px; cursor:pointer;" onclick="navClick()" fill="var(--secondaryColor)" width="60" height="60" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 14.545L1.455 16 8 9.455 14.545 16 16 14.545 9.455 8 16 1.455 14.545 0 8 6.545 1.455 0 0 1.455 6.545 8z" fill-rule="evenodd"/>
            </svg>
        </nav>
    </div>
    <div class="full-screen-container"> <!--style="background-image: url("landscape1.jpg");background-position: center;background-repeat: no-repeat;background-attachment: fixed;background-size: cover;"-->
        <img  id="bg-vid" src='<?php echo $bgPicSRC ?>' >
        <!-- <video autoplay loop muted id="bg-vid" >
            <source src="lands2.mp4" type="video/mp4">
            Sorry, your browser doesn't support embedded videos.
        </video> -->
        <!-- header -->
        <header id="main_header">
            <div id="name-container" ><span><?php echo strtoupper($ResumeHeading->firstName[0]) ?></span><p><?php echo substr($ResumeHeading->firstName,1) ?>.</p></div>
            <nav id="nav" >
                <p onclick="navClicked(this,1)" >home</p>
                <p onclick="navClicked(this,2)">about</p>
                <?php if ($services!=""){ echo  '<p onclick="navClicked(this,3)">services</p>' ;}else{ echo  '<p  style="display:none;" ></p>' ;} ?>
                <?php if ($skills!="") {echo  '<p onclick="navClicked(this,4)">skills</p>' ;}else{ echo  '<p  style="display:none;" ></p>' ;} ?>
                <?php if ($projects!="") {echo  '<p onclick="navClicked(this,5)">portfolio</p>';}else{ echo  '<p  style="display:none;" ></p>' ;}  ?>
                <p onclick="navClicked(this,6)">contact</p>
            </nav>
            <div id="svg-nav" onclick="navClick()" ><svg fill="var(--primaryColor)" width="40px" height="40px" viewBox="0 0 52 52" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg"><path d="M50,12.5H2a2,2,0,0,1,0-4H50a2,2,0,0,1,0,4Z"/><path d="M50,28H2a2,2,0,0,1,0-4H50a2,2,0,0,1,0,4Z"/><path d="M50,43.5H2a2,2,0,0,1,0-4H50a2,2,0,0,1,0,4Z"/></svg></div>
        </header>
        <!-- main content container-->
        <section  id="content-container">
            <?php 
            if($typeOfVisitor==="user"){
                echo '
                <div id="edit" class="edit" >
                    <a href="'.$webURL.'/user/portfHeading.php" >
                        <svg width="50px" height="50px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M21.1213 2.70705C19.9497 1.53548 18.0503 1.53547 16.8787 2.70705L15.1989 4.38685L7.29289 12.2928C7.16473 12.421 7.07382 12.5816 7.02986 12.7574L6.02986 16.7574C5.94466 17.0982 6.04451 17.4587 6.29289 17.707C6.54127 17.9554 6.90176 18.0553 7.24254 17.9701L11.2425 16.9701C11.4184 16.9261 11.5789 16.8352 11.7071 16.707L19.5556 8.85857L21.2929 7.12126C22.4645 5.94969 22.4645 4.05019 21.2929 2.87862L21.1213 2.70705ZM18.2929 4.12126C18.6834 3.73074 19.3166 3.73074 19.7071 4.12126L19.8787 4.29283C20.2692 4.68336 20.2692 5.31653 19.8787 5.70705L18.8622 6.72357L17.3068 5.10738L18.2929 4.12126ZM15.8923 6.52185L17.4477 8.13804L10.4888 15.097L8.37437 15.6256L8.90296 13.5112L15.8923 6.52185ZM4 7.99994C4 7.44766 4.44772 6.99994 5 6.99994H10C10.5523 6.99994 11 6.55223 11 5.99994C11 5.44766 10.5523 4.99994 10 4.99994H5C3.34315 4.99994 2 6.34309 2 7.99994V18.9999C2 20.6568 3.34315 21.9999 5 21.9999H16C17.6569 21.9999 19 20.6568 19 18.9999V13.9999C19 13.4477 18.5523 12.9999 18 12.9999C17.4477 12.9999 17 13.4477 17 13.9999V18.9999C17 19.5522 16.5523 19.9999 16 19.9999H5C4.44772 19.9999 4 19.5522 4 18.9999V7.99994Z" fill="#000000"/>
                        </svg>
                    </a>
                </div>
                ';
            }
            ?>
            <div>
                <p id="occupation" ><?php echo $ResumeHeading->profession ?></p>
                <p id="birth" >Born in <?php echo $ResumeHeading->birth ?></p>
                <p id="name-country" >Hi , I'm <span><?php echo $ResumeHeading->firstName ?></span> <?php echo $ResumeHeading->lastName ?><br> from <span><?php echo $ResumeHeading->country ?></span></p>
            </div>
        </section>
        <div id="about" >
            <div id="about-image" >
                <img src="<?php echo $contactPicSRC ?>"/>
            </div>
            <div id="about-container" >
                <div id="about-me" >About Me</div>
                <div id="summary" ><?php echo $summary ?></div>
                <div id="about-nav" >
                    <?php $about_navs=[false,false,false];  if ($work!="") { echo '<p class="p-focuced" id="about-nav-exp" >experience</p>';$about_navs[0]=true; }else echo '<p class="p-focuced" id="about-nav-exp" style="display:none;" ></p>' ?>
                    <?php if ($edu!=""){ echo '<p id="about-nav-edu" >education</p>' ;$about_navs[1]=true; } else echo '<p id="about-nav-edu" style="display:none;" ></p>'   ?>
                    <?php if ($cert!=""){ echo '<p id="about-nav-cert" >certifications</p>';$about_navs[2]=true; }else echo  '<p id="about-nav-cert" style="display:none;" ></p>'  ?>
                    <?php if ($languages!="") echo '<p id="about-nav-lang" >languages</p>' ?>
                </div>
                <div id="exp-part" class="exp-edu-certificate" >
                    <?php   
                    if ($work!="") {
                        foreach ($work as $workRow){
                            if($workRow->current){
                                $end_date="current";
                            }else{
                                $end_date=$workRow->endDate;
                            }
                            echo '
                            <div class="exp-edu-part" >
                                <div class="work-date" >
                                    <p>'.$workRow->startDate.'</p>
                                    <p>'.$end_date.'</p>
                                </div>
                                <div class="work-detail" >
                                    <p class="work-detail-occup">'.$workRow->jobTitle.'</p>
                                    <p>'.$workRow->employer.','.$workRow->country.','.$workRow->city.'</p>
                                    <p>'.$workRow->jobDesc.'</p>
                                </div>
                            </div>
                            ';
                        }
                    }
                    ?>
                </div>
                <div id="edu-part" class="exp-edu-certificate" >
                    <?php 
                    if($edu!=""){
                        foreach($edu as $eduRow){
                            echo '
                            <div class="exp-edu-part" >
                                <div class="work-date" >
                                    <p>'.$eduRow->startDate.'</p>
                                    <p>'.$eduRow->endDate.'</p>
                                </div>
                                <div class="work-detail" >
                                    <p class="work-detail-occup">'.$eduRow->degree.' : '.$eduRow->field.'</p>
                                    <p>'.$eduRow->schoolName.'-'.$eduRow->schoolLoc.'</p>
                                </div>
                            </div>
                            ';
                        }
                    }
                    ?>
                </div>
                <div id="certificate-part" class="exp-edu-certificate" >
                    <?php 
                    if($cert!=""){
                        foreach($cert as $certRow){
                            if($certRow->online){
                                $online_offline="online course";
                            }else{
                                $online_offline="offline course";
                            }
                            echo '
                            <div class="exp-edu-part" >
                                <div class="work-date" >
                                    <p>'.$certRow->certDate.'</p>
                                </div>
                                <div class="work-detail" >
                                    <p class="work-detail-occup">'.$certRow->certName.'</p>
                                    <p>'.$online_offline.' , '.$certRow->academyName.'</p>
                                </div>
                            </div>
                            ';
                        }
                    }
                    ?>
                </div>
                <div id="lang-part" class="exp-edu-certificate" >
                    <?php 
                    // var_dump($languages->language);
                    for ($i=0;$i<count($languages->language);$i++){
                        $rate=$languages->numofstars[$i];
                        echo '
                        <div  class="language" >
                            <h4>'.$languages->language[$i].'</h4>
                            <div class="main-rate" >
                                <div class="rate" style="width: '.($rate*20).'%;" ></div>
                            </div>
                            <h4  class="h4-rate" >'.starsValue($rate).'</h4>
                        </div>
                        ';
                    }
                    ?>
                </div>

            </div>
        </div>
        <div id="services" >
            <div id="My-services" ><p>My Services</p></div>
            <div id="services-div" >
                <?php 
                if($services!=""){
                    foreach($services as $service ){
                        echo '
                        <div class="service" >
                            <h3>'.$service->serviceTitle.'</h3>
                            <p>'.$service->serviceDesc.'</p>
                        </div>
                        ';
                    }
                }
                ?>
            </div>
        </div>
        <div id="skills" >
            <div id="skills-p" ><p>Skills</p></div>
            <?php 
                if($skills!=""){
                    for ($i=0;$i<count($skills->skill);$i++){
                        $rate=$skills->numofstars[$i];
                        echo '
                        <div class="skill" >
                            <p style="margin: 0; padding:0" >'.$skills->skill[$i].'</p>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(0,1)" id="star10" width="24" height="24" style="fill:'.skillColor($rate,1).';"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(1,1)" width="24" id="star11" height="24" style="fill:'.skillColor($rate,2).';"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(2,1)"  width="24" id="star12" height="24" style="fill:'.skillColor($rate,3).';"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(3,1)" width="24" id="star13" height="24" style="fill:'.skillColor($rate,4).';"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                                <svg xmlns="http://www.w3.org/2000/svg" onmouseover="starHover(4,1)"  width="24" id="star14" height="24" style="fill:'.skillColor($rate,5).';"><path d="M21.947 9.179a1.001 1.001 0 0 0-.868-.676l-5.701-.453-2.467-5.461a.998.998 0 0 0-1.822-.001L8.622 8.05l-5.701.453a1 1 0 0 0-.619 1.713l4.213 4.107-1.49 6.452a1 1 0 0 0 1.53 1.057L12 18.202l5.445 3.63a1.001 1.001 0 0 0 1.517-1.106l-1.829-6.4 4.536-4.082c.297-.268.406-.686.278-1.065z"></path></svg>
                            </div>
                        </div>
                        ';
                    }
                }
            ?>
        </div>
        <div id="portfolio" >
            <h1>My Work</h1>
            <div id="left" >
                <svg fill="var(--secondaryColor)" width="70" height="70" viewBox="-8.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.094 15.938l7.688 7.688-3.719 3.563-11.063-11.063 11.313-11.344 3.531 3.5z"></path>
                </svg>
            </div>
            <div id="right" >
                <svg fill="var(--secondaryColor)" width="70" height="70" viewBox="-8.5 0 32 32" version="1.1" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.75 16.063l-7.688-7.688 3.719-3.594 11.063 11.094-11.344 11.313-3.5-3.469z"></path>
                </svg>
            </div>
            <div id="portfolio-div" >
                <?php 
                if($projects!=""){
                    foreach($projects as $project ){
                        echo '
                        <div class="article" >
                            <img src="user/usersImg/'.$project->imageName.'" >
                            <a href="'.$project->projectLink.'" target="_blank" >
                                <h2>'.$project->projectTitle.'</h2>
                                <p>'.$project->projectDesc.'</p>
                            </a>
                        </div>
                        ';
                    }
                }
                ?>
            </div>
        </div>
        <div id="contact" >
            <div id="contact-title" >Contact Me</div>
            <div id="contact-container" >
                <div id="contact-contact" >
                    <div>
                            <svg width="30" height="30" viewBox="0 -2.5 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -922.000000)" fill="var(--secondaryColor)">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path d="M294,774.474 L284,765.649 L284,777 L304,777 L304,765.649 L294,774.474 Z M294.001,771.812 L284,762.981 L284,762 L304,762 L304,762.981 L294.001,771.812 Z" id="email-[#1572]"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                            <p><?php echo $contact->email  ?></p>
                    </div>
                    <div>
                        <svg fill="var(--secondaryColor)" style="margin:-10px;" width="45" height="45" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="M11.748 5.773S11.418 5 10.914 5c-.496 0-.754.229-.926.387S6.938 7.91 6.938 7.91s-.837.731-.773 2.106c.054 1.375.323 3.332 1.719 6.058 1.386 2.72 4.855 6.876 7.047 8.337 0 0 2.031 1.558 3.921 2.191.549.173 1.647.398 1.903.398.26 0 .719 0 1.246-.385.536-.389 3.543-2.807 3.543-2.807s.736-.665-.119-1.438c-.859-.773-3.467-2.492-4.025-2.944-.559-.459-1.355-.257-1.699.054-.343.313-.956.828-1.031.893-.112.086-.419.365-.763.226-.438-.173-2.234-1.148-3.899-3.426-1.655-2.276-1.837-3.02-2.084-3.824a.56.56 0 0 1 .225-.657c.248-.172 1.161-.933 1.161-.933s.591-.583.344-1.27-1.906-4.716-1.906-4.716z"/></svg>
                        <P><?php echo $contact->phone  ?></P>
                    </div>
                    <div id="social-media" >
                    <?php 
                    if($contact->fcb!=""){
                        echo '
                        <a href="'.$contact->fcb.'" >
                            <svg fill="var(--primaryColor)" width="40" height="40" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2.03998C6.5 2.03998 2 6.52998 2 12.06C2 17.06 5.66 21.21 10.44 21.96V14.96H7.9V12.06H10.44V9.84998C10.44 7.33998 11.93 5.95998 14.22 5.95998C15.31 5.95998 16.45 6.14998 16.45 6.14998V8.61998H15.19C13.95 8.61998 13.56 9.38998 13.56 10.18V12.06H16.34L15.89 14.96H13.56V21.96C15.9164 21.5878 18.0622 20.3855 19.6099 18.57C21.1576 16.7546 22.0054 14.4456 22 12.06C22 6.52998 17.5 2.03998 12 2.03998Z"/>
                            </svg>
                        </a>
                        ';
                    }

                    if($contact->instagram!=""){
                        echo '
                        <a href="'.$contact->instagram.'" >
                            <svg width="30" height="30" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-340.000000, -7439.000000)" fill="var(--primaryColor)">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path d="M289.869652,7279.12273 C288.241769,7279.19618 286.830805,7279.5942 285.691486,7280.72871 C284.548187,7281.86918 284.155147,7283.28558 284.081514,7284.89653 C284.035742,7285.90201 283.768077,7293.49818 284.544207,7295.49028 C285.067597,7296.83422 286.098457,7297.86749 287.454694,7298.39256 C288.087538,7298.63872 288.809936,7298.80547 289.869652,7298.85411 C298.730467,7299.25511 302.015089,7299.03674 303.400182,7295.49028 C303.645956,7294.859 303.815113,7294.1374 303.86188,7293.08031 C304.26686,7284.19677 303.796207,7282.27117 302.251908,7280.72871 C301.027016,7279.50685 299.5862,7278.67508 289.869652,7279.12273 M289.951245,7297.06748 C288.981083,7297.0238 288.454707,7296.86201 288.103459,7296.72603 C287.219865,7296.3826 286.556174,7295.72155 286.214876,7294.84312 C285.623823,7293.32944 285.819846,7286.14023 285.872583,7284.97693 C285.924325,7283.83745 286.155174,7282.79624 286.959165,7281.99226 C287.954203,7280.99968 289.239792,7280.51332 297.993144,7280.90837 C299.135448,7280.95998 300.179243,7281.19026 300.985224,7281.99226 C301.980262,7282.98483 302.473801,7284.28014 302.071806,7292.99991 C302.028024,7293.96767 301.865833,7294.49274 301.729513,7294.84312 C300.829003,7297.15085 298.757333,7297.47145 289.951245,7297.06748 M298.089663,7283.68956 C298.089663,7284.34665 298.623998,7284.88065 299.283709,7284.88065 C299.943419,7284.88065 300.47875,7284.34665 300.47875,7283.68956 C300.47875,7283.03248 299.943419,7282.49847 299.283709,7282.49847 C298.623998,7282.49847 298.089663,7283.03248 298.089663,7283.68956 M288.862673,7288.98792 C288.862673,7291.80286 291.150266,7294.08479 293.972194,7294.08479 C296.794123,7294.08479 299.081716,7291.80286 299.081716,7288.98792 C299.081716,7286.17298 296.794123,7283.89205 293.972194,7283.89205 C291.150266,7283.89205 288.862673,7286.17298 288.862673,7288.98792 M290.655732,7288.98792 C290.655732,7287.16159 292.140329,7285.67967 293.972194,7285.67967 C295.80406,7285.67967 297.288657,7287.16159 297.288657,7288.98792 C297.288657,7290.81525 295.80406,7292.29716 293.972194,7292.29716 C292.140329,7292.29716 290.655732,7290.81525 290.655732,7288.98792" id="instagram-[#167]"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                        ';
                    }

                    if($contact->twitter!=""){
                        echo '
                        <a href="'.$contact->twitter.'" >
                            <svg width="30" height="30" viewBox="0 -2 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-60.000000, -7521.000000)" fill="var(--primaryColor)">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path d="M10.29,7377 C17.837,7377 21.965,7370.84365 21.965,7365.50546 C21.965,7365.33021 21.965,7365.15595 21.953,7364.98267 C22.756,7364.41163 23.449,7363.70276 24,7362.8915 C23.252,7363.21837 22.457,7363.433 21.644,7363.52751 C22.5,7363.02244 23.141,7362.2289 23.448,7361.2926 C22.642,7361.76321 21.761,7362.095 20.842,7362.27321 C19.288,7360.64674 16.689,7360.56798 15.036,7362.09796 C13.971,7363.08447 13.518,7364.55538 13.849,7365.95835 C10.55,7365.79492 7.476,7364.261 5.392,7361.73762 C4.303,7363.58363 4.86,7365.94457 6.663,7367.12996 C6.01,7367.11125 5.371,7366.93797 4.8,7366.62489 L4.8,7366.67608 C4.801,7368.5989 6.178,7370.2549 8.092,7370.63591 C7.488,7370.79836 6.854,7370.82199 6.24,7370.70483 C6.777,7372.35099 8.318,7373.47829 10.073,7373.51078 C8.62,7374.63513 6.825,7375.24554 4.977,7375.24358 C4.651,7375.24259 4.325,7375.22388 4,7375.18549 C5.877,7376.37088 8.06,7377 10.29,7376.99705" id="twitter-[#154]"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                        ';
                    }
                    if($contact->linkedin!=""){
                        echo '
                        <a href="'.$contact->linkedin.'" >
                        <svg width="30" height="30" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Dribbble-Light-Preview" transform="translate(-180.000000, -7479.000000)" fill="var(--primaryColor)">
                                    <g id="icons" transform="translate(56.000000, 160.000000)">
                                        <path d="M144,7339 L140,7339 L140,7332.001 C140,7330.081 139.153,7329.01 137.634,7329.01 C135.981,7329.01 135,7330.126 135,7332.001 L135,7339 L131,7339 L131,7326 L135,7326 L135,7327.462 C135,7327.462 136.255,7325.26 139.083,7325.26 C141.912,7325.26 144,7326.986 144,7330.558 L144,7339 L144,7339 Z M126.442,7323.921 C125.093,7323.921 124,7322.819 124,7321.46 C124,7320.102 125.093,7319 126.442,7319 C127.79,7319 128.883,7320.102 128.883,7321.46 C128.884,7322.819 127.79,7323.921 126.442,7323.921 L126.442,7323.921 Z M124,7339 L129,7339 L129,7326 L124,7326 L124,7339 Z" id="linkedin-[#161]"></path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                    </a>
                        ';
                    }

                    if($contact->github!=""){
                        echo '
                        <a href="'.$contact->github.'" >
                            <svg width="30" height="30" viewBox="0 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-140.000000, -7559.000000)" fill="var(--primaryColor)">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path d="M94,7399 C99.523,7399 104,7403.59 104,7409.253 C104,7413.782 101.138,7417.624 97.167,7418.981 C96.66,7419.082 96.48,7418.762 96.48,7418.489 C96.48,7418.151 96.492,7417.047 96.492,7415.675 C96.492,7414.719 96.172,7414.095 95.813,7413.777 C98.04,7413.523 100.38,7412.656 100.38,7408.718 C100.38,7407.598 99.992,7406.684 99.35,7405.966 C99.454,7405.707 99.797,7404.664 99.252,7403.252 C99.252,7403.252 98.414,7402.977 96.505,7404.303 C95.706,7404.076 94.85,7403.962 94,7403.958 C93.15,7403.962 92.295,7404.076 91.497,7404.303 C89.586,7402.977 88.746,7403.252 88.746,7403.252 C88.203,7404.664 88.546,7405.707 88.649,7405.966 C88.01,7406.684 87.619,7407.598 87.619,7408.718 C87.619,7412.646 89.954,7413.526 92.175,7413.785 C91.889,7414.041 91.63,7414.493 91.54,7415.156 C90.97,7415.418 89.522,7415.871 88.63,7414.304 C88.63,7414.304 88.101,7413.319 87.097,7413.247 C87.097,7413.247 86.122,7413.234 87.029,7413.87 C87.029,7413.87 87.684,7414.185 88.139,7415.37 C88.139,7415.37 88.726,7417.2 91.508,7416.58 C91.513,7417.437 91.522,7418.245 91.522,7418.489 C91.522,7418.76 91.338,7419.077 90.839,7418.982 C86.865,7417.627 84,7413.783 84,7409.253 C84,7403.59 88.478,7399 94,7399" id="github-[#142]"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                        ';
                    }

                    if($contact->tik_tok!=""){
                        echo '
                        <a href="'.$contact->tik_tok.'" >
                        <svg style="margin:-5px;" width="40" height="40" viewBox="0 0 24 24" fill="var(--primaryColor)" xmlns="http://www.w3.org/2000/svg"><path d="M20 7.50414C18.5333 7.56942 15.52 6.75998 15.2 3H12.4V13.9666C12.4 17.0999 9.93253 18.4412 8.289 17.2507C6.20169 15.7389 7.60003 12.5958 10.2 12.9874V9.6583C8.20003 9.6583 4 10.4416 4 15.3374C4 21.6041 10.8146 21.4083 12.4 20.5824C14.9798 19.2385 15.6 17.7827 15.6 14.5541C15.6 11.6166 15.6 9.85413 15.6 9.0708C16.2667 9.39719 18.08 10.0891 20 10.2458" stroke="#000000" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </a>
                        ';
                    }

                    if($contact->youtube!=""){
                        echo '
                        <a href="'.$contact->youtube.'" >
                            <svg fill="var(--primaryColor)" height="30" width="30" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                                viewBox="-271 311.2 256 179.8" xml:space="preserve">
                            <path d="M-59.1,311.2h-167.8c0,0-44.1,0-44.1,44.1v91.5c0,0,0,44.1,44.1,44.1h167.8c0,0,44.1,0,44.1-44.1v-91.5
                                C-15,355.3-15,311.2-59.1,311.2z M-177.1,450.3v-98.5l83.8,49.3L-177.1,450.3z"/>
                            </svg>
                        </a>
                        ';
                    }

                    if($contact->twitch!=""){
                        echo '
                        <a href="'.$contact->twitch.'" >
                            <svg width="30" height="30" viewBox="-0.5 0 20 20" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                <g id="Page-1" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <g id="Dribbble-Light-Preview" transform="translate(-141.000000, -7399.000000)" fill="var(--primaryColor)">
                                        <g id="icons" transform="translate(56.000000, 160.000000)">
                                            <path d="M97,7249 L99,7249 L99,7244 L97,7244 L97,7249 Z M92,7249 L94,7249 L94,7244 L92,7244 L92,7249 Z M102,7250.307 L102,7241 L88,7241 L88,7253 L92,7253 L92,7255.953 L94.56,7253 L99.34,7253 L102,7250.307 Z M98.907,7256 L94.993,7256 L92.387,7259 L90,7259 L90,7256 L85,7256 L85,7242.48 L86.3,7239 L104,7239 L104,7251.173 L98.907,7256 Z" id="twitch-[#182]"></path>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </a>
                        ';
                    }
                    ?>
                        

                    </div>
                    <?php if($getResume->rowCount()>0) echo  '<a id="download-cv" href="'.$resumeSRC.'" download >download CV</a>' ?>
                </div>
                <div id="contact-form" >
                    <form method="POST" >
                        <input class="input" name="name" type="text" required placeholder="Your Name" >
                        <input class="input" name="email" type="email" required placeholder="Your Email" >
                        <textarea class="input" name="message" required placeholder="Your Message" ></textarea>
                        <button name='send' >send</button>
                    </form>  
                </div>
            </div>
        </div>
    </div>
<script >

if ( /android|webOS|iPhone|iPad|iPod|blackberry|IEMobile|Opera Mini|Windows Phone/i.test(navigator.userAgent) ){//phone and ipad
    let r = document.querySelector(':root');
    r.style.setProperty('--a-opacity','1');
}

let navP=document.querySelectorAll("nav p");
let expPart=document.getElementById("exp-part");
let eduPart=document.getElementById("edu-part");
let certPart=document.getElementById("certificate-part");
let langPart=document.getElementById("lang-part");
let expNav=document.getElementById("about-nav-exp");
let eduNav=document.getElementById("about-nav-edu");
let certNav=document.getElementById("about-nav-cert");
let langNav=document.getElementById("about-nav-lang");
let navPhone=document.getElementById("nav-phone");
let header=document.querySelector("header");

let about=document.getElementById("about");
let contentContainer=document.getElementById("content-container");
let services=document.getElementById("services");
let skills=document.getElementById("skills");
let portfolio=document.getElementById("portfolio");
let contact=document.getElementById("contact");
let headerHeight=59 ;

let navPhoneClickSt=true ;

const numOfCards=<?php if($projects!="") {echo count($projects);} else {echo true;}  ?>;
const Right=document.getElementById('right');
const Left=document.getElementById('left');
const first=document.querySelector(".article");
var pos=20
var index=0;


var navClicked=(e,num)=>{
    e.style.color='var(--secondaryColor)';
    for(let i=0;i<navP.length;i++){
        if(navP[i]!==e){
            navP[i].style.color='var(--primaryColor)';
        }
    }

    switch (num){
        case 1 :
            window.scrollTo({top:0});
            break ;
        case 2 :
            window.scrollTo({top:contentContainer.clientHeight-headerHeight});
            break ;
        case 3 :
            window.scrollTo({top:about.clientHeight+contentContainer.clientHeight-headerHeight});
            break ;
        case 4 :
            window.scrollTo({top:about.clientHeight+contentContainer.clientHeight+services.clientHeight-headerHeight+20});
            break ;
        case 5 :
            window.scrollTo({top:about.clientHeight+contentContainer.clientHeight+services.clientHeight+skills.clientHeight-headerHeight+40});
            break ;
        case 6 :
            window.scrollTo({top:about.clientHeight+contentContainer.clientHeight+services.clientHeight+skills.clientHeight+portfolio.clientHeight-headerHeight+60});
            break ;
    } 
    // document.body.scrollTop = 0; // For Safari and Chrome

}

expNav.addEventListener("click",()=>{
    expNav.classList.toggle('p-focuced');
    eduNav.classList.remove('p-focuced');
    certNav.classList.remove('p-focuced');
    langNav.classList.remove('p-focuced');

    langPart.style.display='none'
    expPart.style.display='flex'
    eduPart.style.display='none'
    certPart.style.display='none'
})

eduNav.addEventListener("click",()=>{
    expNav.classList.remove('p-focuced');
    eduNav.classList.toggle('p-focuced');
    certNav.classList.remove('p-focuced');
    langNav.classList.remove('p-focuced');

    langPart.style.display='none'
    eduPart.style.display='flex'
    expPart.style.display='none'
    certPart.style.display='none'
})
certNav.addEventListener("click",()=>{
    expNav.classList.remove('p-focuced');
    eduNav.classList.remove('p-focuced');
    certNav.classList.toggle('p-focuced');
    langNav.classList.remove('p-focuced');

    langPart.style.display='none'
    certPart.style.display='flex'
    eduPart.style.display='none'
    expPart.style.display='none'
})

langNav.addEventListener("click",()=>{
    expNav.classList.remove('p-focuced');
    eduNav.classList.remove('p-focuced');
    certNav.classList.remove('p-focuced');
    langNav.classList.toggle('p-focuced');

    langPart.style.display='flex'
    certPart.style.display='none'
    eduPart.style.display='none'
    expPart.style.display='none'
})

let navClick=()=>{
    if(navPhoneClickSt){
        navPhone.style.left='0'
    }else{
        navPhone.style.left='-100vw'
    }
    navPhoneClickSt=!navPhoneClickSt
}


window.addEventListener("scroll", (event) => {
    let scroll = this.scrollY;
    if (scroll<30){
        header.style.backgroundColor='rgba(0, 0, 0, 0)'
    }else if(scroll<window.screen.availHeight){
        header.style.backgroundColor='rgba(0, 0, 0, 0.5)'
    }else{
        header.style.backgroundColor='rgba(0, 0, 0, 1)'
    }
});


let numOfAvailCards=()=>{
    let returnVal
    if(document.body.offsetWidth>800){
        returnVal=3;
    }else if(document.body.offsetWidth>500){
        returnVal=2;
    }else{
        returnVal=1
    }
    return returnVal ;
}


        window.addEventListener('scroll', function() {
            var scrollPosition = window.scrollY;
            if(scrollPosition>=about.clientHeight+contentContainer.clientHeight+services.clientHeight+skills.clientHeight+portfolio.clientHeight-headerHeight-1){
                for(let i=0;i<navP.length;i++){
                    if(i!==5 || i!=11)
                        navP[i].style.color='var(--primaryColor)';
                }
                navP[5].style.color='var(--secondaryColor)';
                navP[11].style.color='var(--secondaryColor)'; 
            }
            else if(scrollPosition>=about.clientHeight+contentContainer.clientHeight+services.clientHeight+skills.clientHeight-headerHeight-1){
                for(let i=0;i<navP.length;i++){
                    if(i!==4 || i!==10)
                        navP[i].style.color='var(--primaryColor)';
                }
                navP[4].style.color='var(--secondaryColor)';
                navP[10].style.color='var(--secondaryColor)';
            }else if(scrollPosition>=about.clientHeight+contentContainer.clientHeight+services.clientHeight-headerHeight-1){
                for(let i=0;i<navP.length;i++){
                    if(i!==3 || i!==9 )
                        navP[i].style.color='var(--primaryColor)';
                }
                navP[3].style.color='var(--secondaryColor)';
                navP[9].style.color='var(--secondaryColor)';
            }else if(scrollPosition>=about.clientHeight+contentContainer.clientHeight-headerHeight-1){
                for(let i=0;i<navP.length;i++){
                    if(i!==2 || i!==8)
                        navP[i].style.color='var(--primaryColor)';
                }
                navP[2].style.color='var(--secondaryColor)';
                navP[8].style.color='var(--secondaryColor)';
            }else if(scrollPosition>=contentContainer.clientHeight-headerHeight-1){
                for(let i=0;i<navP.length;i++){
                    if(i!==1 || i!==7)
                        navP[i].style.color='var(--primaryColor)';
                }
                navP[1].style.color='var(--secondaryColor)';
                navP[7].style.color='var(--secondaryColor)';
            }else {
                for(let i=1;i<navP.length;i++){
                    if(i!==6)
                        navP[i].style.color='var(--primaryColor)';
                }
                navP[0].style.color='var(--secondaryColor)';
                navP[6].style.color='var(--secondaryColor)';
            }
        });
</script>



<?php 

if($about_navs[0]===false){
    
    if($about_navs[1]===true ){
        echo "<script>
        expNav.classList.remove('p-focuced');
        eduNav.classList.toggle('p-focuced');
        certNav.classList.remove('p-focuced');
        langNav.classList.remove('p-focuced');

        langPart.style.display='none'
        eduPart.style.display='flex'
        expPart.style.display='none'
        certPart.style.display='none'
        </script>";
    }elseif($about_navs[2]===true){
        echo "<script>
        expNav.classList.remove('p-focuced');
        eduNav.classList.remove('p-focuced');
        certNav.classList.toggle('p-focuced');
        langNav.classList.remove('p-focuced');

        langPart.style.display='none'
        certPart.style.display='flex'
        eduPart.style.display='none'
        expPart.style.display='none'
        </script>";
    }else{
        echo "<script>
        expNav.classList.remove('p-focuced');
        eduNav.classList.remove('p-focuced');
        certNav.classList.remove('p-focuced');
        langNav.classList.toggle('p-focuced');

        langPart.style.display='flex'
        certPart.style.display='none'
        eduPart.style.display='none'
        expPart.style.display='none'
        </script>";
    }
}

if($projects!=""){
    echo "<script>
    first.style.marginLeft=String(pos)+'px'
        
        Right.addEventListener('click',()=>{
            let cardWidth=first.clientWidth;
            if(index<numOfCards-numOfAvailCards()){
                index+=1;
                pos-=cardWidth + 20
                first.style.marginLeft=String(pos)+'px'
            }

            if(index===numOfCards-numOfAvailCards()){
                Right.style.opacity='0.5';
            }else{
                Right.style.opacity='1';
            }

            if(index===0){
                Left.style.opacity='0.5';
            }else{
                Left.style.opacity='1';
            }
        })

        Left.addEventListener('click',()=>{
            let cardWidth=first.clientWidth;
            if(index>0){
                index-=1;
                pos+=cardWidth + 20
                first.style.marginLeft=String(pos)+'px'
            }

            if(index===0){
                Left.style.opacity='0.5';
            }else{
                Left.style.opacity='1';
            }
    
            if(index===numOfCards-numOfAvailCards()){
                Right.style.opacity='0.5';
            }else{
                Right.style.opacity='1';
            }
        })

        if(numOfCards===numOfAvailCards()){
            Right.style.opacity='0';
            Left.style.opacity='0';
        }else{
            Left.style.opacity='0.5';
            Right.style.opacity='1';
        }


        window.addEventListener('resize', (event) => {
            pos=20;
            index=0;
            first.style.marginLeft=String(pos)+'px'
            Left.style.opacity='0.5';
            Right.style.opacity='1';

            if(numOfCards===numOfAvailCards()){
                Right.style.opacity='0';
                Left.style.opacity='0';
            }else{
                Left.style.opacity='0.5';
                Right.style.opacity='1';
            }
        });
    </script>";
}




if(isset($_POST['send'])){
    $name = $_POST["name"];
    $email =$_POST["email"];
    $message = $_POST["message"];

    require_once "mail.php";
    $mail->addAddress($contact->email);
    $mail->Subject = $name;
    $mail->Body = '<h1>message from '.$email.' : </h1>'. "<p>".$message."<p>" ;
    $mail->setFrom("px.turing@gmail.com", "PX-Turing");
    $mail->send();
}




?>






</body>
</html>