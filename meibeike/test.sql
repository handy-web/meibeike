create table `mbk_fm_activity`(
id int(11) unsigned not null auto_increment,
activity_name varchar(128) not null comment '活动名称',
min_code varchar(16) default null comment 'F码最小取值',
max_code varchar(16) default null comment 'F码最大取值',
fm_count int(10) default 0 comment 'F码数量',
start_time datetime default null comment '活动开始时间',
end_time datetime default null comment '活动结束时间',
prize_type tinyint(1) default null comment '使用奖品类型',
prize_limit varchar(64) default null comment '使用奖品金额值',
coupon_start_time datetime default null comment '代金券使用开始日期',
coupon_end_time datetime default null comment '代金券使用结束日期',
prize_comment varchar(200) default null comment '使用奖品说明',
exchange_count varchar(16) default null comment 'F码被使用的数量到达条件才可以兑奖',
exchange_prize_type tinyint(1) default null comment '兑换奖品类型',
exchange_prize_limit varchar(64) default null comment '兑换奖品金额',
exchange_prize_comment varchar(200) default null comment '兑换奖品说明',
status tinyint(1) default 0  comment '0:审核中,1:正常',
add_time datetime default null comment '活动添加时间',
admin_id int(5) default null comment '管理员ID',
primary key(`id`)
)engine = myisam charset = utf8 collate = utf8_general_ci comment 'F码活动表';


create table `mbk_fm`(
id int(11) unsigned not null auto_increment,
aid int(11) unsigned not null comment '跟F码活动表关联',
f_code varchar(10) not null comment 'F码',
owner_meiid int(11) unsigned default null comment 'F码拥有者ID',
send_status tinyint(1) default 0  comment 'F码发放状态:0,未发放;1,已发放',
send_time datetime default null comment '发放日期',
user_meiid int(11) unsigned default null comment '使用者ID',
order_number varchar(16) default null comment '不为空则表示此F码被使用',
use_time datetime default null comment '使用时间',
exchange_status tinyint(1) default 0 comment '0：未兑换,1:已兑换',
exchange_time datetime default null comment '兑换时间',
primary key(`id`),
index (`owner_meiid`),
index(`user_meiid`),
index(`f_code`),
index(`aid`)
)engine = myisam charset = utf8  collate = utf8_general_ci comment 'F码表';


create table `mbk_fm_apply`(
id int(11) unsigned not null auto_increment,
apply_meiid int(11) unsigned default null comment '申请者ID',
apply_count varchar(16) default null comment '申请个数',
apply_comment text default null comment '申请说明',
apply_status tinyint(1) default 0 comment '0：申请中，1：已同意,2:已拒绝',
apply_time datetime default null comment '申请时间',
contact_way varchar(128) default null comment '联系方式',
admin_id int(5) default null comment '管理员ID',
review_time datetime default null comment '审核时间',
ip varchar(15) default null comment '申请者IP',
apply_from varchar(200) default null comment '申请来源',
primary key (`id`),
index (`apply_meiid`)
)engine = myisam charset = utf8 collate = utf8_general_ci comment 'F码申请表';



alter table `mbk_suggestion` add column `ip` varchar(15) default null after `re_time`;

alter table `mbk_suggestion` add column `suggestion_type` tinyint(1) default 0  comment '问题类型' after `content`;

alter table `mbk_suggestion` add column `contact_way` varchar(128) default null  comment '问题类型' after `suggestion_type`;

alter table `mbk_fm` drop index `f_code`;

alter table `mbk_fm` add unique `f_code` (`f_code`);

alter table `mbk_fm` change `order_number` `order_number` varchar(50) default null comment '订单号';

alter table `mbk_fm` add column `is_shared` tinyint(1) default 0 comment 'F码是否已分享' after `exchange_time`;

create table `mbk_config`(
id int(11) unsigned not null auto_increment,
field1 varchar(256) not null default '' comment '字段1',
config_type varchar(256) not null default '' comment '配置类型',
primary key(`id`)
)engine = myisam charset = utf8  collate = utf8_general_ci comment '系统配置表';

insert into `mbk_config` (field1,config_type) values ('48','订单配置');



