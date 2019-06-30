<html>
<head>
    <link rel="stylesheet" type="text/css" href="/akademijai/public/css/program.css">
    <title>Countries</title>
</head>
<body>
<div>
    <div>
        <label>Filter by date:</label>
        <form action="/akademijai/public/HomeController/filterDataByDate" method="post">
            <input class="inputForAddOrEdit" type="date" name="date" required="required">
            <button type="submit">Filter</button>
        </form>
        <div class="right">
            <div>
                <a class="buttonLink2" href="/akademijai/public/HomeController/clear">Back to Countries List</a>
            </div>
        </div>
    </div>
    <div>
        <label>Search countries:</label>
        <form action="/akademijai/public/HomeController/searchData" method="post">
            <input class="inputForAddOrEdit" type="text" name="text" required="required">
            <button type="submit">Search</button>
        </form>
    </div>
    <table class="table">
        <tr>
            <th>
                Country name
                    <img class="cursor" src="/akademijai/public/img/sort_asc_icon.png" onclick="window.location.href='/akademijai/public/HomeController/index?sort=<?php echo 'asc' ?>'">
                    <img class="cursor" src="/akademijai/public/img/sort_desc_icon.png" onclick="window.location.href='/akademijai/public/HomeController/index?sort=<?php echo 'desc' ?>'">
                    <img class="cursor" src="/akademijai/public/img/cancel_icon.png" onclick="window.location.href='/akademijai/public/HomeController/abortSort'">
            </th>
            <th>Area(sq.km)</th>
            <th>People count</th>
            <th>Phone code</th>
            <th>Choices</th>
        </tr>
        <?php foreach ($data['result'] as $value):;?>
            <tr>
                <td><a class="countriesColor" href="/akademijai/public/HomeController/redirectToCities?id=<?php echo $value['id'] ?>&country=<?php echo $value['name'] ?>"><?php echo $value['name'];?></a></td>
                <td><?php echo $value['area'];?></td>
                <td><?php echo $value['inhabitants_count'];?></td>
                <td><?php echo "+"; echo $value['phone_code'];?></td>
                <td><a class="buttonLink1" href="/akademijai/public/HomeController/getCountryById?id=<?php echo $value['id'] ?>">Edit</a>
                    <a class="buttonDanger" href="/akademijai/public/HomeController/deleteCountryAndCities?id=<?php echo $value['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <div class="center">
        <div class="paging">
            <a href="#">&laquo;</a>
            <?php for ($i = 1; $i <= $data['pagesCount']; $i++)
                if ($i == $_SESSION['currentPageNumber']) {
                    echo "<a class='active' href=\"/akademijai/public/HomeController/index?param=$i\">$i &nbsp;</a>";
                }
                else {
                    echo "<a href=\"/akademijai/public/HomeController/index?param=$i\">$i &nbsp;</a>";
                }
            ?>
            <a href="#">&raquo;</a>
        </div>
    </div>
</div>
<div>
    <div>
        <?php if ($data['editStatus'] == false): ?>
            <h2>Add Country</h2>
            <form action="/akademijai/public/HomeController/create" method="post">
                <div class="addOrEditForm">
                    <label>Country name</label>
                    <div>
                        <input class="inputForAddOrEdit" type="text" pattern="^[A-Za-z-]+$" maxlength="50" title="Only alphabetical letters and - if needed" name="country_name" required="required"/>
                    </div>
                    <label>Area(sq.km)</label>
                    <div>
                        <input class="inputForAddOrEdit" type="number" min="1" maxlength="11" name="area" required="required"/>
                    </div>
                    <label>People count</label>
                    <div>
                        <input class="inputForAddOrEdit" type="number" min="0" maxlength="11" name="inhabitants_count" required="required"/>
                    </div>
                    <label>Phone code</label>
                    <div>
                        <input class="inputForAddOrEdit" maxlength="255" type="number" min="1" name="phone_code" required="required"/>
                    </div>
                </div>
                <div>
                    <button type="submit">Save</button>
                </div>
            </form>
        <?php else: ?>
            <h2>Edit Country</h2>
            <form action="/akademijai/public/HomeController/updateCountryById?id=<?php echo $data['country'][0]['id']?>" method="post">
                <label>Country name</label>
                <div>
                    <input class="inputForAddOrEdit" type="text" name="country_name" maxlength="50" required="required" pattern="^[A-Za-z-]+$" title="Only alphabetical letters and - if needed" value="<?php echo $data['country'][0]['name']?>"/>
                </div>
                <label>Area(sq.km)</label>
                <div>
                    <input class="inputForAddOrEdit" type="number" name="area" maxlength="11" required="required" min="1" value="<?php echo $data['country'][0]['area']?>"/>
                </div>
                <label>People count</label>
                <div>
                    <input class="inputForAddOrEdit" type="number" name="inhabitants_count" maxlength="11" min="0" required="required" value="<?php echo $data['country'][0]['inhabitants_count']?>"/>
                </div>
                <label>Phone code</label>
                <div>
                    <input class="inputForAddOrEdit" type="number" name="phone_code" maxlength="255" min="1" required="required" value="<?php echo $data['country'][0]['phone_code']?>"/>
                </div>
                <div>
                    <button type="submit">Update</button>
                </div>
            </form>
        <?php endif;?>
    </div>
</div>
</body>
</html>