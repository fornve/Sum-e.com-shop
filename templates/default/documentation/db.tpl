<div class="post">
    <h2 class="title">Tables</h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
        <ul>
            <li><a href="#admin">admin</a></li>
            <li><a href="#category">category</a></li>
            <li><a href="#category_description">category_description</a></li>
            <li><a href="#config">config</a></li>
            <li><a href="#geoip">geoip</a></li>
            <li><a href="#manufacturer">manufacturer</a></li>
            <li><a href="#order">order</a></li>
            <li><a href="#order_product">order_product</a></li>
            <li><a href="#order_status_history">order_status_history</a></li>
            <li><a href="#product">product</a></li>
            <li><a href="#product_category">product_category</a></li>
            <li><a href="#product_description">product_description</a></li>
            <li><a href="#product_image">product_image</a></li>
            <li><a href="#product_manufacturer">product_manufacturer</a></li>
            <li><a href="#shipping">shipping</a></li>
        </ul>
    </div>

    <h2 class="title">Table - admin</h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
    <a name="admin"></a>
    <pre>
+-----------------+---------------------+------+-----+---------------------+----------------+
| Field           | Type                | Null | Key | Default             | Extra          |
+-----------------+---------------------+------+-----+---------------------+----------------+
| id              | bigint(20) unsigned | NO   | PRI | NULL                | auto_increment |
| username        | varchar(40)         | YES  |     | NULL                |                |
| password        | varchar(40)         | YES  |     | NULL                |                |
| last_login_time | timestamp           | NO   |     | CURRENT_TIMESTAMP   |                |
| last_login_ip   | timestamp           | NO   |     | 0000-00-00 00:00:00 |                |
| vendor          | int(10)             | YES  |     | NULL                |                |
+-----------------+---------------------+------+-----+---------------------+----------------+
    </pre>
    </div>

    <h2 class="title">Table - category</h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
    <a name="category"></a>
<pre>
+------------+---------------------+------+-----+---------+----------------+
| Field      | Type                | Null | Key | Default | Extra          |
+------------+---------------------+------+-----+---------+----------------+
| id         | bigint(20) unsigned | NO   | PRI | NULL    | auto_increment |
| name       | varchar(255)        | YES  |     | NULL    |                |
| image      | varchar(255)        | YES  |     | NULL    |                |
| parent     | int(20)             | YES  |     | NULL    |                |
| sort_order | int(5)              | YES  |     | NULL    |                |
+------------+---------------------+------+-----+---------+----------------+
</pre>
    </div>


    <h2 class="title">Table - category_description</h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
    <a name="category_description"></a>
<pre>
+-------------+---------+------+-----+---------+-------+
| Field       | Type    | Null | Key | Default | Extra |
+-------------+---------+------+-----+---------+-------+
| category    | int(20) | NO   | PRI | NULL    |       |
| description | text    | YES  |     | NULL    |       |
+-------------+---------+------+-----+---------+-------+
</pre>
    </div>


    <h2 class="title">Table - </h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
    <a name=""></a>
<pre>
</pre>
    </div>


    <h2 class="title">Table - </h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
    <a name=""></a>
<pre>
</pre>
    </div>


    <h2 class="title">Table - </h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
    <a name=""></a>
<pre>
</pre>
    </div>


    <h2 class="title">Table - </h2>
    <p class="date">2009-03-27</p>
    <div class="entry">
    <a name=""></a>
<pre>
</pre>
    </div>


</div>

