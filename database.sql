USE vlite;
CREATE TABLE user (
  id       INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
  COMMENT 'user id',
  username VARCHAR(50)  NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  email    VARCHAR(50)  NOT NULL UNIQUE,
  profile  TEXT,
  icon     VARCHAR(50)
)
  CHARSET utf8;

CREATE TABLE project (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
  COMMENT 'project id',
  title       VARCHAR(50)  NOT NULL,
  description TEXT,
  label       TEXT,
  create_time DATETIME     NOT NULL,
  owner       INT UNSIGNED NOT NULL REFERENCES user (id),
  file_name   VARCHAR(50)  NOT NULL,
  version     VARCHAR(25)  NOT NULL,
  stars       INT UNSIGNED
)
  CHARSET utf8;

CREATE TABLE project_update (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
  COMMENT 'update id',
  version     VARCHAR(25)  NOT NULL,
  project     INT UNSIGNED NOT NULL REFERENCES project (id),
  owner       INT UNSIGNED NOT NULL REFERENCES user (id),
  update_time DATETIME     NOT NULL,
  description TEXT
)
  CHARSET utf8;

CREATE TABLE issue_label (
  id    INT UNSIGNED                  NOT NULL AUTO_INCREMENT PRIMARY KEY
  COMMENT 'issue label id',
  name  VARCHAR(25)                   NOT NULL,
  color VARCHAR(25) DEFAULT '#FFFFFF' NOT NULL
)
  CHARSET utf8;

CREATE TABLE issue (
  id          INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY
  COMMENT 'issue id',
  title       VARCHAR(50)  NOT NULL,
  milestone   VARCHAR(50),
  description TEXT         NOT NULL,
  create_time DATETIME     NOT NULL,
  close_time  DATETIME,
  label       INT UNSIGNED NOT NULL REFERENCES issue_label (id),
  sponsor     INT UNSIGNED NOT NULL
  COMMENT '发起者' REFERENCES user (id),
  project     INT UNSIGNED NOT NULL REFERENCES project (id),
  owner       INT UNSIGNED NOT NULL
  COMMENT '项目拥有者' REFERENCES user (id),
  state       TINYINT(1)   NOT NULL
  COMMENT '1 for open 0 for close',
  is_read     TINYINT(1)   NOT NULL DEFAULT 0
  COMMENT '0 for not read'
)
  CHARSET utf8;

CREATE TABLE issue_comment (
  id           INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  content      TEXT,
  comment_time DATETIME     NOT NULL,
  sponsor      INT UNSIGNED NOT NULL
  COMMENT '发起者' REFERENCES user (id),
  issue        INT UNSIGNED NOT NULL REFERENCES issue (id),
  project      INT UNSIGNED NOT NULL REFERENCES project (id),
  owner        INT UNSIGNED NOT NULL REFERENCES user (id),
  is_read      TINYINT(1)   NOT NULL DEFAULT 0
)
  CHARSET utf8;

CREATE TABLE todo (
  id      INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  title   VARCHAR(50)  NOT NULL,
  content TEXT,
  issue   INT UNSIGNED NOT NULL REFERENCES issue (id),
  owner   INT UNSIGNED NOT NULL REFERENCES user (id),
  project INT UNSIGNED NOT NULL REFERENCES project (id)
)
  CHARSET utf8;