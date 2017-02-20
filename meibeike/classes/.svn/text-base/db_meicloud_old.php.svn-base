<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Db_Meicloud {

    //调用存储过程新增用户
    public static function addUser($ustring, $username, $passwd, $usertype) {
        return false;
        $db = IDBFactory::getDB();
        //$db->query("set @ret;");
        try {
            $db->query("call meicloud.proc_register ( '$ustring', '$username','$passwd',$usertype,'', @ret)");
            $ret = $db->query("select @ret as meiid;");
            //var_dump($ret);
            $meiid = $ret[0]["meiid"];
        } catch (Exception $e) {
            //var_dump($e);
            return '-1';
        }
        return $meiid;
    }

    //调用存储过程修改用户密码
    public static function modifyUserPwd($newpasswd) {
        return false;
        $db = IDBFactory::getDB();
        try { // 0邮箱 1手机
            $passwd = ISafe::get("password");
            if (ISafe::get("email")) {
                $usertype = 0;
                $ustring = ISafe::get("email");
            } else {
                $usertype = 1;
                $ustring = ISafe::get("mobile");
            }
            $db->query("call meicloud.proc_change_password( '$ustring', $usertype,'$passwd','$newpasswd', @ret)");
            $ret = $db->query("select @ret;");
            //var_dump($ret);
            return $ret;
        } catch (Exception $e) {
            //var_dump($e);
            return '-1';
        }
    }

    //调用存储过程重置密码
    public static function resetUserPwd($ustring, $usertype, $passwd) {
        return false;
        try {
            $db = IDBFactory::getDB();
            $db->query("call meicloud.proc_reset_password( '$ustring', $usertype,'$passwd','', @ret)");
            $ret = $db->query("select @ret;");
            return $ret[0]["@ret"];
        } catch (Exception $e) {
            //var_dump($e);
            return false;
        }
    }

    //调用存储过程验证手机/邮箱是否存在
    public static function checkUserExist($ustring, $usertype) {
        return false;
        try {
            $db = IDBFactory::getDB();
            $db->query("call meicloud.proc_check_user_exist( '$ustring', $usertype, @ret)");
            $ret = $db->query("select @ret;");
            if ($ret[0]["@ret"] == 1) {
                return 1;
            }
        } catch (Exception $e) {
            //var_dump($e);
            return false;
        }
        return 0;
    }

    //调用存储过程取用户信息（登录）
    public static function userLogin($ustring, $passwd, $usertype) {
        return false;
        $db = IDBFactory::getDB();
        try {
            //$mysqli = $db->linkRes;
            //$mysqli->autocommit(FALSE);
            $ret = array();
            $db->linkRes->multi_query("call meicloud.proc_login( '$ustring','$passwd','$usertype',@meiid,@mobile,@email,@face_url,@face_id,@username,@bindlist, @ret)");

            do {
                if ($result = $db->linkRes->store_result()) {
                    while ($row = $result->fetch_row()) {
                        array_push($ret, $row[0]);
                    }
                }
            } while ($db->linkRes->next_result());

            $ret = $db->query("select @meiid meiid,@mobile mobile,@email email,@face_url face_url,@face_id face_id,@username username,@bindlist bindlist,@ret;");
            if ($ret[0]["@ret"] == '-1') {
                return false;
            } else {
                //同步本地数据库
                $userObj = new IModel("user");
                $meiid = $ret[0]["meiid"];
                $userinfo = $userObj->getObj("meiid = $meiid");
                if ($userinfo) {
                    $userObj->setData(array("mobile" => $ret[0]["mobile"],
                        "email" => $ret[0]["email"],
                        "password" => $passwd,
                        "active" => 1));
                    $userObj->update("meiid = $meiid");
                } else {
                    //新增用户
                    $userArray = array(
                        'username' => $ret[0]["username"],
                        'password' => $passwd,
                        'email' => $ret[0]["email"],
                        'mobile' => $ret[0]["mobile"],
                        'type' => 1,
                        'active' => 1,
                        'meiid' => $meiid
                    );
                    $userObj->setData($userArray);
                    $user_id = $userObj->add();

                    if ($user_id) {
                        //member表
                        $memberArray = array(
                            'user_id' => $user_id,
                            'time' => ITime::getDateTime(),
                        );
                        $memberObj = new IModel('member');
                        $memberObj->setData($memberArray);
                        $memberObj->add();
                    }
                }
                return $ret[0];
            }
        } catch (Exception $e) {
            //var_dump($e);
            return false;
        }
    }
    
    //调用存储过程取用户信息 
    public static function getUserInfo($ustring, $usertype) {
        //return false;
        $db = IDBFactory::getDB();
        try {
            //$mysqli = $db->linkRes;
            //$mysqli->autocommit(FALSE);
            $ret = array();
            $db->linkRes->multi_query("call meicloud.proc_get_userinfo( '$ustring','$usertype',@meiid,@password,@mobile,@email,@face_url,@face_id,@username,@bindlist, @ret)");

            do {
                if ($result = $db->linkRes->store_result()) {
                    while ($row = $result->fetch_row()) {
                        array_push($ret, $row[0]);
                    }
                }
            } while ($db->linkRes->next_result());

            $ret = $db->query("select @meiid meiid, @password password, @mobile mobile,@email email,@face_url face_url,@face_id face_id,@username username,@bindlist bindlist,@ret;");
            if ($ret[0]["@ret"] == '-1') {
                return false;
            } else {
                //同步本地数据库
                $userObj = new IModel("user");
                $meiid = $ret[0]["meiid"];
                $userinfo = $userObj->getObj("meiid = $meiid");
                if ($userinfo) {
                    $userObj->setData(array("mobile" => $ret[0]["mobile"],
                        "email" => $ret[0]["email"],
                        "password" => $ret[0]["password"],
                        "active" => 1));
                    $userObj->update("meiid = $meiid");
                } else {
                    //新增用户
                    if($usertype=="0"){
                        $mtype = 1;
                    }else{
                        $mtype = 2;
                    }
                    $userArray = array(
                        'username' => $ret[0]["username"],
                        'password' => $ret[0]["password"],
                        'email' => $ret[0]["email"],
                        'mobile' => $ret[0]["mobile"],
                        'type' => $mtype,
                        'active' => 1,
                        'meiid' => $meiid
                    );
                    $userObj->setData($userArray);
                    $user_id = $userObj->add();

                    if ($user_id) {
                        //member表
                        $memberArray = array(
                            'user_id' => $user_id,
                            'time' => ITime::getDateTime(),
                        );
                        $memberObj = new IModel('member');
                        $memberObj->setData($memberArray);
                        $memberObj->add();
                    }
                }
                //输出数组
                $userArray = array(
                        'username' => $ret[0]["username"],
                        'password' => $passwd,
                        'email' => $ret[0]["email"],
                        'mobile' => $ret[0]["mobile"],
                        'type' => $mtype,
                        'active' => 1,
                        'meiid' => $meiid
                    );
                return $userArray;
            }
        } catch (Exception $e) {
            //var_dump($e);
            return false;
        }
    }

    //调用存储过程发短信
    public static function sendMsg($content, $number) {
        return false;
        if(!$number){
            return false;
        }
        try {
            $db = IDBFactory::getDB();
            $db->query("call meicloud.proc_add_shortmessage( '$content', '$number', @ret)");
            $ret = $db->query("select @ret;");
            if ($ret[0]["@ret"] == 1) {
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

}
