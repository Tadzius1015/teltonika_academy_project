<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" type="text/css" href="/akademijai/public/css/program.css">
    <title>Cities</title>
</head>
<body>
<div>
    <div>
        <label>Filter by date:</label>
        <form action="/akademijai/public/CityController/filterDataByDate" method="post">
            <input class="inputForAddOrEdit" type="date" name="date" required="required">
            <button type="submit">Filter</button>
        </form>
        <div>
            <div class="right">
                <a class="buttonLink1" href="/akademijai/public/CityController/clear">Back to country cities list</a>
                <a class="buttonLink2" href="/akademijai/public/CityController/redirectToCountries">Back to countries list</a>
            </div>
        </div>
    </div>
    <div>
        <label>Search cities:</label>
        <form action="/akademijai/public/CityController/searchData" method="post">
            <input class="inputForAddOrEdit" type="text" name="text" required="required">
            <button type="submit">Search</button>
        </form>
    </div>
    <div class="center">
        <h1><?php echo $_SESSION['country']?> cities:</h1>
    </div>
    <table class="table">
        <tr>
            <th>
                City name
                <img class="cursor" src="/akademijai/public/img/sort_asc_icon.png" onclick="window.location.href='/akademijai/public/CityController/index?sort=<?php echo 'asc' ?>'">
                <img class="cursor" src="/akademijai/public/img/sort_desc_icon.png" onclick="window.location.href='/akademijai/public/CityController/index?sort=<?php echo 'desc' ?>'">
                <img class="cursor" src="/akademijai/public/img/cancel_icon.png" onclick="window.location.href='/akademijai/public/CityController/abortSort'">
            </th>
            <th>Area(sq.km)</th>
            <th>People count</th>
            <th>Postal Code</th>
            <th>Choices</th>
        </tr>
        <?php foreach ($data['result'] as $value):;?>
            <tr>
                <td><?php echo $value['name'];?></td>
                <td><?php echo $value['area'];?></td>
                <td><?php echo $value['inhabitants_count'];?></td>
                <td><?php echo $value['postal_code'];?></td>
                <td><a class="buttonLink1" href="/akademijai/public/CityController/getCityById?id=<?php echo $value['id'] ?>">Edit</a>
                    <a class="buttonDanger" href="/akademijai/public/CityController/deleteCity?id=<?php echo $value['id'] ?>">Delete</a>
                </td>
            </tr>
        <?php endforeach;?>
    </table>
    <div class="center">
        <div class="paging">
            <a href="#">&laquo;</a>
            <?php for ($i = 1; $i <= $data['pagesCount']; $i++)
                if ($i == $_SESSION['currentPageNumber']) {
                    echo "<a class='active' href=\"/akademijai/public/CityController/index?param=$i\">$i &nbsp;</a>";
                }
                else {
                    echo "<a href=\"/akademijai/public/CityController/index?param=$i\">$i &nbsp;</a>";
                }
            ?>
            <a href="#">&raquo;</a>
        </div>
    </div>
</div>
    <div>
        <?php if ($data['editStatus'] == false): ?>
        <h2>Add City</h2>
        <div>
            <form action="/akademijai/public/CityController/create?id=<?php echo $data['countryId'] ?>"" method="post">
                <label>City name</label>
                <div>
                    <input class="inputForAddOrEdit" type="text" name="city_name" maxlength="50" pattern="^[A-Za-z-]+$" title="Only alphabetical letters and - if needed" required="required"/>
                </div>
                <label>Area(sq.km)</label>
                <div>
                    <input class="inputForAddOrEdit" type="number" min="1" maxlength="11" name="area" required="required"/>
                </div>
                <label>People count</label>
                <div>
                    <input class="inputForAddOrEdit" type="number" min="0" maxlength="11" name="inhabitants_count" required="required"/>
                </div>
                <label>Postal code</label>
                <div>
                    <input class="inputForAddOrEdit" type="number" min="1" maxlength="8" name="postal_code" required="required"/>
                </div>
                <div>
                    <button type="submit">Save</button>
                </div>
            </form>
            <?php else: ?>
                <h2>Edit City</h2>
                <form action="/akademijai/public/CityController/updateCity?id=<?php echo $data['city'][0]['id']?>" method="post">
                    <label>City name</label>
                    <div>
                        <input class="inputForAddOrEdit" type="text" name="city_name" required="required" maxlength="50" pattern="^[A-Za-z-]+$" title="Only alphabetical letters and - if needed" value="<?php echo $data['city'][0]['name']?>"/>
                    </div>
                    <label>Area(sq.km)</label>
                    <div>
                        <input class="inputForAddOrEdit" type="number" name="area" min="1" maxlength="11" required="required" value="<?php echo $data['city'][0]['area']?>"/>
                    </div>
                    <label>People count</label>
                    <div>
                        <input class="inputForAddOrEdit" type="number" name="inhabitants_count" min="0" maxlength="11" required="required" value="<?php echo $data['city'][0]['inhabitants_count']?>"/>
                    </div>
                    <label>Postal code</label>
                    <div>
                        <input class="inputForAddOrEdit" type="number" name="postal_code" min="1" maxlength="8" required="required" value="<?php echo $data['city'][0]['postal_code']?>"/>
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