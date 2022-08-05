<?php

require __DIR__.'/Module/Database.php';
require __DIR__.'/Util/Util.php';

class Table extends  Database
{
    /**
     * Table constructor.
     */
    public function __construct()
    {
        parent::__construct();
        $this->install();
    }

    public function install(){

        // user table
        parent::createTable($this->encode(array(
            'name'=>'users',
            'value'=>"
             id int(11) not null auto_increment,
             firstname varchar(25) not null,
             lastname varchar(25) not null,
             username varchar(100) not null,
             avater text null,
             phoneNo int(11) not null,
             email varchar(100) null,
             password text not null, 
             gender enum('male','female','notspecified') default 'notspecified' not null,
             merital_status enum('Single','merriage','engaged','notspecified') default 'notspecified' not null,
             role enum('0','1','2') default '0' not null,
             bio text null,
             position varchar(255) null, 
             address varchar(255) not null,
             createdDate datetime not null,
             last_login datetime not null,
             privilege enum('0','1','2') default '0' not null,
             status enum('0','1') not null default '0',
             primary key(id)
             "
        )));

        // subscription for Ceremony
        parent::createTable($this->encode(array(
            'name'=>'subscription',
            'value'=>"
            subId int(11) not null auto_increment,
            categoryId int(11) not null,
            level enum('Free','Silver','Gold') default 'free' not null,
            subscriptionType enum('noType',ceremony','busness') default 'noType' not null,
            activeted enum('0',1') default '0' not null,
            duration varchar(50) not null,
            startTime datetime not null,
            endTime datetime not null,
            receiptNo varchar(200) not null,
            createdDate datetime not null,
            primary key(subId),
      
            "
        )));

        // subscritpion for Busness
        parent::createTable($this->encode(array(
            'name'=>'subscritpionbsn',
            'value'=>"
            subId int(11) not null auto_increment,
            busnessId int(11) not null,
            level enum('free','silver','Golden') default 'free' not null,
            subscriptionType enum('noType',ceremony','busness') default 'noType' not null,
            activated enum('0',1') default '0' not null,
            createdDate datetime not null default current_timestamp,
            primary key(subId),
      
            "
        )));


        // posts
        parent::createTable($this->encode(array(
            'name'=>'posts',
            'value'=>"
            pId int(11) not null auto_increment,
            createdBy int(11) null,
            ceremonyId int(11) null,
            body text,
            vedeo text,
            createdDate datetime not null default current_timestamp,
            primary key(pId)
            "
        )));


        // host List: will depricieted to  Service table
        parent::createTable($this->encode(array(
            'name'=>'hostlist',
            'value'=>"
            hostId  int(11) not null auto_increment,
            busnessId  int(11) not null,
            ceremonyId int(11) not null,
            createdBy int(11) not null,
            contact varchar(50) not null,
            confirm enum('0','1') default '0' not null,
            createdDate datetime not null default current_timestamp,
            primary key(hostId)
            "
        )));


        // Service table
        parent::createTable($this->encode(array(
            'name'=>' services',
            'value'=>"
            svId int(11) not null auto_increment,
            busnessId int(11) not null ,
            ceremonyId int(11) not null ,
            createdBy int(11) not null ,
            contact varchar(50) not null,
            confirm enum('0','1') default '0' not null,
            createdDate datetime not null default current_timestamp,
            primary key(svId)
            "
        )));

        // ceremony table
        parent::createTable($this->encode(array(
            'name'=>'ceremony',
            'value'=>"
            cId int(11) not null auto_increment,
            codeNo varchar(100) not null,
            cName varchar(100) not null,
            ceremonyType enum('none',Birthday','SendOff','Kitchen Part','Wedding','Kigodoro') default 'none' not null,
            fId int(11) not null,
            sId int(11) not null,
            admin int(11) null,
            cImage varchar(500) not null,
            ceremonyDate datetime not null,
            contact varchar(30) not null,
            goLiveId text,
            createdDate datetime not null default current_timestamp,
            primary key(cId)
            "
        )));

        // busnessstaff table
        parent::createTable($this->encode(array(
            'name'=>' busnessstaff',
            'value'=>"
            stId int(11) not null auto_increment,
            bId int(11) not null ,
            userId int(11) not null ,
            position varchar(50) not null,
            confirm enum('0','1') default '0' not null,
            createdDate datetime not null default current_timestamp,
            primary key(stId)
            "
        )));

        // busnesss Photos table
        parent::createTable($this->encode(array(
            'name'=>'busnessphoto',
            'value'=>"
            bPhotoId int(11) not null auto_increment,
            bId int(11) not null,
            photo varchar(500) not null,
            createdDate datetime not null default current_timestamp,
            primary key(bPhotoId)
            "
        )));

        // busness table
        parent::createTable($this->encode(array(
            'name'=>'ceremony',
            'value'=>"
            bId int(11) not null auto_increment,
            ceoId int(11) not null,
            createdBy int(11) not null,
            codeNo varchar(100) not null,
            cName varchar(100) not null,
            busnessType enum('none',Mc','Production','Decorator','Hall',
            'Cake Baker','Singer','Dancer','Saloon','Car','Cooker',) default 'none' not null,
            coProfile varchar(500) not null,
            knownAs varchar(100) not null,
            price varchar(100) not null,
            contact varchar(30) not null,
            location varchar(100) not null,
            companyName varchar(50) not null,
            aboutCEO text,
            aboutCompany text,
            hotStatus enum('0','1') default '0' not null,
            createdDate datetime not null default current_timestamp,
            primary key(bId)
            "
        )));

       

        // // notifications
        // parent::createTable($this->encode(array(
        //     'name'=>'notifications',
        //     'value'=>"
        //     id int(11) not null auto_increment,
        //     type varchar(255) not null,
        //     sender int(11) not null,
        //     receiver int(11) not null,
        //     title varchar(255) not null,
        //     content text,
        //     status enum('0','1') not null default '0',
        //     date datetime not null default current_timestamp,
        //     foreign key(sender) references users(id),
        //     foreign key(receiver) references users(id),
        //     primary key(id)
        //     "
        // )));

        // // payments
        // parent::createTable($this->encode(array(
        //     'name'=>'payments',
        //     'value'=>"
        //     id int(11) not null auto_increment,
        //     mnos varchar(255) not null,
        //     amount int(11) not null,
        //     user int(11) not null,
        //     status enum('0','1') not null default '0',
        //     date datetime not null default current_timestamp,
        //     foreign key(user) references users(id),
        //     primary key(id)
        //     "
        // )));


        // adding an admin
        $status = false;
        if(parent::select(parent::encode(array(
            "table"=>"users",
            "where"=>"username='admin1' AND email='admin1@gmail.com'"
        )))->num_rows < 1){
            $sql= parent::insert(parent::encode(array(
                "table"=>"users",
                "data"=>"firstname='JerMan', lastname='koosafi',
            username='admin1', email='admin1@gmail.com'
            ,password='db60e69108838102a03ba69c7', phoneNo='255743882455',
             address='Dar es salaam', createdDate=now(), last_login=now()"
            )));

            $status = ($sql);
        }

        if($status)
            echo "\n Installation was completed successful!\n";
        else
            print_r($this->con->error);
    }

}

new Table();