<?php

class HomeController extends Controller
{
    public function index()
    {
        $this->activate();
        if (isset($_SESSION['filterStatus'])) {
            if (isset($_SESSION['sort'])) {
                $result = $this->choiceToGetData();
                $pagesCount = $this->pages($this->tableElementFilterCount());
                $this->view('countries', ['result' => $result, 'pagesCount' => $pagesCount,  'editStatus' => false]);
            }
            $this->filterDataByDate();
        }
        if (isset($_SESSION['searchStatus'])) {
            if (isset($_SESSION['sort'])) {
                $result = $this->choiceToGetData();
                $pagesCount = $this->pages($this->tableElementSearchedCount());
                $this->view('countries', ['result' => $result, 'pagesCount' => $pagesCount,  'editStatus' => false]);
            }
            $this->searchData();
        }
        $result = $this->choiceToGetData();
        $pagesCount = $this->pages($this->tableElementCount());
        $this->view('countries', ['result' => $result, 'pagesCount' => $pagesCount,  'editStatus' => false]);
    }

    public function activate()
    {
        if(!isset($_GET['param'])) {
            $_SESSION['currentPageNumber'] = 1;
        } else {
            $_SESSION['currentPageNumber'] = intval($_GET['param']);
        }
        if (isset($_GET['sort'])) {
            $_SESSION['sort'] = $_GET['sort'];
        }
    }

    public function redirectToCities()
    {
        include_once('CityController.php');
        $_SESSION['countryId'] = intval($_GET['id']);
        $_SESSION['country'] = $_GET['country'];
        $cityObj = new CityController;
        $this->clearFilter();
        $this->clearSearch();
        unset($_SESSION['sort']);
        unset($_SESSION['currentPageNumber']);
        $cityObj->index();
    }

