CREATE TABLE IF NOT EXISTS books
(
    id           INT AUTO_INCREMENT PRIMARY KEY,
    title        VARCHAR(100) NOT NULL,
    img          VARCHAR(100),
    year         INT,
    pages        INT,
    isbn         VARCHAR(20),
    description  TEXT,
    viewsCounter INT DEFAULT 0,
    wantsCounter INT DEFAULT 0
);
