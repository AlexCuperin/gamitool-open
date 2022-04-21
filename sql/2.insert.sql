INSERT INTO institutions (name)
    VALUES  ('Universidad de Valladolid'),
            ('Universidad Pompeu Fabra'),
            ('Universidad Carlos III Madrid');

INSERT INTO users (name, lastname, email, password, inst_id)
    VALUES  ('Alejandro', 'Ortega-Arranz', 'alex.detec@gmail.com', 'pass', (SELECT id FROM institutions WHERE name='Universidad de Valladolid')),
            ('Alejandra', 'Martinez-Mones', 'amartine@infor.uva.es', 'pass', (SELECT id FROM institutions WHERE name='Universidad de Valladolid'));

INSERT INTO learning_designs (course_name, modules, rows, creator_id)
    VALUES  ('Innovative Collaborative Learning with ICT', '5', '5', (SELECT id FROM users WHERE email='alex.detec@gmail.com')),
            ('Por las Sendas de la Reconquista', '6', '4', (SELECT id FROM users WHERE email='alex.detec@gmail.com'));

INSERT INTO gamification_designs (created_at, name, creator_id, learning_id)
    VALUES  ('2018-01-04','Gam_v1',(SELECT id FROM users WHERE email='alex.detec@gmail.com'),(SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT')),
            ('2018-01-07','Gam_v2',(SELECT id FROM users WHERE email='amartine@infor.uva.es'),(SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'));

INSERT INTO learning_design_access (user_id, learning_id)
    VALUES  ((SELECT id FROM users WHERE email='alex.detec@gmail.com'),(SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT')),
            ((SELECT id FROM users WHERE email='alex.detec@gmail.com'),(SELECT id FROM learning_designs WHERE course_name='Por las Sendas de la Reconquista')),
            ((SELECT id FROM users WHERE email='amartine@infor.uva.es'),(SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT')),
            ((SELECT id FROM users WHERE email='amartine@infor.uva.es'),(SELECT id FROM learning_designs WHERE course_name='Por las Sendas de la Reconquista'));

INSERT INTO gamification_design_access (user_id, gamification_id)
    VALUES  ((SELECT id FROM users WHERE email='alex.detec@gmail.com'),(SELECT id FROM gamification_designs WHERE name='Gam_v1')),
            ((SELECT id FROM users WHERE email='alex.detec@gmail.com'),(SELECT id FROM gamification_designs WHERE name='Gam_v2')),
            ((SELECT id FROM users WHERE email='amartine@infor.uva.es'),(SELECT id FROM gamification_designs WHERE name='Gam_v1')),
            ((SELECT id FROM users WHERE email='amartine@infor.uva.es'),(SELECT id FROM gamification_designs WHERE name='Gam_v2'));

INSERT INTO resource_types (name)
    VALUES  ('Platform'),
            ('Content Page'),
            ('Discussion Forum'),
            ('Quiz'),
            ('Assignment'),
            ('Peer Review'),
            ('Wiki'),
            ('File'),
            ('External URL'),
            ('External Tool'),
            ('3DVW');

INSERT INTO resources (learning_id, module, row, type_id)
    VALUES  ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'0','0',(SELECT id FROM resource_types WHERE name='Platform')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'1','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'1','2',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'1','3',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'1','4',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'1','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'2','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'2','2',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'2','3',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'2','4',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'2','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'3','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'3','2',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'3','3',(SELECT id FROM resource_types WHERE name='Peer Review')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'3','4',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'3','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'4','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'4','2',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'4','3',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'4','4',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'4','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'5','1',(SELECT id FROM resource_types WHERE name='Peer Review')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'5','2',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT'),'5','3',(SELECT id FROM resource_types WHERE name='Quiz'));

INSERT INTO gamification_engines (gdesign_id, name, description, condition_op)
    VALUES  ((SELECT id FROM gamification_designs WHERE name='Gam_v1'), 'Quiz Pro', 'Earn an extra attempt in Quiz 3 and Quiz 4 by scoring at least 75% in Quiz 1 and Quiz 2!','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1'), 'Forum Pro', 'Become Teacher Assistant of course forums by receiving more than 20 likes in the forums','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1'), 'Group Pro', 'Teachers will evaluate your Assignment 4 by receiving the approval of 50% of your team based on your work in Group Task Assingment 2','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1'), 'Wanted', 'Earn 1 extra minute in Quiz 5 (Final) by uploading a profile picture','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1'), 'The King', 'Skip the Quiz 5 (Final) by scoring at least 75% in Quiz 1, Quiz 2, Quiz 3, Quiz 4','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1'), 'The Adventurer', 'Unlock extra course content in Module 5 by wisiting all content pages of the course','and');

INSERT INTO rr_types (name, extra_parameters, input_type, tip)
    VALUES  ('Unlock Resource'                  ,0,'',''),
            ('Final Certificate Discount'       ,1,'number','Which percentage?'),
            ('Unlock Features'                  ,0,'',''),
            ('Teacher Assistant'                ,0,'',''),
            ('Extra Attempts'                   ,1,'number','How many attempts?'),
            ('Extra Time'                       ,1,'number','How many seconds?'),
            ('Skip'                             ,0,'',''),
            ('Lower Score'                      ,1,'number','Which score?'),
            ('Extending Due Date'               ,1,'date','Which date?'),
            ('Re-open'                          ,1,'integer','How many days?'),
            ('Teachers Evaluation'              ,0,'',''),
            ('Individual or Collective'         ,1,'text','Individual or collective?');

INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'), (SELECT id FROM rr_types WHERE name='Final Certificate Discount'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'), (SELECT id FROM rr_types WHERE name='Teacher Assistant'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Content Page'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'), (SELECT id FROM rr_types WHERE name='Unlock Features'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'), (SELECT id FROM rr_types WHERE name='Extra Attempts'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'), (SELECT id FROM rr_types WHERE name='Extra Time'));

INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'), (SELECT id FROM rr_types WHERE name='Skip'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'), (SELECT id FROM rr_types WHERE name='Re-open'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'), (SELECT id FROM rr_types WHERE name='Lower Score'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'), (SELECT id FROM rr_types WHERE name='Extending Due Date'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'), (SELECT id FROM rr_types WHERE name='Skip'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'), (SELECT id FROM rr_types WHERE name='Lower Score'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'), (SELECT id FROM rr_types WHERE name='Extending Due Date'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'), (SELECT id FROM rr_types WHERE name='Re-open'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'), (SELECT id FROM rr_types WHERE name='Teachers Evaluation'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'), (SELECT id FROM rr_types WHERE name='Individual or Collective'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));

INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'), (SELECT id FROM rr_types WHERE name='Teachers Evaluation'));

INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Wiki'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='File'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='External URL'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='External Tool'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));
INSERT INTO resource_rr (resource_type_id, rr_type_id) VALUES ((SELECT id FROM resource_types WHERE name='3DVW'), (SELECT id FROM rr_types WHERE name='Unlock Resource'));

INSERT INTO reward_types (name)
    VALUES  ('Redeemable Rewards'),
            ('Points'),
            ('Levels'),
            ('Badges');


INSERT INTO rewards (reward_type_id, name, url_image, quantity, engine_id)
    VALUES  ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Extra Attempt in Quiz 3',NULL,NULL,(SELECT id FROM gamification_engines WHERE name='Quiz Pro')),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Extra Attempt in Quiz 4',NULL,NULL,(SELECT id FROM gamification_engines WHERE name='Quiz Pro')),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Become Teacher Assistant',NULL,NULL,(SELECT id FROM gamification_engines WHERE name='Forum Pro')),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Teachers Evaluation in Assignment 4',NULL, NULL,(SELECT id FROM gamification_engines WHERE name='Group Pro')),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), '1min Extra Time in Quiz 5', NULL, NULL,(SELECT id FROM gamification_engines WHERE name='Wanted')),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Skip Quiz 5', NULL, NULL,(SELECT id FROM gamification_engines WHERE name='The King')),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Unlock Content Page 5', NULL, NULL,(SELECT id FROM gamification_engines WHERE name='The Adventurer'));

