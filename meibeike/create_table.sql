create table `mbk_third_user` (
id int(11) not null auto_increment primary key,
uid int(11) not null unique comment '�û�UID',
openid varchar (64) not null comment '΢��openid',
unionid varchar (64) not null  comment '�������û�Ψһ��ʶUID',
account_type varchar(2) not null default '' comment '�˺�����7:wx,8:qq,9:weibo',
user_source text  comment '��վ��Դ:��www.baidu.com',
device_type varchar(16) not null default '' comment '1:pc,2:iphone,3:linux,4:android,5:ipad' 
)engine = myisam charset = utf8 COMMENT '�������û���'


create table `mbk_appointment_number` (
id int(11) not null auto_increment primary key,
number varchar (32) not null comment 'ԤԼ��',
status varchar(1) not null comment 'ԤԼ״̬',
create_time datetime comment '����ʱ��',
modify_time datetime comment '����ʱ��' 
)engine = myisam charset = utf8 COMMENT 'ԤԼ�ű�'


create table `mbk_appointment_number` (
id int(11) not null auto_increment primary key,
number varchar (32) not null comment 'ԤԼ��',
status varchar(1) not null comment 'ԤԼ״̬',
create_time datetime comment '����ʱ��',
modify_time datetime comment '����ʱ��' 
)engine = myisam charset = utf8 COMMENT 'ԤԼ�ű�'



create table `mbk_sms_vcode`(
id int(11) not null auto_increment primary key,
mobile varchar(16) not null comment '�ֻ���',
vcode varchar (8) not null comment '��֤��',
create_time datetime comment '����ʱ��'
)engine = myisam charset = utf8 COMMENT '��֤���'




