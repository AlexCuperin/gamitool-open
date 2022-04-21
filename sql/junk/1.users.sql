BEGIN;
CREATE TABLE institutions(
  id     serial NOT NULL,
  name   character varying(100) NOT NULL,
  
  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT institutions_pk PRIMARY KEY (id),
  CONSTRAINT institutions_uk UNIQUE      (name)
);

CREATE TABLE users(
  id                    serial NOT NULL,
  name                  character varying(100) NOT NULL,
  lastname              character varying(100),
  email                 character varying(140) NOT NULL,
  password              character varying(200),
  remember_token        character varying(200),

  inst_id               integer,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT users_pk         PRIMARY KEY (id),
  CONSTRAINT users_uk         UNIQUE (email),
  CONSTRAINT users_inst_fk    FOREIGN KEY (inst_id) REFERENCES institutions (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL
);


CREATE TABLE learning_designs(
  id          serial                 NOT NULL,
  course_name character varying(100) NOT NULL,
  modules     integer                NOT NULL,
  rows        integer                NOT NULL,

  creator_id  integer,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT learning_designs_pk         PRIMARY KEY (id),
  CONSTRAINT learning_designs_creator_fk FOREIGN KEY (creator_id) REFERENCES users (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL
);

CREATE TABLE learning_design_access(
  id          serial  NOT NULL,
  user_id     integer NOT NULL,
  learning_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT learning_design_access_pk            PRIMARY KEY (id),
  CONSTRAINT learning_design_access_uk            UNIQUE (user_id, learning_id),
  CONSTRAINT learning_design_access_user_fk       FOREIGN KEY (user_id)     REFERENCES users           (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT learning_design_access_leadesign_fk  FOREIGN KEY (learning_id) REFERENCES learning_designs(id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE gamification_designs(
  id          serial,
  name        character varying(100),

  creator_id  integer,
  learning_id integer,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT gamification_designs_pk           PRIMARY KEY (id),
  CONSTRAINT gamification_designs_uk           UNIQUE (name,creator_id),
  CONSTRAINT gamification_designs_creator_fk   FOREIGN KEY (creator_id)  REFERENCES users (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT gamification_designs_learning_fk  FOREIGN KEY (learning_id) REFERENCES learning_designs (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE gamification_design_access(
  id                     serial  NOT NULL,
  user_id                integer NOT NULL,
  gamification_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT gamification_design_access_pk            PRIMARY KEY (id),
  CONSTRAINT gamification_design_access_uk            UNIQUE (user_id, gamification_id),
  CONSTRAINT gamification_design_access_user_fk       FOREIGN KEY (user_id)                REFERENCES users               (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT gamification_design_access_gamdesign_fk  FOREIGN KEY (gamification_id) REFERENCES gamification_designs(id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE activities(
  id          serial  NOT NULL,
  module      integer NOT NULL,
  row         integer NOT NULL,

  type_id     integer NOT NULL,
  learning_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT activities_pk          PRIMARY KEY (id),
  CONSTRAINT activities_type_fk     FOREIGN KEY (type_id)     REFERENCES activity_types       (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT activities_learning_fk FOREIGN KEY (learning_id) REFERENCES learning_designs     (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
);

CREATE TABLE activity_types(
  id   serial NOT NULL,
  name character varying(30),

  CONSTRAINT activity_types_pk PRIMARY KEY (id),
  CONSTRAINT activity_types_uk UNIQUE (name)
);

/* Alex!
CREATE TABLE gamified_activities(
  id          serial  NOT NULL,
  action_op   character varying(5),

  criteria_id integer NOT NULL,
  activity_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT gamified_activities_pk  PRIMARY KEY (id),
  CONSTRAINT activities_fk           FOREIGN KEY (activity_id)              REFERENCES activities                (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,
  CONSTRAINT gamification_critera_fk FOREIGN KEY (gamification_criteria_id) REFERENCES gamification_criteria     (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
  -- CONSTRAINT activities_actoper_fk   FOREIGN KEY (act_oper_id)              REFERENCES action_operand_types      (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,
);*/

/* Yo creo que esto no es necesario --
CREATE TABLE action_operand_types(

  id      serial NOT NULL,
  operand character varying(5),

  CONSTRAINT action_operand_types_pk PRIMARY KEY (id),
  CONSTRAINT action_operand_types_uk UNIQUE (operand)
);  */

/* Alex!
CREATE TABLE actions(
  id               serial  NOT NULL,
  --- type_id      integer NOT NULL,
  gam_activity_id  integer NOT NULL,
  condition_op     character varying(5),

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),

  CONSTRAINT actions_pk              PRIMARY KEY (id),
  CONSTRAINT actions_gam_activity_fk FOREIGN KEY (gam_activity_id)  REFERENCES gamified_activities           (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
  -- CONSTRAINT actions_type_fk      FOREIGN KEY (type_id)          REFERENCES action_types                  (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,
  -- CONSTRAINT actions_condoper_fk  FOREIGN KEY (cond_oper_id)     REFERENCES action_operand_types          (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE SET NULL,
); */



CREATE TABLE gamification_engines(
  id      serial NOT NULL,

  CONSTRAINT gamification_engines_pk   PRIMARY KEY (id)
);

CREATE TABLE reward_types(
  id   serial NOT NULL,
  type character varying(20),

  CONSTRAINT reward_types_pk   PRIMARY KEY (id),
  CONSTRAINT reward_types_uk   UNIQUE (type)
);

CREATE TABLE rewards(
  id                     serial  NOT NULL,
  type_id                integer NOT NULL,
  gamification_engine_id integer NOT NULL,

  -- Laravel ORM dates
  created_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  updated_at TIMESTAMP(0) DEFAULT (CURRENT_TIMESTAMP AT TIME ZONE 'UTC'),
  
  CONSTRAINT rewards_pk           PRIMARY KEY (id),
  CONSTRAINT rewards_type_fk      FOREIGN KEY (type_id)                REFERENCES reward_types         (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE,
  CONSTRAINT rewards_gamengine_fk FOREIGN KEY (gamification_engine_id) REFERENCES gamification_engines (id) MATCH SIMPLE ON UPDATE CASCADE ON DELETE CASCADE
);

ROLLBACK;
--END;
