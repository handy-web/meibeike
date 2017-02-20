create table `mbk_third_user` (
id int(11) not null auto_increment primary key,
uid int(11) not null unique comment '用户UID',
openid varchar (64) not null comment '微信openid',
unionid varchar (64) not null  comment '第三方用户唯一标识UID',
account_type varchar(2) not null default '' comment '账号类型7:wx,8:qq,9:weibo',
user_source text  comment '网站来源:如www.baidu.com',
device_type varchar(16) not null default '' comment '1:pc,2:iphone,3:linux,4:android,5:ipad' 
)engine = myisam charset = utf8 COMMENT '第三方用户表'


create table `mbk_appointment_number` (
id int(11) not null auto_increment primary key,
number varchar (32) not null comment '预约号',
status varchar(1) not null comment '预约状态',
create_time datetime comment '创建时间',
modify_time datetime comment '更新时间' 
)engine = myisam charset = utf8 COMMENT '预约号表'


create table `mbk_appointment_number` (
id int(11) not null auto_increment primary key,
number varchar (32) not null comment '预约号',
status varchar(1) not null comment '预约状态',
create_time datetime comment '创建时间',
modify_time datetime comment '更新时间' 
)engine = myisam charset = utf8 COMMENT '预约号表'



create table `mbk_sms_vcode`(
id int(11) not null auto_increment primary key,
mobile varchar(16) not null comment '手机号',
vcode varchar (8) not null comment '验证码',
create_time datetime comment '创建时间'
)engine = myisam charset = utf8 COMMENT '验证码表'




