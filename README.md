# Newsletter
A HTML, CSS, JavaScript, PHP and Mysql project, that represents an example of subscription to a newsletter. The emails are validated in JavaScript and PHP before submittion. All submitted emails can be seen on a separate page, where the results can be sorted and filtered. It is also possible to delete emails from database and search for emails.

## To run the project locally

1. You should have PHP and Mysql installed on your computer. If you don't have you can:
install XAMPP - cross platform web server solution package, meaning it can run in any environment, be it Windows, Linux or MAC OS.
XAMPP and other similar packages mimics (on the local server) the components used in the web server deployments of a live server. That makes
local to live serer transition development really easy.

Once XAMPP installed, you have PHP, MariaDB - which has been replaced for Mysql and APACHE. All you have to do to get your project up running is:

- go to xampp folder and have the project inside htdocs folder
- configure httpd-vhost file which lives in the apache/extra folder, and make sure Document root has the correct path pointing to your project
- if you want a custom domain as opposed to visit the website with the local api address, you have to make an entry to the windows hosts file (do that with notepad as administrator)
ex: 127.0.0.1 mycompany.test. Also make sure that you especify that in the vhosts file in the appache in the ServerName

*configure a vhosts in windows 10   https://www.cloudways.com/blog/configure-virtual-host-on-windows-10-for-wordpress/*

*if you want to run the php scripts from the command prompt or terminal, be sure to set the .exe file in the environment varible PATH.*

2. In the xampp control panel click "start" for Apache and Mysql. Go to Mysql admin and create a new database called "magebit_test". Inside this database create a table called "emails". Create two columns inside the table: first column with a name "id" and type iNT, set it to autoincrement, second column with the name "email" and type text.

3. Open the project in Visual Studio code or other text editor. You should have SASS Compiler extension installed. If you don't have - go to extensions, search for SASS compiler and install this extension, restart Visual Studio Code. After that you should see the Watch SASS button in the bottom bar, click it. It should generate the styles.css file in the "styles" folder.

After doing the above type the address of the project in the browser adderess bar as defined in the vhost file ServerName. To view submitted emails page add "/result.php" to the address.

*If you can't connect to the database, go to C:\xampp\phpMyAdmin\config.inc.php to find out the correct username and password and put them in index php line 139 new PDO statement and in result.php line 26 new PDO statement.*
