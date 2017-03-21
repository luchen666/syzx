<?php
header("Content-type: text/html; charset=utf-8");
include_once(APP_PATH . "/utils/Utils.php");
include_once(APP_PATH . "/utils/ResponseResult.php");
include_once(MODELS_PATH . "/admin/User.php");
include_once(MODELS_PATH . "/admin/Ship.php");
include_once(MODELS_PATH . "/admin/Company.php");

class PostimageController extends APIBaseController
{
    //用户表图像的修改
    public function PostIDImage1Action(){
        if (!empty($_FILES["file"]["name"]))
        {
            //print_r($_FILES);
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_IDImage1.jpg",SITE_UPLOAD_DIR,$_POST["UserID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

                if (in_array($fileParts['extension'],$fileTypes))
                {
                        move_uploaded_file($tempFile,$targetFile);

                        //图像的相对路径
                        $IDImage1='/'.$targetFile;
                        //echo '图像的相对路径的值为：'. $IDImage1;

                        $ret = User::updateIDImage1(
                            $_POST["UserID"],
                            $IDImage1
                        );

                        //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/Users' </script>";
                        echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
                        //header('LOCATION:/admin/home/Users');
                }else{
                    echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
                }
        }else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }
    public function PostIDImage2Action(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_IDImage2.jpg",SITE_UPLOAD_DIR,$_POST["UserID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $IDImage2='/'.$targetFile;
                //echo '图像的相对路径的值为：'. $IDImage2;

                $ret = User::updateIDImage2(
                    $_POST["UserID"],
                    $IDImage2
                );

                //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/Users' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
                //header('LOCATION:/admin/home/Users');
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }
    public function PostAvatarAction(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_Avatar.jpg",SITE_UPLOAD_DIR,$_POST["UserID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $Avatar='/'.$targetFile;
                //echo '图像的相对路径的值为：'. $Avatar;

                $ret = User::updateAvatar(
                    $_POST["UserID"],
                    $Avatar
                );

                //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/Users' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }

    //船舶表图像的修改
    public function PostLogoImageAction(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_LogoImage.jpg",SITE_UPLOAD_DIR,$_POST["ID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $LogoImage='/'.$targetFile;
               // echo '图像的相对路径的值为：'. $LogoImage;

                $ret = Ship::updateLogoImage(
                    $_POST["ID"],
                    $LogoImage
                );

                //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/ships' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }
    public function PostNationalityCertificateAction(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_NationalityCertificate.jpg",SITE_UPLOAD_DIR,$_POST["ID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $NationalityCertificate='/'.$targetFile;
                //echo '图像的相对路径的值为：'. $NationalityCertificate;

                $ret = Ship::updateNationalityCertificate(
                    $_POST["ID"],
                    $NationalityCertificate
                );

                //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/ships' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }
    public function PostInspectionCertificateAction(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_InspectionCertificate.jpg",SITE_UPLOAD_DIR,$_POST["ID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $InspectionCertificate='/'.$targetFile;
                //echo '图像的相对路径的值为：'. $InspectionCertificate;

                $ret = Ship::updateInspectionCertificate(
                    $_POST["ID"],
                    $InspectionCertificate
                );

                //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/ships' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }

    //公司表图像的修改
    public function PostCodeCertificateAction(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_CodeCertificate.jpg",SITE_UPLOAD_DIR,$_POST["ID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $CodeCertificate='/'.$targetFile;
               // echo '图像的相对路径的值为：'. $CodeCertificate;

                $ret = Company::updateCodeCertificate(
                    $_POST["ID"],
                    $CodeCertificate
                );

                //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/Companies' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }
    public function PostBusinessLicensesAction(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_BusinessLicenses.jpg",SITE_UPLOAD_DIR,$_POST["ID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $BusinessLicenses='/'.$targetFile;
                //echo '图像的相对路径的值为：'. $BusinessLicenses;

                $ret = Company::updateBusinessLicenses(
                    $_POST["ID"],
                    $BusinessLicenses
                );

                //echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/Companies' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }
    public function PostTaxRegistrationAction(){
        if (!empty($_FILES["file"]["name"]))
        {
            $tempFile = $_FILES['file']['tmp_name'];
            $targetFile = sprintf("%s/%d_TaxRegistration.jpg",SITE_UPLOAD_DIR,$_POST["ID"]);

            // Validate the file type
            $fileTypes = array('jpg','jpeg','gif','png'); // File extensions
            $fileParts = pathinfo($_FILES['file']['name']);

            if (in_array($fileParts['extension'],$fileTypes))
            {
                move_uploaded_file($tempFile,$targetFile);
                //echo "上传成功";

                //图像的相对路径
                $TaxRegistration='/'.$targetFile;
                //echo '图像的相对路径的值为：'. $TaxRegistration;

                $ret = Company::updateTaxRegistration(
                    $_POST["ID"],
                    $TaxRegistration
                );

               // echo "<script> alert('上传成功返回刚才页面');parent.location.href='/admin/home/Companies' </script>";
                echo "<script>alert('上传成功!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            } else {
                echo "<script>alert('图像格式只能是 jpg,jpeg,gif,png!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
            }
        }
        else{
            echo "<script>alert('请选中后上传!');location.href='".$_SERVER["HTTP_REFERER"]."';</script>";
        }
    }
}
?>