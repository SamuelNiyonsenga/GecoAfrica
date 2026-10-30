<?php
include_once('../config.php');
date_default_timezone_set('Africa/Cairo');
if(!empty($_FILES['files'])){
    $n=0;
    $s=0;
    $prepareNames   =   array();
    foreach($_FILES['files']['name'] as $val)
    {
        $infoExt        =   getimagesize($_FILES['files']['tmp_name'][$n]);
        $s++;
        $filesName      =   str_replace(" ","",trim($_FILES['files']['name'][$n]));
        $files          =   explode(".",$filesName);
        $File_Ext       =   substr($_FILES['files']['name'][$n], strrpos($_FILES['files']['name'][$n],'.'));
         
        if($infoExt['mime'] == 'image/gif' || $infoExt['mime'] == 'image/jpeg' || $infoExt['mime'] == 'image/JPG' || $infoExt['mime'] == 'image/png')
        {
            $srcPath    = $geco_profile_dr;
            $fileName   =   $s.rand(0,999).time().$File_Ext;
            $path   =   trim($srcPath.$fileName);
            if(move_uploaded_file($_FILES['files']['tmp_name'][$n], $path))
            {
                $prepareNames[] .=  $fileName; //need to be fixed.
                $Sflag      =   1; // success
            }else{
                $Sflag  = 2; // file not move to the destination
            }
        }
        else
        {
            $Sflag  = 3; //extention not valid
        }
        $n++;
    }
    if($Sflag==1){
        echo '<div class="alert alert-success">Images uploaded successfully!</div>';
    }else if($Sflag==2){
        echo '{<div class="alert alert-danger">File not move to the destination.</div>}';
        $uploaderror[]='{<div class="alert alert-danger">File not move to the destination.</div>}';
    }else if($Sflag==3){
        echo '{<div class="alert alert-danger">File extention not good. Try with .PNG, .JPEG, .GIF, .JPG</div>}';
        $uploaderror[]='{<div class="alert alert-danger">File extention not good. Try with .PNG, .JPEG, .GIF, .JPG</div>}';
    }
 
    if(!empty($prepareNames)){
        $count  =   1;
        foreach($prepareNames as $name){
            $data   =   array($geco_userid=>$geco_current_id,
                            'img_name'=>$name,
                            'img_order'=>$count++,
                        );
            $db->insert(TB_IMG,$data);
        }
    }
}
?>