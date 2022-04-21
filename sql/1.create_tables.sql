/***************************************
* USERS
***************************************/

CREATE TABLE institutions(
  id   serial,
  name character varying(100) NOT NULL,
  
  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
ALTER TABLE institutions ADD CONSTRAINT institutions_pk PRIMARY KEY (id);
ALTER TABLE institutions ADD CONSTRAINT institutions_uk UNIQUE      (name);

CREATE TABLE users(
  id             serial,
  name           character varying(100) NOT NULL,
  lastname       character varying(100),
  email          character varying(140) NOT NULL,
  password       character varying(200),
  remember_token character varying(200),

  inst_id        integer,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE users ADD CONSTRAINT users_pk         PRIMARY KEY (id);
ALTER TABLE users ADD CONSTRAINT users_uk         UNIQUE (email);
ALTER TABLE users ADD CONSTRAINT users_inst_fk    FOREIGN KEY (inst_id) REFERENCES institutions (id) ON UPDATE CASCADE ON DELETE SET NULL;

/***************************************
* LEARNING DESIGNS
***************************************/

CREATE TABLE learning_designs(
  id          serial,
  course_name character varying(100) NOT NULL,
  modules     integer                NOT NULL,
  rows        integer                NOT NULL,

  creator_id  integer,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE learning_designs ADD CONSTRAINT learning_designs_pk         PRIMARY KEY (id);
ALTER TABLE learning_designs ADD CONSTRAINT learning_designs_creator_fk FOREIGN KEY (creator_id) REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL;

/*CREATE TABLE action_operand_types(
  id serial,
  operand character varying(5)
);

ALTER TABLE action_operand_types ADD CONSTRAINT action_operand_types_pk PRIMARY KEY (id);
ALTER TABLE action_operand_types ADD CONSTRAINT action_operand_types_uk UNIQUE (operand);*/

CREATE TABLE resource_types(
  id   serial,
  name character varying(30) NOT NULL
);

ALTER TABLE resource_types ADD CONSTRAINT resource_types_pk PRIMARY KEY (id);
ALTER TABLE resource_types ADD CONSTRAINT resource_types_uk UNIQUE (name);

CREATE TABLE resources(
  id          serial,
  module      integer NOT NULL,
  row         integer NOT NULL,

  type_id     integer NOT NULL,
  learning_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
 
ALTER TABLE resources ADD CONSTRAINT resources_pk          PRIMARY KEY (id);
ALTER TABLE resources ADD CONSTRAINT resources_type_fk     FOREIGN KEY (type_id)     REFERENCES resource_types       (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE resources ADD CONSTRAINT resources_learning_fk FOREIGN KEY (learning_id) REFERENCES learning_designs     (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE learning_design_access(
  id          serial,
  user_id     integer NOT NULL,
  learning_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE learning_design_access ADD CONSTRAINT learning_design_access_pk            PRIMARY KEY (id);
ALTER TABLE learning_design_access ADD CONSTRAINT learning_design_access_uk            UNIQUE (user_id, learning_id);
ALTER TABLE learning_design_access ADD CONSTRAINT learning_design_access_user_fk       FOREIGN KEY (user_id)     REFERENCES users           (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE learning_design_access ADD CONSTRAINT learning_design_access_leadesign_fk  FOREIGN KEY (learning_id) REFERENCES learning_designs(id) ON UPDATE CASCADE ON DELETE CASCADE;

/***************************************
* GAMIFICATION DESIGNS
***************************************/

CREATE TABLE gamification_designs(
  id          serial,
  name        character varying(100),

  creator_id  integer,
  learning_id integer,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE gamification_designs ADD CONSTRAINT gamification_designs_pk           PRIMARY KEY (id);
ALTER TABLE gamification_designs ADD CONSTRAINT gamification_designs_uk           UNIQUE (name,creator_id);
ALTER TABLE gamification_designs ADD CONSTRAINT gamification_designs_creator_fk   FOREIGN KEY (creator_id)  REFERENCES users (id) ON UPDATE CASCADE ON DELETE SET NULL;
ALTER TABLE gamification_designs ADD CONSTRAINT gamification_designs_learning_fk  FOREIGN KEY (learning_id) REFERENCES learning_designs (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE gamification_design_access(
  id              serial,
  user_id         integer NOT NULL,
  gamification_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE gamification_design_access ADD CONSTRAINT gamification_design_access_pk            PRIMARY KEY (id);
ALTER TABLE gamification_design_access ADD CONSTRAINT gamification_design_access_uk            UNIQUE (user_id, gamification_id);
ALTER TABLE gamification_design_access ADD CONSTRAINT gamification_design_access_user_fk       FOREIGN KEY (user_id)         REFERENCES users               (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE gamification_design_access ADD CONSTRAINT gamification_design_access_gamdesign_fk  FOREIGN KEY (gamification_id) REFERENCES gamification_designs(id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE gamification_engines(
  id           serial,
  name         character varying(100),
  description  character varying(500) NOT NULL,
  condition_op character varying(5) NOT NULL,

  gdesign_id   integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);
ALTER TABLE gamification_engines ADD CONSTRAINT gamification_engines_pk  PRIMARY KEY (id);
ALTER TABLE gamification_engines ADD CONSTRAINT gamification_design_fk   FOREIGN KEY (gdesign_id) REFERENCES gamification_designs (id) ON UPDATE CASCADE ON DELETE CASCADE;

/***************************************
* GAMIFICATION REWARDS
***************************************/

CREATE TABLE reward_types(
  id   serial,
  name character varying(20) NOT NULL
);

ALTER TABLE reward_types ADD CONSTRAINT reward_types_pk   PRIMARY KEY (id);
ALTER TABLE reward_types ADD CONSTRAINT reward_types_uk   UNIQUE (name);

CREATE TABLE rewards(
  id   serial,
  name character varying(100),

  url_image character varying(500),
  quantity  integer,

  reward_type_id  integer NOT NULL,
  engine_id       integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE rewards ADD CONSTRAINT rewards_pk           PRIMARY KEY (id);
ALTER TABLE rewards ADD CONSTRAINT rewards_type_fk      FOREIGN KEY (reward_type_id)  REFERENCES reward_types         (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE rewards ADD CONSTRAINT rewards_gamengine_fk FOREIGN KEY (engine_id)       REFERENCES gamification_engines (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE points(
  id        serial,
  reward_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE points ADD CONSTRAINT points_pk   PRIMARY KEY (id);
ALTER TABLE points ADD CONSTRAINT points_rewards_fk  FOREIGN KEY (reward_id) REFERENCES rewards (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE levels(
  id        serial,
  reward_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE levels ADD CONSTRAINT levels_pk          PRIMARY KEY (id);
ALTER TABLE levels ADD CONSTRAINT levels_rewards_fk  FOREIGN KEY (reward_id) REFERENCES rewards (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE badge_suites(
  id          serial,
  gdesign_id  integer NOT NULL,
  name        character varying(100) NOT NULL,
  description character varying(500),
  num_badges  integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE badge_suites ADD CONSTRAINT badge_suites_pk     PRIMARY KEY (id);
ALTER TABLE badge_suites ADD CONSTRAINT suite_gdesign_fk    FOREIGN KEY (gdesign_id) REFERENCES gamification_designs (id) ON UPDATE CASCADE ON DELETE CASCADE;


CREATE TABLE badges(
  id            serial,
  reward_id     integer NOT NULL,
  suite_id      integer,
  suite_quality integer,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE badges ADD CONSTRAINT badges_pk              PRIMARY KEY (id);
--ALTER TABLE badges ADD CONSTRAINT badges_uk              UNIQUE (suite_id, suite_quality);
ALTER TABLE badges ADD CONSTRAINT badges_rewards_fk      FOREIGN KEY (reward_id) REFERENCES rewards (id)     ON UPDATE CASCADE ON DELETE RESTRICT;
--ALTER TABLE badges ADD CONSTRAINT game_suites_fk         FOREIGN KEY (suite_id) REFERENCES badge_suites (id) ON UPDATE CASCADE ON DELETE SET NULL;

CREATE TABLE rr_types(
  id                serial,
  name              character varying(50)  NOT NULL,
  extra_parameters  integer NOT NULL,
  input_type        character varying(10)  NOT NULL,
  tip               character varying(100) NOT NULL
);

ALTER TABLE rr_types ADD CONSTRAINT rr_types_pk   PRIMARY KEY (id);
ALTER TABLE rr_types ADD CONSTRAINT rr_types_uk   UNIQUE (name);

CREATE TABLE resource_rr(
  id               serial,
  resource_type_id integer NOT NULL,
  rr_type_id       integer NOT NULL
);

ALTER TABLE resource_rr ADD CONSTRAINT resource_rr_pk            PRIMARY KEY (id);
ALTER TABLE resource_rr ADD CONSTRAINT resource_rr_uk            UNIQUE (resource_type_id, rr_type_id);
ALTER TABLE resource_rr ADD CONSTRAINT resource_rr_resources_fk  FOREIGN KEY (resource_type_id)   REFERENCES resource_types  (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE resource_rr ADD CONSTRAINT resource_rr_rr_fk         FOREIGN KEY (rr_type_id)         REFERENCES rr_types        (id) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE redeemable_rewards(
  id     serial,
  param_1 character varying(20),

  reward_id   integer NOT NULL,
  rr_type_id  integer NOT NULL,
  resource_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE redeemable_rewards ADD CONSTRAINT rr_pk       PRIMARY KEY (id);
ALTER TABLE redeemable_rewards ADD CONSTRAINT rr_rewards_fk  FOREIGN KEY (reward_id)   REFERENCES rewards   (id)  ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE redeemable_rewards ADD CONSTRAINT rr_type_fk  FOREIGN KEY (rr_type_id)  REFERENCES rr_types  (id)  ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE redeemable_rewards ADD CONSTRAINT rr_resource_fk FOREIGN KEY (resource_id) REFERENCES resources (id)  ON UPDATE CASCADE ON DELETE CASCADE;

/***************************************
* GAMIFICATION CONDITIONS
***************************************/

CREATE TABLE condition_types(
  id      serial,
  name    character varying(30) NOT NULL,
  enabled boolean default false NOT NULL
);

ALTER TABLE condition_types ADD CONSTRAINT condition_types_pk   PRIMARY KEY (id);
ALTER TABLE condition_types ADD CONSTRAINT condition_types_uk   UNIQUE (name);

CREATE TABLE conditions(
  id          serial,
  description character varying(100) NOT NULL,

  condition_type_id integer NOT NULL,
  engine_id         integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE conditions ADD CONSTRAINT conditions_pk           PRIMARY KEY (id);
ALTER TABLE conditions ADD CONSTRAINT conditions_type_fk      FOREIGN KEY (condition_type_id)  REFERENCES condition_types         (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE conditions ADD CONSTRAINT conditions_gamengine_fk FOREIGN KEY (engine_id)          REFERENCES gamification_engines    (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE reward_conditions(
  id      serial,
  param_1 character varying(30),

  reward_type_id integer NOT NULL,
  condition_id   integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE reward_conditions ADD CONSTRAINT reward_conditions_pk              PRIMARY KEY (id);
ALTER TABLE reward_conditions ADD CONSTRAINT reward_conditions_type_fk         FOREIGN KEY (reward_type_id)  REFERENCES reward_types   (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE reward_conditions ADD CONSTRAINT reward_conditions_conditions_fk   FOREIGN KEY (condition_id)    REFERENCES conditions     (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE group_conditions(
  id                 serial,
  student_percentage integer NOT NULL,

  condition_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE group_conditions ADD CONSTRAINT group_conditions_pk              PRIMARY KEY (id);
ALTER TABLE group_conditions ADD CONSTRAINT group_conditions_conditions_fk   FOREIGN KEY (condition_id)    REFERENCES conditions  (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE resource_conditions(
  id              serial,
  resource_op     character varying(5),
  action_op       character varying(5) NOT NULL,

  resource_id     integer NOT NULL,
  condition_id    integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE resource_conditions ADD CONSTRAINT resource_conditions_pk              PRIMARY KEY (id);
ALTER TABLE resource_conditions ADD CONSTRAINT resource_conditions_resource_fk     FOREIGN KEY (resource_id)     REFERENCES resources   (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE resource_conditions ADD CONSTRAINT resource_conditions_conditions_fk   FOREIGN KEY (condition_id)    REFERENCES conditions  (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE action_types(
  id   serial,
  name character varying(70) NOT NULL
);

ALTER TABLE action_types ADD CONSTRAINT actions_types_pk PRIMARY KEY (id);
ALTER TABLE action_types ADD CONSTRAINT actions_types_uk UNIQUE (name);

CREATE TABLE actions(
  id          serial,
  res_cond_id integer  NOT NULL,
  type_id     integer  NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE actions ADD CONSTRAINT actions_pk               PRIMARY KEY (id);
ALTER TABLE actions ADD CONSTRAINT actions_resource_cond_fk FOREIGN KEY (res_cond_id)  REFERENCES resource_conditions  (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE actions ADD CONSTRAINT actions_type_fk          FOREIGN KEY (type_id)      REFERENCES action_types         (id) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE rule_types(
  id               serial,
  name             character varying(200) NOT NULL,
  extra_parameters integer                NOT NULL,
  input_type       character varying(10)  NOT NULL,
  tip              character varying(100) NOT NULL
);

ALTER TABLE rule_types ADD CONSTRAINT rule_types_pk PRIMARY KEY (id);
ALTER TABLE rule_types ADD CONSTRAINT rule_types_uk UNIQUE (name);

CREATE TABLE rules(
  id      serial,
  param_1 character varying(30),
  param_2 character varying(30),

  action_id integer  NOT NULL,
  type_id   integer  NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE rules ADD CONSTRAINT rules_pk        PRIMARY KEY (id);
ALTER TABLE rules ADD CONSTRAINT rule_action_fk  FOREIGN KEY (action_id)  REFERENCES actions     (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE rules ADD CONSTRAINT rule_type_fk    FOREIGN KEY (type_id)    REFERENCES rule_types  (id) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE resource_action(
  id               serial,
  resource_type_id integer NOT NULL,
  action_type_id   integer NOT NULL
);

ALTER TABLE resource_action ADD CONSTRAINT resource_action_pk            PRIMARY KEY (id);
ALTER TABLE resource_action ADD CONSTRAINT resource_action_uk            UNIQUE (resource_type_id, action_type_id);
ALTER TABLE resource_action ADD CONSTRAINT resource_action_resources_fk  FOREIGN KEY (resource_type_id)   REFERENCES resource_types  (id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE resource_action ADD CONSTRAINT resource_action_actions_fk    FOREIGN KEY (action_type_id)     REFERENCES action_types    (id) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE action_rule(
  id             serial,
  action_type_id integer NOT NULL,
  rule_type_id   integer NOT NULL
);

ALTER TABLE action_rule ADD CONSTRAINT action_rule_pk          PRIMARY KEY (id);
ALTER TABLE action_rule ADD CONSTRAINT action_rule_uk          UNIQUE (action_type_id, rule_type_id);
ALTER TABLE action_rule ADD CONSTRAINT action_rule_rules_fk    FOREIGN KEY (action_type_id)     REFERENCES action_types    (id) ON UPDATE CASCADE ON DELETE RESTRICT;
ALTER TABLE action_rule ADD CONSTRAINT action_rule_actions_fk  FOREIGN KEY (rule_type_id)       REFERENCES rule_types      (id) ON UPDATE CASCADE ON DELETE RESTRICT;

CREATE TABLE deploy_types(
  id         serial,
  name       character varying(30) NOT NULL,
  enabled    boolean default false NOT NULL
);

ALTER TABLE deploy_types ADD CONSTRAINT deploy_types_pk PRIMARY KEY (id);
ALTER TABLE deploy_types ADD CONSTRAINT deploy_types_uk UNIQUE (name);

CREATE TABLE gamification_deploys(
  id                serial,
  gdesign_id        integer NOT NULL,
  instance_type_id  integer NOT NULL,
  instance_url      character varying(300) NOT NULL,
  course_id         integer NOT NULL,
  bearer            character varying(200) NOT NULL,

  creator_id  integer,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE gamification_deploys ADD CONSTRAINT gamification_deploys_pk         PRIMARY KEY (id);
ALTER TABLE gamification_deploys ADD CONSTRAINT gamification_deploys_gdesign_fk FOREIGN KEY (gdesign_id)       REFERENCES gamification_designs (id) ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE gamification_deploys ADD CONSTRAINT gamification_deploys_types_fk   FOREIGN KEY (instance_type_id) REFERENCES deploy_types         (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE resource_deploy(
  id                    serial,
  resource_id           integer NOT NULL,
  deploy_id             integer NOT NULL,
  instance_resource_id  integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE resource_deploy ADD CONSTRAINT resource_deploy_pk           PRIMARY KEY (id);
ALTER TABLE resource_deploy ADD CONSTRAINT resource_deploy_resource_fk  FOREIGN KEY (resource_id)   REFERENCES resources (id)             ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE resource_deploy ADD CONSTRAINT resource_deploy_deploy_fk    FOREIGN KEY (deploy_id)     REFERENCES gamification_deploys (id)  ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE students(
  id                 serial,
  gdeploy_id         integer NOT NULL,

  id_instance        integer NOT NULL,
  email_instance     character varying(140),
  name_instance      character varying(300),

  enrolled_at TIMESTAMP,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE students ADD CONSTRAINT students_pk         PRIMARY KEY (id);
--ALTER TABLE students ADD CONSTRAINT students_gdeploy_fk FOREIGN KEY (gdeploy_id) REFERENCES gamification_deploys (id) ON UPDATE CASCADE ON DELETE CASCADE;

CREATE TABLE student_engine(
  id                    serial,
  student_id            integer NOT NULL,
  engine_id             integer NOT NULL,
  issued                boolean NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP,
  updated_at TIMESTAMP
);

ALTER TABLE student_engine ADD CONSTRAINT student_engine_pk           PRIMARY KEY (id);
ALTER TABLE student_engine ADD CONSTRAINT student_engine_student_fk   FOREIGN KEY (student_id)   REFERENCES students (id)              ON UPDATE CASCADE ON DELETE CASCADE;
ALTER TABLE student_engine ADD CONSTRAINT student_engine_engine_fk    FOREIGN KEY (engine_id)    REFERENCES gamification_engines (id)  ON UPDATE CASCADE ON DELETE CASCADE;
