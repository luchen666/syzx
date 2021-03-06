<?php
/* 编写:穆金秋*/

include_once(MODELS_PATH . "/BaseModel.php");

class Company Extends BaseModel
{
//    //查
//    public static function getCompanies()
//    {
//        $sql =  "SELECT	*  FROM  Company WHERE IsDeleted=0";
//        $rs = ExecSQL($sql);
//        return $rs->AsArray();
//    }
    //查新增公司数量
    public static function getNewCompany(){
        $tody=date("Y-m-d");
        //echo $tody;2017-01-20
        $sql = "SELECT	COUNT(*) as newCompanyNumber  FROM  Company WHERE IsDeleted=0 and CreateDate=$tody";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //查公司各状态数量
    public static function getCompanyNumber()
    {
        $sql =  "SELECT CertifyState,Count(ID) AS Cnumber FROM Company WHERE IsDeleted=0 GROUP BY CertifyState WITH ROLLUP";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }

//    //插入
//    public static function insertCompany($Name,$Address,$Description,$VIP,$Code,$CertifyState)
//    {
//        $sql =  "INSERT INTO Company(Name,Address,Description,VIP,Code,CertifyState,CreateDate) VALUES (:Name,:Address,:Description,:VIP,:Code,:CertifyState,now())";
//        $rs = ExecSQL($sql,Array("Name"=>$Name,"Address"=>$Address,"Description"=>$Description,"VIP"=>$VIP,"Code"=>$Code,"CertifyState"=>$CertifyState));
//        return $rs->AsArray();
//    }
    //改
    public static function updateCompany($ID,$Name,$Address,$Description,$VIP,$Code,$CertifyState)
    {
        $sql =  "UPDATE Company SET Name='$Name',Address='$Address',Description='$Description',VIP='$VIP',Code='$Code',CertifyState='$CertifyState' WHERE ID='$ID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //修改CodeCertificate
    public static function updateCodeCertificate($ID,$CodeCertificate)
    {
        $sql =  "UPDATE Company SET CodeCertificate='$CodeCertificate' WHERE ID='$ID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //修改BusinessLicenses
    public static function updateBusinessLicenses($ID,$BusinessLicenses)
    {
        $sql =  "UPDATE Company SET BusinessLicenses='$BusinessLicenses' WHERE ID='$ID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }
    //修改TaxRegistration
    public static function updateTaxRegistration($ID,$TaxRegistration)
    {
        $sql =  "UPDATE Company SET TaxRegistration='$TaxRegistration' WHERE ID='$ID'";
        $rs = ExecSQL($sql);
        return $rs->AsArray();
    }


//====================================================================

    //公司总数
    public static function getAllCompaniesNum()
    {
        $rs = ExecSQL("SELECT Count(ID) AS N FROM company");
        return $rs->N;
    }
    //从company表中获取数据
    public static function getCompanies($page=1,$rows=20,$where=array())
    {
        $from = ($page-1)*$rows;
        $sql = "SELECT * FROM company %s LIMIT $from,$rows";
        if(IsSet($where["Name"]) && $where["Name"] != "")
        {   $text = "WHERE IsDeleted =0 AND Name LIKE '%" . $where["Name"] ."%'";
            $sql = sprintf($sql,$text);
        }
        else    $sql = sprintf($sql, "WHERE IsDeleted =0");

        $rs = ExecSQL($sql);
        return $rs->RecordCount > 0 ? $rs->AsArray() : null;
    }

    //查一个公司
    public static function getCompany($id)
    {
        $sql =  "SELECT *  FROM  Company WHERE ID = :ID ";
        $rs = ExecSQL($sql,Array("ID"=>$id));
        return $rs->AsArray();
    }
    //删除选中数据
    public static function deleteCompany($id)
    {   $id=explode(",",$id);
        $count=count($id);
        for($i=0;$i<$count;$i++){
            ExecSQL("UPDATE Company SET IsDeleted = 1 WHERE ID='$id[$i]'");
        };
        return true;
    }

    //保存添加或编辑的公司数据
    public static function save($parm,$tbname="",$rs = null)
    {
        if($parm["ID"]==0)
        {
            $sql = "INSERT INTO company
                                (   Name,
                                    CreateUserID,
                                    CreateUserName,
                                    Address,
                                    Description,
                                    VIP,
                                    Code,
                                    CertifyState,
                                    CreateDate
                                )
                        VALUES(      :Name,
                                      :CreateUserID,
                                      :CreateUserName,
                                      :Address,
                                      :Description,
                                      :VIP,
                                      :Code,
                                      :CertifyState,
                                      Now()
                        )";
                unset($parm["ID"]);
        }
        else
        {   $sql = "UPDATE company
                    SET Name = :Name, Address = :Address, Description = :Description, VIP = :VIP, Code = :Code, CertifyState = :CertifyState
                     WHERE ID = :ID";
        }
        ExecSQL($sql,$parm);
        return true;
    }

    //检查公司是否已经存在
    public static function checkName($name)
    {
        $rs = new RecordSets();
        $rs->ExecSQL("SELECT COUNT(Name) AS Cnt FROM company WHERE Name = :Name",array("Name"=>$name));
        if ($rs->Cnt > 0) return 1;
    }


}