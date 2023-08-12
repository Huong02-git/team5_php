CREATE TABLE users (
  user_id int(100) NOT NULL AUTO_INCREMENT,
  name varchar(20) NOT NULL,
  email varchar(50) NOT NULL,
  password varchar(50) NOT NULL,
  user_type varchar(100)  NOT NULL DEFAULT 'user',
  image varchar(100) NOT NULL,
   PRIMARY KEY(user_id)
);


CREATE TABLE `cart` (
  cart_id int(100) NOT NULL AUTO_INCREMENT,
  user_id int(100) NOT NULL,
  PRIMARY KEY(cart_id),
FOREIGN KEY (user_id) REFERENCES users (user_id)
);




CREATE TABLE categories (
  cate_id int(100) NOT NULL AUTO_INCREMENT,
  name varchar(100) NOT NULL,
  description varchar(500) NOT NULL,
  image varchar(100) NOT NULL,
   PRIMARY KEY(cate_id)
);


CREATE TABLE dishes (
  d_id int(100) NOT NULL AUTO_INCREMENT,
  title varchar(100) NOT NULL,
  cate_id int(100) NOT NULL,
  details varchar(500) NOT NULL,
  price int(10) NOT NULL,
  image varchar(100) NOT NULL,
   PRIMARY KEY(d_id),
   FOREIGN KEY (cate_id) REFERENCES categories (cate_id)
);
CREATE TABLE orders (
  o_id int(100) NOT NULL AUTO_INCREMENT,
  user_id int(100) NOT NULL,
  name varchar(20) NOT NULL,
  number varchar(10) NOT NULL,
  email varchar(50) NOT NULL,
  method varchar(50) NOT NULL,
  address varchar(500) NOT NULL,
  placed_on date NOT NULL DEFAULT current_timestamp(),
  payment_status varchar(20) NOT NULL DEFAULT 'pending',
   PRIMARY KEY(o_id),
   FOREIGN KEY (user_id) REFERENCES users (user_id)
);
CREATE TABLE Details_cart (
id int(100) NOT NULL AUTO_INCREMENT,
cart_id int(100),
d_id int(100),
totalDishes int(100) NOT NULL,
totalMoney int(100) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (cart_id) REFERENCES cart (cart_id),
    FOREIGN KEY (d_id) REFERENCES dishes (d_id)
);


CREATE TABLE Details_order (
id int(100) NOT NULL AUTO_INCREMENT,
o_id int(100),
d_id int(100),
quantity int(100) NOT NULL,
total_price int(100) NOT NULL,
    PRIMARY KEY (id),
    FOREIGN KEY (o_id) REFERENCES orders (o_id),
    FOREIGN KEY (d_id) REFERENCES dishes (d_id)
);