    public function create()
    {
        unset($_SESSION['filterStatus']);
        $connection = $this->connect();
        $countryName = $_POST['country_name'];
        $countryArea = $_POST['area'];
        $country_inhabitants_count = $_POST['inhabitants_count'];
        $country_phone_code = $_POST['phone_code'];
        $today_date = date("Y-m-d");
        $data = "INSERT INTO countries(name, area, inhabitants_count, phone_code, date_created) VALUES ('$countryName',
                                    '$countryArea',$country_inhabitants_count, $country_phone_code, '$today_date')";
        $query = $connection->query($data);
        $_POST = '';
        $connection->close();
        $this->clear();
    }

    public function deleteCountryAndCities()
    {
        $id = intval($_GET['id']);
        $connection = $this->connect();

        // Remove selected country cities

        $sql = "DELETE FROM cities WHERE fk_countryId = '$id'";
        $query = $connection->query($sql);

        // Remove selected country

        $sql = "DELETE FROM countries WHERE id = '$id'";
        $query = $connection->query($sql);

        $connection->close();
        header("Location: /akademijai/public/");
        $this->index();
    }

    public function getCountryById()
    {
        $finalResult = [];
        $id = intval($_GET['id']);
        $allCountries = [];
        $pagesCount = 0;
        $connection = $this->connect();
        if (isset($_SESSION['filterStatus'])) {
            $_SESSION['filterStatuss'] = true;
            $allCountries = $this->filterDataByDate();
            $pagesCount = $this->pages($this->tableElementFilterCount());
        } else if (isset($_SESSION['searchStatus'])) {
            $_SESSION['searchStatuss'] = true;
            $allCountries = $this->searchData();
            $pagesCount = $this->pages($this->tableElementSearchedCount());
        } else {
            $allCountries = $this->choiceToGetData();
            $pagesCount = $this->pages($this->tableElementCount());
        }
        $data = "SELECT * FROM countries WHERE id = '$id'";
        $result = $connection->query($data);
        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        }
        $connection->close();
        $this->view('countries', ['country' => $finalResult, 'pagesCount' => $pagesCount,
            'result' => $allCountries, 'editStatus' => true]);
    }

    public function updateCountryById()
    {
        $id = intval($_GET['id']);
        $countryName = $_POST['country_name'];
        $countryArea = $_POST['area'];
        $country_inhabitants_count = $_POST['inhabitants_count'];
        $country_phone_code = $_POST['phone_code'];
        $connection = $this->connect();
        $sql = "UPDATE countries SET name = '$countryName', area = '$countryArea', 
            inhabitants_count = '$country_inhabitants_count', phone_code = '$country_phone_code' WHERE id = '$id'";
        $result = $connection->query($sql);
        $connection->close();
        $this->index();
    }

    public function choiceToGetData()
    {
        if (isset($_SESSION['sort'])) {
            $result = $this->getSortedData();
            return $result;
        } else {
            $result = $this->getAllCountries();
            return $result;
        }
    }

    public function getSortedData()
    {
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        if (isset($_SESSION['filterStatus'])) {
            $date = $_SESSION['date'];
            $sql_asc = "SELECT * FROM countries WHERE date_created = '$date' ORDER BY name ASC LIMIT 10 OFFSET $offset";
            $sql_desc = "SELECT * FROM countries WHERE date_created = '$date' ORDER BY name DESC LIMIT 10 OFFSET $offset";
            $finalResult = $this->sortData($sql_asc, $sql_desc);
            return $finalResult;
        } else if (isset($_SESSION['searchStatus'])) {
            $text = $_SESSION['search_text'];
            $sql_asc = "SELECT * FROM countries WHERE name LIKE '%$text%' OR area LIKE '%$text%' 
            OR inhabitants_count LIKE '%$text%' OR phone_code LIKE '%$text%' ORDER BY name ASC LIMIT 10 OFFSET $offset";
            $sql_desc = "SELECT * FROM countries WHERE name LIKE '%$text%' OR area LIKE '%$text%' 
            OR inhabitants_count LIKE '%$text%' OR phone_code LIKE '%$text%' ORDER BY name DESC LIMIT 10 OFFSET $offset";
            $finalResult = $this->sortData($sql_asc, $sql_desc);
            return $finalResult;
        } else {
            $sql_asc = "SELECT * FROM countries ORDER BY name ASC LIMIT 10 OFFSET $offset";
            $sql_desc = "SELECT * FROM countries ORDER BY name DESC LIMIT 10 OFFSET $offset";
            $finalResult = $this->sortData($sql_asc, $sql_desc);
            return $finalResult;
        }
    }

    public function sortData($sql_asc, $sql_desc)
    {
        $finalResult = [];
        $connection = $this->connect();
        if ($_SESSION['sort'] == 'asc') {
            $data = $sql_asc;
            $result = $connection->query($data);
            while ($tmp = $result->fetch_assoc()) {
                $finalResult[] = $tmp;
            }
        } else {
            $data = $sql_desc;
            $result = $connection->query($data);
            while ($tmp = $result->fetch_assoc()) {
                $finalResult[] = $tmp;
            }
        }
        $connection->close();

        return $finalResult;
    }

    public function getAllCountries()
    {
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        $finalResult = [];
        $connection = $this->connect();
        $data = "SELECT * FROM countries LIMIT 10 OFFSET $offset";
        $result = $connection->query($data);

        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        }
        $connection->close();

        return $finalResult;
    }
    public function pages($elementsCount)
    {
        $elementsInPage = 10;
        $pagesCount = 1;
        if ($elementsCount <= $elementsInPage) {
            return $pagesCount;
        } else {
            if ($elementsCount % $elementsInPage == 0) {
                return intval($elementsCount / $elementsInPage);
            } else {
                return intval(($elementsCount / $elementsInPage) + 1);
            }
        }
    }

    public function filterDataByDate()
    {
        $this->clearSearch();
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        $_SESSION['filterStatus'] = true;
        $finalResult = [];
        if (isset($_POST['date'])) {
            $_SESSION['date'] = $_POST['date'];
        }
        $convertedData = $_SESSION['date'];
        $connection = $this->connect();
        $sql = "SELECT * FROM countries WHERE date_created = '$convertedData' LIMIT 10 OFFSET $offset";
        $result = $connection->query($sql);
        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        }
        $connection->close();
        if (isset($_SESSION['filterStatuss'])) {
            $_SESSION['result'] = $finalResult;
            unset($_SESSION['filterStatuss']);
            return $finalResult;
        } else {
            $pagesCount = $this->pages($this->tableElementFilterCount());
            $this->view('countries', ['result' => $finalResult, 'pagesCount' => $pagesCount, 'editStatus' => false]);
        }

        return 0;
    }

    public function clearFilter()
    {
        unset($_SESSION['filterStatus']);
        unset($_SESSION['filterStatuss']);
        unset($_SESSION['date']);
    }

    public function clearSearch()
    {
        unset($_SESSION['searchStatus']);
        unset($_SESSION['searchStatuss']);
        unset($_SESSION['search_text']);
    }

    public function clear()
    {
        $this->clearSearch();
        $this->clearFilter();
        unset($_SESSION['sort']);
        $this->index();
    }

    public function abortSort()
    {
        unset($_SESSION['sort']);
        $this->index();
    }

    public function connect()
    {
        $connection = mysqli_connect("localhost", "root", "", "akademijai");
        if (mysqli_connect_errno()) {
            echo "Error " . mysqli_connect_error();
            die();
        }

        return $connection;
    }

    public function tableElementCount()
    {
        $connection = $this->connect();
        $elementsCount = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM countries"));
        return $elementsCount;
    }

    public function tableElementFilterCount()
    {
        $convertedData = $_SESSION['date'];
        $connection = $this->connect();
        $elementsCount = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM countries 
        WHERE date_created = '$convertedData'"));

        return $elementsCount;
    }

    public function tableElementSearchedCount()
    {
        $text = $_SESSION['search_text'];
        $connection = $this->connect();
        $elementsCount = mysqli_num_rows(mysqli_query($connection, "SELECT * FROM countries WHERE name 
            LIKE '%$text%' OR area LIKE '%$text%' OR inhabitants_count LIKE '%$text%' OR phone_code LIKE '%$text%'"));

        return $elementsCount;
    }

    public function searchData()
    {
        $this->clearFilter();
        $offset = ($_SESSION['currentPageNumber'] - 1) * 10;
        $_SESSION['searchStatus'] = true;
        $finalResult = [];
        if (isset($_POST['text'])) {
            $_SESSION['search_text'] = $_POST['text'];
        }
        $text = $_SESSION['search_text'];
        $connection = $this->connect();
        $sql = "SELECT * FROM countries WHERE name LIKE '%$text%' OR area LIKE '%$text%' OR inhabitants_count LIKE '%$text%' OR phone_code LIKE '%$text%' LIMIT 10 OFFSET $offset";
        $result = $connection->query($sql);
        while ($tmp = $result->fetch_assoc()) {
            $finalResult[] = $tmp;
        };
        $connection->close();
        if (isset($_SESSION['searchStatuss'])) {
            $_SESSION['result'] = $finalResult;
            unset($_SESSION['searchStatuss']);
            return $finalResult;
        } else {
            $pagesCount = $this->pages($this->tableElementSearchedCount());
            $this->view('countries', ['result' => $finalResult, 'pagesCount' => $pagesCount, 'editStatus' => false]);
        }

        return 0;
    }
}