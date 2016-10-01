CREATE TABLE permission__directory(
	id int not null auto_increment,
	directoryName varchar(200) not null default '/' comment 'The directory that is affected',
	visible boolean not null default false comment 'Can the user see this directory',
	reading boolean not null default false comment 'Can the user read from this directory',
	writing boolean not null default false comment 'Can the user write into the directory',
	userId int not null default 0 comment 'The user this applies to',
	add primary(id)
);