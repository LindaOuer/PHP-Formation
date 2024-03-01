# PHP CRUD + PHP Ajax CRUD Example

## Clone the starter repository
1. Open the htdocs folder
2. Clone the repository 

```javascript
git clone https://github.com/LindaOuer/PHP-Ajax-starter.git
```
3. Cd to the new folder

## Create the database + Student Table

Table structure for table `Student`

```SQL
CREATE TABLE `Student` (
  `Id` int(11) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `Image` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
```
