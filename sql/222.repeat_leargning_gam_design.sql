BEGIN;
INSERT INTO learning_designs (course_name, modules, rows, creator_id)
    VALUES  ('Innovative Collaborative Learning with ICT', '5', '5', (SELECT id FROM users WHERE email='jtelss18_01@gsic.uva.es'));

INSERT INTO gamification_designs (created_at, name, creator_id, learning_id)
    VALUES  ('2018-01-04','Gam_v1',(SELECT id FROM users WHERE email='jtelss18_01@gsic.uva.es'), (SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1)),
            ('2018-01-07','Gam_v2',(SELECT id FROM users WHERE email='jtelss18_01@gsic.uva.es'),(SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1));

INSERT INTO resources (learning_id, module, row, type_id)
    VALUES  ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'0','0',(SELECT id FROM resource_types WHERE name='Platform')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'1','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'1','2',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'1','3',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'1','4',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'1','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'2','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'2','2',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'2','3',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'2','4',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'2','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'3','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'3','2',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'3','3',(SELECT id FROM resource_types WHERE name='Peer Review')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'3','4',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'3','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'4','1',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'4','2',(SELECT id FROM resource_types WHERE name='Assignment')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'4','3',(SELECT id FROM resource_types WHERE name='Discussion Forum')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'4','4',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'4','5',(SELECT id FROM resource_types WHERE name='Quiz')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'5','1',(SELECT id FROM resource_types WHERE name='Peer Review')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'5','2',(SELECT id FROM resource_types WHERE name='Content Page')),
            ((SELECT id FROM learning_designs WHERE course_name='Innovative Collaborative Learning with ICT' ORDER BY id DESC LIMIT 1),'5','3',(SELECT id FROM resource_types WHERE name='Quiz'));

INSERT INTO gamification_engines (gdesign_id, name, description, condition_op)
    VALUES  ((SELECT id FROM gamification_designs WHERE name='Gam_v1' ORDER BY id DESC LIMIT 1), 'Quiz Pro', 'Earn an extra attempt in Quiz 3 and Quiz 4 by scoring at least 75% in Quiz 1 and Quiz 2!','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1' ORDER BY id DESC LIMIT 1), 'Forum Pro', 'Become Teacher Assistant of course forums by receiving more than 20 likes in the forums','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1' ORDER BY id DESC LIMIT 1), 'Group Pro', 'Teachers will evaluate your Assignment 4 by receiving the approval of 50% of your team based on your work in Group Task Assingment 2','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1' ORDER BY id DESC LIMIT 1), 'Wanted', 'Earn 1 extra minute in Quiz 5 (Final) by uploading a profile picture','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1' ORDER BY id DESC LIMIT 1), 'The King', 'Skip the Quiz 5 (Final) by scoring at least 75% in Quiz 1, Quiz 2, Quiz 3, Quiz 4','and'),
            ((SELECT id FROM gamification_designs WHERE name='Gam_v1' ORDER BY id DESC LIMIT 1), 'The Adventurer', 'Unlock extra course content in Module 5 by wisiting all content pages of the course','and');

INSERT INTO rewards (reward_type_id, name, url_image, quantity, engine_id)
    VALUES  ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Extra Attempt in Quiz 3',             NULL, NULL, (SELECT id FROM gamification_engines WHERE name='Quiz Pro' ORDER BY id DESC LIMIT 1)),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Extra Attempt in Quiz 4',             NULL, NULL, (SELECT id FROM gamification_engines WHERE name='Quiz Pro' ORDER BY id DESC LIMIT 1)),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Become Teacher Assistant',            NULL, NULL, (SELECT id FROM gamification_engines WHERE name='Forum Pro' ORDER BY id DESC LIMIT 1)),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Teachers Evaluation in Assignment 4', NULL, NULL, (SELECT id FROM gamification_engines WHERE name='Group Pro' ORDER BY id DESC LIMIT 1)),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), '1min Extra Time in Quiz 5',           NULL, NULL, (SELECT id FROM gamification_engines WHERE name='Wanted' ORDER BY id DESC LIMIT 1)),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Skip Quiz 5',                         NULL, NULL, (SELECT id FROM gamification_engines WHERE name='The King' ORDER BY id DESC LIMIT 1)),
            ((SELECT id FROM reward_types WHERE name='Redeemable Rewards'), 'Unlock Content Page 5',               NULL, NULL, (SELECT id FROM gamification_engines WHERE name='The Adventurer' ORDER BY id DESC LIMIT 1));