INSERT INTO redeemable_rewards (reward_id,rr_type_id, param_1, resource_id)
    VALUES  ((SELECT id FROM rewards WHERE name='Extra Attempt in Quiz 3'),            (SELECT id FROM rr_types WHERE name='Extra Attempts'),      '1',  (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=3 AND row=5)),
            ((SELECT id FROM rewards WHERE name='Extra Attempt in Quiz 4'),            (SELECT id FROM rr_types WHERE name='Extra Attempts'),      '1',  (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=4 AND row=5)),
            ((SELECT id FROM rewards WHERE name='Become Teacher Assistant'),           (SELECT id FROM rr_types WHERE name='Teacher Assistant'),   NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Platform'    )  AND module=0 AND row=0)),
            ((SELECT id FROM rewards WHERE name='Teachers Evaluation in Assignment 4'),(SELECT id FROM rr_types WHERE name='Teachers Evaluation'), NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Assignment'  )  AND module=2 AND row=3)),
            ((SELECT id FROM rewards WHERE name='1min Extra Time in Quiz 5'),          (SELECT id FROM rr_types WHERE name='Extra Time'),          '60', (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=5 AND row=3)),
            ((SELECT id FROM rewards WHERE name='Skip Quiz 5'),                        (SELECT id FROM rr_types WHERE name='Skip'),                NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=5 AND row=3)),
            ((SELECT id FROM rewards WHERE name='Unlock Content Page 5'),              (SELECT id FROM rr_types WHERE name='Unlock Resource'),     NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Content Page')  AND module=5 AND row=2));

INSERT INTO condition_types (name, enabled)
    VALUES  ('Resource Condition', TRUE),
            ('Group Condition', FALSE),
            ('Reward Condition', FALSE);

INSERT INTO conditions (description, condition_type_id, engine_id)
    VALUES  ('75% in Quiz 1',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='Quiz Pro')),
            ('75% in Quiz 2',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='Quiz Pro')),
            ('20 likes in Forums',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='Forum Pro')),
            ('50% approval in Group Task Assingment 2',(SELECT id FROM condition_types WHERE name='Group Condition'),(SELECT id FROM gamification_engines WHERE name='Group Pro')),
            ('Upload a profile picture',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='Wanted')),
            ('80% in Quiz 1',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='The King')),
            ('80% in Quiz 2',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='The King')),
            ('80% in Quiz 3',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='The King')),
            ('80% in Quiz 4',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='The King')),
            ('View all Content Pages',(SELECT id FROM condition_types WHERE name='Resource Condition'),(SELECT id FROM gamification_engines WHERE name='The Adventurer'));

INSERT INTO group_conditions (condition_id, student_percentage)
    VALUES  ((SELECT id FROM conditions WHERE description='50% approval in Group Task Assingment 2'),'50');

INSERT INTO resource_conditions (condition_id, resource_id, resource_op, action_op) VALUES
            ((SELECT id FROM conditions WHERE description='75% in Quiz 1'),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=1 AND row=5),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='75% in Quiz 2'),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=2 AND row=5),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='20 likes in Forums'),      (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Discussion Forum')  AND module=1 AND row=4),'any' ,'and'),
            ((SELECT id FROM conditions WHERE description='Upload a profile picture'),(SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Platform'        )  AND module=0 AND row=0),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 1'),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=1 AND row=5),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 2'),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=2 AND row=5),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 3'),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=3 AND row=5),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 4'),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=4 AND row=5),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='View all Content Pages'),  (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Content Page'    )  AND module=1 AND row=1),'all' ,'and');

INSERT INTO action_types (name)
    VALUES  ('Log in'),
            ('Log out'),
            ('Invite a friend'),
            ('Send message to student'),
            ('Send message to group'),
            ('Send message to teacher'),
            ('Upload profile picture'),
            ('Upload profile information'),
            ('Visit'),
            ('Mark as done'),
            ('Submit'),
            ('Edit'),
            ('Open'),
            ('Participate'),
            ('Entry'),
            ('Answer'),
            ('Give Like'),
            ('Give Like to an entry'),
            ('Give Like to an answer'),
            ('Receive Like'),
            ('Receive Like in an entry'),
            ('Receive Like in an answer'),
            ('Solve a question'),
            ('Mark as read'),
            ('Situate in a POI'),
            ('Interact with a resource'),
            ('Interact with other users'),
            ('Answers and clarifications'),
            ('Receive comments'),
            ('Comment with a minimum number of characters'),
            ('Fulfill the rubric'),
			      ('Google Spreadsheets: Insert new entry');

INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Log in'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Log out'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Invite a friend'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Send message to student'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Send message to group'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Send message to teacher'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Upload profile picture'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Upload profile information'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Mark as done'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Platform'),(SELECT id FROM action_types WHERE name='Submit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Content Page'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Content Page'),(SELECT id FROM action_types WHERE name='Mark as done'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Content Page'),(SELECT id FROM action_types WHERE name='Edit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Participate'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Entry'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Answer'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Give Like'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Give Like to an entry'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Give Like to an answer'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Receive Like'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Receive Like in an entry'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Receive Like in an answer'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Solve a question'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Discussion Forum'),(SELECT id FROM action_types WHERE name='Mark as read'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'),(SELECT id FROM action_types WHERE name='Mark as done'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Quiz'),(SELECT id FROM action_types WHERE name='Submit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'),(SELECT id FROM action_types WHERE name='Mark as done'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Assignment'),(SELECT id FROM action_types WHERE name='Submit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Mark as done'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Submit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Entry'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Answers and clarifications'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Give Like'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Receive comments'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='3DVW'),(SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Peer Review'),(SELECT id FROM action_types WHERE name='Fulfill the rubric'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Wiki'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Wiki'),(SELECT id FROM action_types WHERE name='Mark as done'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='Wiki'),(SELECT id FROM action_types WHERE name='Edit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='File'),(SELECT id FROM action_types WHERE name='Open'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='File'),(SELECT id FROM action_types WHERE name='Mark as read'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='External URL'),(SELECT id FROM action_types WHERE name='Open'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='External Tool'),(SELECT id FROM action_types WHERE name='Open'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='3DVW'),(SELECT id FROM action_types WHERE name='Visit'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='3DVW'),(SELECT id FROM action_types WHERE name='Interact with a resource'));
INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='3DVW'),(SELECT id FROM action_types WHERE name='Interact with other users'));

