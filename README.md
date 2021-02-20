# BackendForHousingPos
### CN
为具有上传功能的 [HousingPos](https://github.com/Bluefissure/HousingPos) 准备的后端，需mysql数据库。不需要/没有数据库的话也可以参考php注释将建立/写入数据库的代码删除，照样可以用。  
- `init.php`为初始化用文件。  
- `index.php`为工作环境用文件。  
- 请注意目录权限问题，无上级目录权限无法写入`map.json`和`log.txt`。
- 请注意完成初始化工作后将`init.php`删除，本文件对公网开放很不安全。

### EN
This is the back-end for [HousingPos](https://github.com/Bluefissure/HousingPos) with upload function. You need a MySQL database. If you don't need / don't have a database, you can also delete the code used to create / write the database according to the PHP comments.
- `init.php` PHP File for initialization.
- `index.php` PHP File for the work environment.
- Please pay attention to the problem of directory permission. `map.json` and `log.txt` cannot be written without the permission of superior directory.
- Please note that `init.php` should be deleted after initialization. This file is not safe for the public network.