INSERT INTO redeemable_rewards (reward_id,rr_type_id, param_1, resource_id)
    VALUES  ((SELECT id FROM rewards WHERE name='Extra Attempt in Quiz 3' ORDER BY id DESC LIMIT 1),            (SELECT id FROM rr_types WHERE name='Extra Attempts'),      '1',  (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=3 AND row=5 ORDER BY id DESC LIMIT 1 )),
            ((SELECT id FROM rewards WHERE name='Extra Attempt in Quiz 4' ORDER BY id DESC LIMIT 1),            (SELECT id FROM rr_types WHERE name='Extra Attempts'),      '1',  (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=4 AND row=5 ORDER BY id DESC LIMIT 1 )),
            ((SELECT id FROM rewards WHERE name='Become Teacher Assistant' ORDER BY id DESC LIMIT 1),           (SELECT id FROM rr_types WHERE name='Teacher Assistant'),   NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Platform'    )  AND module=0 AND row=0 ORDER BY id DESC LIMIT 1 )),
            ((SELECT id FROM rewards WHERE name='Teachers Evaluation in Assignment 4' ORDER BY id DESC LIMIT 1),(SELECT id FROM rr_types WHERE name='Teachers Evaluation'), NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Assignment'  )  AND module=2 AND row=3 ORDER BY id DESC LIMIT 1 )),
            ((SELECT id FROM rewards WHERE name='1min Extra Time in Quiz 5' ORDER BY id DESC LIMIT 1),          (SELECT id FROM rr_types WHERE name='Extra Time'),          '60', (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=5 AND row=3 ORDER BY id DESC LIMIT 1 )),
            ((SELECT id FROM rewards WHERE name='Skip Quiz 5' ORDER BY id DESC LIMIT 1),                        (SELECT id FROM rr_types WHERE name='Skip'),                NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'        )  AND module=5 AND row=3 ORDER BY id DESC LIMIT 1 )),
            ((SELECT id FROM rewards WHERE name='Unlock Content Page 5' ORDER BY id DESC LIMIT 1),              (SELECT id FROM rr_types WHERE name='Unlock Resource'),     NULL, (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Content Page')  AND module=5 AND row=2 ORDER BY id DESC LIMIT 1 ));

INSERT INTO conditions (description, condition_type_id, engine_id)
    VALUES  ('75% in Quiz 1',(SELECT id FROM condition_types WHERE name='Resource Condition'),                        (SELECT id FROM gamification_engines WHERE name='Quiz Pro'       ORDER BY id DESC LIMIT 1)),
            ('75% in Quiz 2',(SELECT id FROM condition_types WHERE name='Resource Condition'),                        (SELECT id FROM gamification_engines WHERE name='Quiz Pro'       ORDER BY id DESC LIMIT 1)),
            ('20 likes in Forums',(SELECT id FROM condition_types WHERE name='Resource Condition'),                   (SELECT id FROM gamification_engines WHERE name='Forum Pro'      ORDER BY id DESC LIMIT 1)),
            ('50% approval in Group Task Assingment 2',(SELECT id FROM condition_types WHERE name='Group Condition'), (SELECT id FROM gamification_engines WHERE name='Group Pro'      ORDER BY id DESC LIMIT 1)),
            ('Upload a profile picture',(SELECT id FROM condition_types WHERE name='Resource Condition'),             (SELECT id FROM gamification_engines WHERE name='Wanted'         ORDER BY id DESC LIMIT 1)),
            ('80% in Quiz 1',(SELECT id FROM condition_types WHERE name='Resource Condition'),                        (SELECT id FROM gamification_engines WHERE name='The King'       ORDER BY id DESC LIMIT 1)),
            ('80% in Quiz 2',(SELECT id FROM condition_types WHERE name='Resource Condition'),                        (SELECT id FROM gamification_engines WHERE name='The King'       ORDER BY id DESC LIMIT 1)),
            ('80% in Quiz 3',(SELECT id FROM condition_types WHERE name='Resource Condition'),                        (SELECT id FROM gamification_engines WHERE name='The King'       ORDER BY id DESC LIMIT 1)),
            ('80% in Quiz 4',(SELECT id FROM condition_types WHERE name='Resource Condition'),                        (SELECT id FROM gamification_engines WHERE name='The King'       ORDER BY id DESC LIMIT 1)),
            ('View all Content Pages',(SELECT id FROM condition_types WHERE name='Resource Condition'),               (SELECT id FROM gamification_engines WHERE name='The Adventurer' ORDER BY id DESC LIMIT 1));

INSERT INTO group_conditions (condition_id, student_percentage)
    VALUES  ((SELECT id FROM conditions WHERE description='50% approval in Group Task Assingment 2' ORDER BY id DESC LIMIT 1),'50');

INSERT INTO resource_conditions (condition_id, resource_id, resource_op, action_op) VALUES
            ((SELECT id FROM conditions WHERE description='75% in Quiz 1' ORDER BY id DESC LIMIT 1),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=1 AND row=5 ORDER BY id DESC LIMIT 1),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='75% in Quiz 2' ORDER BY id DESC LIMIT 1),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=2 AND row=5 ORDER BY id DESC LIMIT 1),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='20 likes in Forums' ORDER BY id DESC LIMIT 1),      (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Discussion Forum')  AND module=1 AND row=4 ORDER BY id DESC LIMIT 1),'any' ,'and'),
            ((SELECT id FROM conditions WHERE description='Upload a profile picture' ORDER BY id DESC LIMIT 1),(SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Platform'        )  AND module=0 AND row=0 ORDER BY id DESC LIMIT 1),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 1' ORDER BY id DESC LIMIT 1),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=1 AND row=5 ORDER BY id DESC LIMIT 1),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 2' ORDER BY id DESC LIMIT 1),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=2 AND row=5 ORDER BY id DESC LIMIT 1),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 3' ORDER BY id DESC LIMIT 1),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=3 AND row=5 ORDER BY id DESC LIMIT 1),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='80% in Quiz 4' ORDER BY id DESC LIMIT 1),           (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Quiz'            )  AND module=4 AND row=5 ORDER BY id DESC LIMIT 1),NULL ,'and'),
            ((SELECT id FROM conditions WHERE description='View all Content Pages' ORDER BY id DESC LIMIT 1),  (SELECT id FROM resources WHERE type_id=(SELECT id FROM resource_types WHERE name='Content Page'    )  AND module=1 AND row=1 ORDER BY id DESC LIMIT 1),'all' ,'and');


INSERT INTO actions (res_cond_id, type_id)
    VALUES  ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 1'            ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 2'            ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='20 likes in Forums'       ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Receive Like')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='Upload a profile picture' ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Upload profile picture')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 1'            ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 2'            ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 3'            ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 4'            ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Submit')),
            ((SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='View all Content Pages'   ORDER BY id DESC LIMIT 1)),(SELECT id FROM action_types WHERE name='Visit'));

INSERT INTO rules (action_id, type_id, param_1, param_2)
    VALUES  ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 1'            ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'75',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='75% in Quiz 2'            ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'75',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='20 likes in Forums'       ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Do the action several times'),'20',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='Upload a profile picture' ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Do the action itself'),NULL,NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 1'            ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 2'            ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 3'            ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='80% in Quiz 4'            ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Get an upper or equal score than X'),'80',NULL),
            ((SELECT id FROM actions WHERE res_cond_id=(SELECT id FROM resource_conditions WHERE condition_id=(SELECT id FROM conditions WHERE description='View all Content Pages'   ORDER BY id DESC LIMIT 1))),(SELECT id FROM rule_types WHERE name='Do the action itself'),NULL,NULL);
END;
