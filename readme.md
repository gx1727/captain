#安静的超自己的轮子

#CAPTAIN 
php框架  
终于，还是没忍着，开始超自己的轮了  

目录分隔符:DIRECTORY_SEPARATOR  
目录结构
```
index.php
js
css
fonts
img
uploads
logs
core
    |-libraries
    |-helpers
app
    |-config.php 配制文件
    |-routes.php 路由文件
```


## url设计
1. http://域名:端口/c/m?k1=v1&k2=m2  
2. http://域名:端口/二级目录/index.php?c=c&m=m&k1=v1&k2=m2

在网站根目录即为项目根目录时， 可以使用重写的方式去掉 index.php ,其它情况下，都使用第二种方式的完整url


基本上搞定了路由了