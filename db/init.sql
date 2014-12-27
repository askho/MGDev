CREATE TABLE Album (
	albumID INT AUTO_INCREMENT NOT NULL
	,albumName VARCHAR(250) NOT NULL
	,albumDate DATE NOT NULL
	,albumTag VARCHAR(1000)
	,privateLink VARCHAR(255)
	,CONSTRAINT pk_AlbumID PRIMARY KEY (albumID)
	,CONSTRAINT UQ_albumName UNIQUE (albumName)
);

CREATE TABLE Photo (
	photoID INT AUTO_INCREMENT NOT NULL
	,photoName VARCHAR(250) NULL
	,photoDate DATE NOT NULL
	,dateTaken date NULL
	,aperture VARCHAR(10) NULL
	,ISO VARCHAR(15) NULL
	,focalLength INT NULL
	,camera VARCHAR(50) NULL
	,description BLOB NULL
	,location VARCHAR(100) NULL
	,originalFileName VARCHAR(100) NULL
	,CONSTRAINT pk_photoID PRIMARY KEY (photoID)
);

CREATE TABLE PhotoAlbum (
	photoID INT NOT NULL
	,albumID INT NOT NULL
	,CONSTRAINT PK_photoID PRIMARY KEY (photoID)
	,CONSTRAINT FK_photoID FOREIGN KEY (photoID) REFERENCES photo(photoID) ON DELETE CASCADE
	,CONSTRAINT FK_albumID FOREIGN KEY (albumID) REFERENCES album(albumID) ON DELETE CASCADE
);


CREATE TABLE Admin (
    username    VARCHAR(128)     NOT NULL
    ,hash       VARCHAR(128)     NOT NULL
);

CREATE TABLE Category (
	categoryID INT AUTO_INCREMENT NOT NULL
	,categoryName VARCHAR(250) NOT NULL
	,CONSTRAINT PK_categoryID PRIMARY KEY(categoryID)
);

CREATE TABLE AlbumCategory (
	albumCategoryID INT AUTO_INCREMENT NOT NULL 
	,categoryID INT NOT NULL
	,albumID INT NOT NULL
	,CONSTRAINT PK_albumCategoryID PRIMARY KEY (albumCategoryID )
	,CONSTRAINT FK_cateogryID FOREIGN KEY (categoryID) REFERENCES Category(categoryID) ON DELETE CASCADE
	,CONSTRAINT FK_albumID_AlbumCategory FOREIGN KEY  (albumID) REFERENCES Album(albumID) ON DELETE CASCADE
);