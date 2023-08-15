CREATE TABLE users (
  id int(100) NOT NULL AUTO_INCREMENT,
  name varchar(20) NOT NULL,
  email varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  user_type varchar(100)  NOT NULL DEFAULT 'user',
  image varchar(100) NOT NULL,
   PRIMARY KEY(id)
);

CREATE TABLE `cart` (
  id int(100) NOT NULL AUTO_INCREMENT,
  user_id int(100) NOT NULL,
  title varchar(100) NOT NULL,
  image varchar(100) NOT NULL, 
  PRIMARY KEY(id),
FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE
);


CREATE TABLE categories (
  cates_id int(100) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  description varchar(500) NOT NULL,
  image1 varchar(100) NOT NULL,
   PRIMARY KEY(cates_id)
);

CREATE TABLE dishes (
  id int(100) NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL,
  cate_id int(100) NOT NULL,
  details varchar(500) NOT NULL,
  price int(10) NOT NULL,
  image varchar(100) NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY (cate_id) REFERENCES categories (cates_id) ON DELETE CASCADE
);
CREATE TABLE orders (
  id int(100) NOT NULL AUTO_INCREMENT,
  user_id int(100) NOT NULL,
  name varchar(20) NOT NULL,
  number varchar(10) NOT NULL,
  email varchar(50) NOT NULL,
  method varchar(50) NOT NULL,
  address varchar(500) NOT NULL,
  total_price int(100) NOT NULL,
  placed_on date NOT NULL DEFAULT current_timestamp(),
  payment_status varchar(20) NOT NULL DEFAULT 'pending',
   PRIMARY KEY(id),
   FOREIGN KEY (user_id) REFERENCES users (id)ON DELETE CASCADE
);
CREATE TABLE details_cart (
cid int(100),
d_id int(100), 
totalDishes int(100) NOT NULL,
price int(100) NOT NULL,
    PRIMARY KEY (cid,d_id),
    FOREIGN KEY (cid) REFERENCES cart (id) ON DELETE CASCADE,
    FOREIGN KEY (d_id) REFERENCES dishes (id)ON DELETE CASCADE
);

CREATE TABLE details_order (
o_id int(100),
d_id int(100), 
quantity int(100) NOT NULL,

    PRIMARY KEY (o_id, d_id),
    FOREIGN KEY (o_id) REFERENCES orders (id) ON DELETE CASCADE,
    FOREIGN KEY (d_id) REFERENCES dishes (id)ON DELETE CASCADE
);