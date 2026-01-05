CREATE TABLE IF NOT EXISTS Szerepkor (
    szerepkor_id INT AUTO_INCREMENT PRIMARY KEY,
    megnevezes VARCHAR(255)
);

CREATE TABLE IF NOT EXISTS Felhasznalo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255),
    jelszo VARCHAR(255),
    neve VARCHAR(255),
    neme VARCHAR(50),
    eletkor INT,
    iskola VARCHAR(255),
    mikor_keszult DATETIME,
    szerepkor_id INT,
    FOREIGN KEY (szerepkor_id) REFERENCES Szerepkor(szerepkor_id)
);

CREATE TABLE IF NOT EXISTS Uzenetek (
    uzenet_id INT AUTO_INCREMENT PRIMARY KEY,
    tartalom TEXT,
    mikor_keszult DATETIME,
    id INT,
    FOREIGN KEY (id) REFERENCES Felhasznalo(id)
);

CREATE TABLE IF NOT EXISTS Velemeny (
    velemeny_id INT AUTO_INCREMENT PRIMARY KEY,
    tartalom TEXT,
    mikor_keszult DATETIME,
    id INT,
    FOREIGN KEY (id) REFERENCES Felhasznalo(id)
);

CREATE TABLE IF NOT EXISTS Reakciok (
    reakcio_id INT AUTO_INCREMENT PRIMARY KEY,
    emoji VARCHAR(50),
    mikor_keszult DATE
);

CREATE TABLE IF NOT EXISTS Reagal (
    id INT,
    reakcio_id INT,
    PRIMARY KEY (id, reakcio_id),
    FOREIGN KEY (id) REFERENCES Felhasznalo(id),
    FOREIGN KEY (reakcio_id) REFERENCES Reakciok(reakcio_id)
);

CREATE TABLE IF NOT EXISTS Reagalas_Velemenyre (
    reakcio_id INT,
    velemeny_id INT,
    id INT,
    PRIMARY KEY (reakcio_id, velemeny_id),
    FOREIGN KEY (id) REFERENCES Felhasznalo(id),
    FOREIGN KEY (reakcio_id) REFERENCES Reakciok(reakcio_id),
    FOREIGN KEY (velemeny_id) REFERENCES Velemeny(velemeny_id)
);

CREATE TABLE IF NOT EXISTS Reagalas_Uzenetre (
    reakcio_id INT,
    uzenet_id INT,
    id INT,
    PRIMARY KEY (reakcio_id, uzenet_id),
    FOREIGN KEY (id) REFERENCES Felhasznalo(id),
    FOREIGN KEY (reakcio_id) REFERENCES Reakciok(reakcio_id),
    FOREIGN KEY (uzenet_id) REFERENCES Uzenetek(uzenet_id)
);

INSERT INTO Szerepkor (szerepkor_id , megnevezes) VALUES
(1, 'admin'),
(2, 'felhasznalo');




