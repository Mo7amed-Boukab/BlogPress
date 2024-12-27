CREATE DATABASE blogpress;
USE blogpress;

CREATE TABLE author (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE visitors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL
);

CREATE TABLE articles (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    author_id INT NOT NULL,
    views INT DEFAULT 0,
    likes INT DEFAULT 0,
    FOREIGN KEY (author_id) REFERENCES author(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

ALTER TABLE articles 
ADD COLUMN image VARCHAR(255) AFTER content;

CREATE TABLE comments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    content TEXT NOT NULL,
    article_id INT NOT NULL,
    visitor_id INT NOT NULL,
    FOREIGN KEY (article_id) REFERENCES articles(id),
    FOREIGN KEY (visitor_id) REFERENCES visitors(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE likes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    visitor_id INT NOT NULL,
    article_id INT NOT NULL,
    FOREIGN KEY (visitor_id) REFERENCES visitors(id),
    FOREIGN KEY (article_id) REFERENCES articles(id)
);
-- SELECT v.username, c.content, c.created_at   FROM comments AS c 
--               JOIN visitors AS v ON c.visitor_id = v.id 
--               WHERE c.article_id = ?
--               ORDER BY c.created_at DESC;

-- SELECT * FROM articles as art INNER JOIN author as auth on art.author_id = auth.id;
-- SELECT * FROM likes AS lk INNER JOIN artickes AS art ON art.id = lk.article_id;
-- SELECT * FROM comments AS com INNER JOIN articles AS art ON com.article_id = art.id;
SELECT art.id, art.title, art.views, art.likes, COUNT(com.id) AS total_comments    
        FROM 
            articles AS art
        LEFT JOIN 
            comments AS com ON art.id = com.article_id
        GROUP BY 
            art.id;
-- SELECT auth.username, COUNT(*) AS total_articles 
-- FROM articles AS ar INNER JOIN author AS auth ON ar.author_id = auth.id
-- GROUP BY auth.username;

-- SELECT ar.title, ar.views FROM articles AS ar
-- ORDER BY ar.views DESC;

-- SELECT ar.views, ar.likes FROM articles AS ar
-- ORDER BY ar.views DESC, ar.likes DESC;

SELECT v.username, c.content, c.created_at 
                            FROM comments AS c 
                            INNER JOIN visitors AS v ON c.visitor_id = v.id 
                            WHERE c.article_id = 2
                            ORDER BY c.created_at DESC;

SELECT id, title, image, likes FROM articles ORDER BY likes DESC LIMIT 3;
SELECT a.id, a.title, a.content, a.image, a.likes, a.views, a.created_at, au.username 
            FROM articles AS a 
            INNER JOIN author AS au ON a.author_id = au.id;
    
SELECT v.username, c.content, c.created_at 
            FROM comments AS c 
            INNER JOIN visitors AS v ON c.visitor_id = v.id 
            WHERE c.article_id =3
            ORDER BY c.created_at DESC    