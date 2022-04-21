INSERT INTO users (name, lastname, email, password, inst_id) VALUES ('Justin', 'Bibier', 'justin@bieber.com', '$2y$10$dPXagNK0dr.DYmyaKO5xFOZnU0dRMmMerZHRactdLHU0BtCFAx6ve', 1);

INSERT INTO learning_design_access (user_id, learning_id) VALUES ( (SELECT id FROM users WHERE email = 'justin@bieber.com'), 1);
INSERT INTO learning_design_access (user_id, learning_id) VALUES ( (SELECT id FROM users WHERE email = 'justin@bieber.com'), 2);

INSERT INTO gamification_design_access (user_id, gamification_id) VALUES ( (SELECT id FROM users WHERE email = 'justin@bieber.com'), 1);
INSERT INTO gamification_design_access (user_id, gamification_id) VALUES ( (SELECT id FROM users WHERE email = 'justin@bieber.com'), 2);

