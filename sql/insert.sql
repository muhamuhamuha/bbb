-- customer table
INSERT INTO CUSTOMER VALUES ('JD123','12345','John','Doe','456 washtenaw ave','Ann Arbor','Michigan','48198','VISA','5555555555555555','0122');

INSERT INTO CUSTOMER VALUES ('BM734','54321','Brie','Macbeth','981 packard st','Ann Arbor','Michigan','48012','MASTER','4444444444444444','1203');  

INSERT INTO CUSTOMER VALUES ('MJ463','71231','Miranda','Johnson','612 Orchard Rd','Ypsilanti','Michigan','48365','VISA','0333333333333333','1133');

-- book table
INSERT INTO BOOK VALUES (5555555333444, 'Anna Karenina', 'Leo Tolstoy', 'The Russian Messenger', 20.00, 'World Literature', 0);

INSERT INTO BOOK VALUES (1234567891234, 'Lord of the Rings', 'J.R.R. Tolkien', 'Allen & Unwin', 50.00, 'Fantasy', 7);

INSERT INTO BOOK VALUES (1111111111111, 'Moby Dick', 'Herman Melville', 'Richard Bentley', 63.00, 'American Literature', 4);

INSERT INTO BOOK VALUES (9876543210123, 'Harry Potter', 'J.K. Rowling', 'Bloomsbury', 43.00, 'Fantasy', 10);

INSERT INTO BOOK VALUES (3213213453568, 'Harry Potter and the Sorcerers Stone', 'J.K. Rowling', 'Bloomsbury', 43.00, 'Fantasy', 10);

INSERT INTO BOOK VALUES (4494413453568, 'Harry Potter and the Chamber of Secrets', 'J.K. Rowling', 'Bloomsbury', 43.00, 'Fantasy', 10);

INSERT INTO BOOK VALUES (3213111111168, 'Harry Potter and the Prisoner of Azkaban', 'J.K. Rowling', 'Bloomsbury', 43.00, 'Fantasy', 10);

INSERT INTO BOOK VALUES (4444444444444, 'Harry Potter and the Goblet of Fire', 'J.K. Rowling', 'Bloomsbury', 43.00, 'Fantasy', 10);

INSERT INTO BOOK VALUES (9272523210123, 'Speaker for the Dead', 'Orson Scott Card', 'Tor Books', 10.00, 'Science Fiction', 10);

INSERT INTO BOOK VALUES (2222222222222, 'Zen and the Art of Motorcycle Maintenance: An Inquiry Into VaLues', 'Robert M. Pirsig', 'William Morrow', 43.00, 'Adventure', 10);

INSERT INTO BOOK VALUES (9999999999999, 'The Dunwich Horror', 'H.P. Lovecraft', 'Weird Tales', 5.99, 'Horror', 1);

-- review table
INSERT INTO REVIEW ("Description", ISBN) VALUES ('fantastic book would recommend!',1111111111111);

INSERT INTO REVIEW ("Description", ISBN) VALUES ('something for nerds, it was ok',9876543210123);

INSERT INTO REVIEW ("Description", ISBN) VALUES ('da best',9876543210123);

INSERT INTO REVIEW ("Description", ISBN) VALUES ('oh my god when is the sequel coming out i cannot wait',9876543210123);

INSERT INTO REVIEW ("Description", ISBN) VALUES ('the best of the best',1234567891234);

INSERT INTO ADMINISTRATOR VALUES ('AD001','00001');
