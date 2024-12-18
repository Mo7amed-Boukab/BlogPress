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
    views INT,
    likes INT,
    FOREIGN KEY (author_id) REFERENCES author(id),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
INSERT INTO articles (title, content, author_id) VALUES("10 Tips to Stay Productive All Day","Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores obcaecati provident quidem ratione recusandae illum, nostrum a cum accusamus impedit quaerat ab amet possimus natus perspiciatis animi in nobis eius veritatis harum! Nemo aliquam, quidem fugiat deleniti mollitia eius dolorum sequi natus soluta possimus! Deserunt cum facere minima in sed.",14);
INSERT INTO articles (title, content, author_id) VALUES("5 Habits of Highly Successful People","Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores obcaecati provident quidem ratione recusandae illum, nostrum a cum accusamus impedit quaerat ab amet possimus natus perspiciatis animi in nobis eius veritatis harum! Nemo aliquam, quidem fugiat deleniti mollitia eius dolorum sequi natus soluta possimus! Deserunt cum facere minima in sed.",14);
INSERT INTO articles (title, content, author_id) VALUES("Save Time, Achieve Your Goals!","Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolores obcaecati provident quidem ratione recusandae illum, nostrum a cum accusamus impedit quaerat ab amet possimus natus perspiciatis animi in nobis eius veritatis harum! Nemo aliquam, quidem fugiat deleniti mollitia eius dolorum sequi natus soluta possimus! Deserunt cum facere minima in sed.",14);

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
