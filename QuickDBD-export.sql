-- Exported from QuickDBD: https://www.quickdatabasediagrams.com/
    -- NOTE! If you have used non-SQL datatypes in your design, you will have to change these here.
    -- Modify this code to update the DB schema diagram.
    -- To reset the sample schema, replace everything with
    -- two dots ('..' - without quotes).
CREATE TABLE utilisateur(
    id_utilisateur INT NOT NULL,
    nom VARCHAR(50) NOT NULL,
    prenom VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL,
    mdp VARCHAR(50) NOT NULL,
    PRIMARY KEY(id_utilisateur),
    CONSTRAINT uc_utilisateur_email UNIQUE(email)
); CREATE TABLE Postit(
    id_postit INT NOT NULL,
    id_owner INT NOT NULL,
    titre VARCHAR(50) NOT NULL,
    contenue VARCHAR(50) NOT NULL,
    PRIMARY KEY(id_postit)
); CREATE TABLE Partage(
    id_postit INT NOT NULL,
    id_user INT NOT NULL,
    PRIMARY KEY(id_postit, id_user)
); ALTER TABLE
    Postit ADD CONSTRAINT fk_Postit_id_owner FOREIGN KEY(id_owner) REFERENCES utilisateur(id_utilisateur);
ALTER TABLE
    Partage ADD CONSTRAINT fk_Partage_id_postit FOREIGN KEY(id_postit) REFERENCES Postit(id_postit);
ALTER TABLE
    Partage ADD CONSTRAINT fk_Partage_id_user FOREIGN KEY(id_user) REFERENCES utilisateur(id_utilisateur);