INSERT INTO resource_action (resource_type_id, action_type_id) VALUES ((SELECT id FROM resource_types WHERE name='External Tool'),(SELECT id FROM action_types WHERE name='Google Spreadsheets: Insert new entry'));

INSERT INTO actions (res_cond_id, type_id)
    VALUES  ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 1')),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 2')),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='20 likes in Forums')),(SELECT id FROM action_types WHERE name='Receive Like')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='Upload a profile picture')),(SELECT id FROM action_types WHERE name='Upload profile picture')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 1')),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 2')),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 3')),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 4')),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='View all Content Pages')),(SELECT id FROM action_types WHERE name='Visit'));

INSERT INTO rule_types (name, extra_parameters, input_type, tip)
    VALUES  ('Do the action itself',                                            0,'',''),
            ('Do the action several times',                                     1,'number',     'How many times?'),
            ('Do the action before a specific date',                            1,'date',       'Which date?'),
            ('Do the action several times before a specific date',              2,'text',       'How many times and which date?'),
            ('Do the action between a specific time frame',                     2,'date',       'Which period?'),
            ('Be one of the first participants doing the action',               1,'number',     'How many participants?'),
            ('Be one of the first participants doing the action several times', 2,'text',       'How many participants and which date?'),
            ('At least some group members have to perform the action',          1,'number',     'How many members?'),
            ('Get a validity score lower than X',                               1,'number',     'Which score?'),
            ('Get a reliability score lower than X',                            1,'number',     'Which score?'),
            ('Get an upper or equal score than X',                              2,'number',     'Which score? Rubric id? (empty if no rubric)');

INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log in'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Log out'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Invite a friend'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));

INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to student'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to group'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Send message to teacher'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));

INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile picture'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Upload profile information'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Visit'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as done'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Get a validity score lower than X'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Submit'),(SELECT id FROM rule_types WHERE name='Get a reliability score lower than X'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Edit'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Open'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Participate'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Entry'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answer'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an entry'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Give Like to an answer'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an entry'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Receive Like in an answer'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Solve a question'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Mark as read'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Situate in a POI'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with a resource'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Interact with other users'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));

INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answers and clarifications'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answers and clarifications'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answers and clarifications'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answers and clarifications'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answers and clarifications'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answers and clarifications'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Answers and clarifications'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));

INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Comment with a minimum number of characters'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));

INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Do the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Do the action before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Do the action several times before a specific date'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Do the action between a specific time frame'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Be one of the first participants doing the action several times'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='At least some group members have to perform the action'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Get a validity score lower than X'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Fulfill the rubric'),(SELECT id FROM rule_types WHERE name='Get a reliability score lower than X'));

INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Google Spreadsheets: Insert new entry'),(SELECT id FROM rule_types WHERE name='Do the action itself'));
INSERT INTO action_rule (action_type_id, rule_type_id) VALUES ((SELECT id FROM action_types WHERE name='Google Spreadsheets: Insert new entry'),(SELECT id FROM rule_types WHERE name='Do the action several times'));

INSERT INTO rules (action_id, type_id, param_1, param_2)
    VALUES  ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 1'))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'75',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 2'))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'75',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='20 likes in Forums'))),(SELECT id FROM rule_types WHERE name='Do the action several times'),'20',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='Upload a profile picture'))),(SELECT id FROM rule_types WHERE name='Do the action itself'),NULL,NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 1'))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 2'))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 3'))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 4'))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='View all Content Pages'))),(SELECT id FROM rule_types WHERE name='Do the action itself'),NULL,NULL);

INSERT INTO deploy_types (name, enabled)
    VALUES      ('Canvas',TRUE),
                ('Open edX', FALSE),
                ('Moodle', FALSE);
